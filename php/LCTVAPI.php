<?php
/**
 * Livecoding.tv API.
 * 
 * @package LCTVBadges\LCTVAPI
 * @since 0.0.3
 */

/** Check if script is accessed directly. */
if ( basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
  exit();
}

/** Set path for flat file data store if not set. */
if ( ! defined( 'LCTVAPI_DATA_PATH' ) ) {
	define( 'LCTVAPI_DATA_PATH', __DIR__ );
}

/** Set cache expire time if not set. */
if ( ! defined( 'LCTVAPI_CACHE_EXPIRES_IN' ) ) {
	define( 'LCTVAPI_CACHE_EXPIRES_IN', 300 );
}

/**
 * Livecoding.tv API class.
 *
 * @since 0.0.3
 */
class LCTVAPI {

	/**
	 * App client id.
	 * 
	 * Used for authorization and token retrieval.
	 * 
	 * @var string
	 */
	public $client_id = '';

	/**
	 * App client secret.
	 * 
	 * Used for authorization and token retrieval.
	 * 
	 * @var string
	 */
	public $client_secret = '';

	/**
	 * Redirect url after livecoding.tv authorization.
	 * 
	 * Used for authorization code return from API.
	 * 
	 * @var string URL.
	 */
	public $redirect_url = '';

	/**
	 * Token.
	 * 
	 * Used to make API calls.
	 * 
	 * @var stdClass|boolean
	 */
	public $token = false;

	/**
	 * Scope.
	 * 
	 * Used to define authorization scope.
	 * 
	 * @var string
	 */
	public $scope = 'read read:user read:channel';

	/**
	 * User name/slug.
	 * 
	 * Used to identify the user token for API calls.
	 * 
	 * @var string
	 */
	public $user = '';

	/**
	 * Data store.
	 * 
	 * Used to store, recall and delete data.
	 * 
	 * @var string|data store object
	 */
	private $data_store = 'LCTVAPIDataStoreFlatFiles';

	/**
	 * Constructor.
	 *
	 * Supplied $args override class property defaults.
	 *
	 * @since 0.0.3
	 *
	 * @param array $args Arguments to override class property defaults.
	 */
	function __construct( $args = array() ) {

		/** Check that curl is available. */
		if ( ! function_exists( 'curl_version' ) ) {
			echo 'Curl required.';
			exit();
		}

		/** Override class property defaults. */
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[$key] ) ) {
				$this->$key = $args[$key];
			}
		}

		/** Instatiate data store. */
		$this->data_store = new $this->data_store();

		/** Delete user token and all user data. */
		if ( isset( $_GET['delete'] ) && isset( $_GET['user'] ) ) {
			$token_del = $this->data_store->get_data( $_GET['user'], 'token' );
			if ( $_GET['delete'] === $token_del->delete_id ) {
				$this->data_store->delete_data( $_GET['user'], '*' );
			}
		}

		/** Attempt to get token data. */
		$this->token = $this->data_store->get_data( $this->user, 'token' );

		/** If the API isn't authorized start a PHP session. */
		if ( ! $this->is_authorized() ) {
			session_start();
		}

		/** Received authorization redirect from API. */
		if ( isset( $_GET['state'] ) && isset( $_GET['code'] ) && session_status() === PHP_SESSION_ACTIVE ) {
			if ( $_GET['state'] === session_id() ) {
				$this->token = new stdClass();
				$this->token->code = $_GET['code'];
			}
		}

		/** Check the token. */
		$this->check_token();

	}

	/**
	 * Check the token and get/refresh it if necessary.
	 *
	 * @since 0.0.3
	 * @access public
	 */
	public function check_token() {

		/** No token to check or errored token. */
		if ( $this->token === false || isset( $this->token->error ) ) {
			return;
		}

		/** Token hasn't expired yet. */
		if ( isset( $this->token->access_token ) && ( time() - $this->token->created_at ) < $this->token->expires_in ) {
			return;
		}

		/** POST parameters for token request. */
		$token_params = array(
			'grant_type'   => 'authorization_code',
			'code'         => $this->token->code,
			'redirect_uri' => $this->redirect_url,
		);

		/** Add refresh POST parameters if available. */
		if ( isset( $this->token->refresh_token ) ) {
			$token_params['grant_type'] = 'refresh_token';
			$token_params['refresh_token'] = $this->token->refresh_token;
		}

		/** Token request headers. */
		$token_headers = array(
			'Cache-Control: no-cache',
			'Pragma: no-cache',
			'Authorization: Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret ),
		);

		/** POST token request to API. */
		$token_ret = $this->post_url_contents( 'https://www.livecoding.tv/o/token/', $token_params, $token_headers );
		$token = json_decode( $token_ret );

		/** Stop checking on error. */
		if ( isset( $token->error ) ) {
			return;
		}

		/** Setup new token and save to data store. */
		$token->code = $this->token->code;
		$token->created_at = time();
		$this->token = $token;
		$this->token->delete_id = uniqid();
		$user = $this->api_request( 'v1/user/' );
		if ( ! isset( $user->result->detail ) ) {
			if ( empty( $this->user ) ) {
				$this->user = $user->result->slug;
			}
			$this->data_store->put_data( $user->slug, 'token', $this->token );
		}

	}

	/**
	 * Get authorization url.
	 *
	 * @since 0.0.3
	 * @access public
	 */
	public function get_authorization_url() {

		/** If PHP session hasn't been started, start it now. */
		if ( session_status() !== PHP_SESSION_ACTIVE ) {
			if ( session_start() === false ) {
				return '';
			}
		}

		return 'https://www.livecoding.tv/o/authorize/' .'
			?scope=' . urlencode( $this->scope ) .
			'&state=' . urlencode( session_id() ) .
			'&redirect_uri=' . urlencode( $this->redirect_url ) .
			'&response_type=code' .
			'&client_id=' . urlencode( $this->client_id );

	}

	/**
	 * Check if there's a token.
	 *
	 * @since 0.0.3
	 * @access public
	 */
	public function is_authorized() {

		return ( $this->token !== false && isset( $this->token->access_token ) );

	}

	/**
	 * Make an API request/call.
	 *
	 * @since 0.0.3
	 * @access public
	 * 
	 * @param string $api_path API endpoint. ex: 'v1/livestreams/'
	 */
	public function api_request( $api_path ) {

		/** Check if we're authorized. */
		if ( ! $this->is_authorized() ) {
			$ret = new stdClass();
			$ret->result->detail = 'LCTVAPI not authorized';
			return $ret;
		}

		/** Setup api request type for data store. */
		$api_request_type = str_replace( '/', '', $api_path );

		/** Attempt to load API request from cache. */
		$api_cache = $this->data_store->get_data( $this->user, $api_request_type );

		/** Make API request call if we have no cache or if cache is expired. */
		if ( ! isset( $api_cache->created_at ) || ( time() - $api_cache->created_at ) > LCTVAPI_CACHE_EXPIRES_IN ) {

			$headers = array(
				'Cache-Control: no-cache',
				'Pragma: no-cache',
				'Authorization: ' . $this->token->token_type . ' ' . $this->token->access_token,
			);

			$api_ret = $this->get_url_contents( 'https://www.livecoding.tv:443/api/' . $api_path, $headers );

			$api_cache = new stdClass();
			$api_cache->result = json_decode( $api_ret, false );
			$api_cache->created_at = time();
			$this->data_store->put_data( $this->user, $api_request_type, $api_cache );

		}

		return $api_cache;

	}

	/**
	 * Curl GET request.
	 *
	 * @since 0.0.3
	 * @access private
	 */
	private function get_url_contents( $url, $custom_header = [] ) {

		$crl = curl_init();
		curl_setopt( $crl, CURLOPT_HTTPHEADER, $custom_header );
		curl_setopt( $crl, CURLOPT_URL, $url );
		curl_setopt( $crl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $crl, CURLOPT_CONNECTTIMEOUT, 5 );
		$ret = curl_exec( $crl );
		curl_close( $crl );

		return $ret;

	}

	/**
	 * Curl POST request.
	 *
	 * @since 0.0.3
	 * @access private
	 */
	private function post_url_contents( $url, $fields, $custom_header = [] ) {

		$fields_string = '';
		foreach( $fields as $key => $value ) {
			$fields_string .= $key . '=' . urlencode( $value ) . '&';
		}
		rtrim( $fields_string, '&' );

		$crl = curl_init();
		curl_setopt( $crl, CURLOPT_HTTPHEADER, $custom_header );
		curl_setopt( $crl, CURLOPT_URL, $url );
		if ( ! empty( $fields_string ) ) {
			curl_setopt( $crl, CURLOPT_POST, count( $fields ) );
			curl_setopt( $crl, CURLOPT_POSTFIELDS, $fields_string );
		}
		curl_setopt( $crl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $crl, CURLOPT_CONNECTTIMEOUT, 5 );
		$ret = curl_exec( $crl );
		curl_close( $crl );

		return $ret;

	}

}

/**
 * Flat file data store class.
 *
 * @since 0.0.3
 */
class LCTVAPIDataStoreFlatFiles {

	/**
	 * Get data.
	 * 
	 * Data is always an object, and should be returned as an object.
	 *
	 * @since 0.0.3
	 * @access public
	 * 
	 * @param string $user User name/slug.
	 * @param string $type Data type.
	 * 
	 * @return bool|stdClass False on failure, object on success.
	 */
	public function get_data( $user, $type ) {

		if ( empty( $user ) || empty( $type ) || ! file_exists( LCTV_DATA_PATH . $user . '.' . $type ) ) {
			return false;
		}

		return json_decode( file_get_contents( LCTV_DATA_PATH . $user . '.' . $type ), false );

	}

	/**
	 * Put/store data.
	 * 
	 * Data is always an object. False should be returned on failure,
	 * any other value will be considered a success.
	 *
	 * @since 0.0.3
	 * @access public
	 * 
	 * @param string   $user User name/slug.
	 * @param string   $type Data type.
	 * @param stdClass $data Data object.
	 * 
	 * @return bool|int False on failure, number of bytes written on success.
	 */
	public function put_data( $user, $type, $data ) {

		if ( empty( $user ) || empty( $type ) || empty( $data ) ) {
			return false;
		}

		return file_put_contents( LCTV_DATA_PATH . $user . '.' . $type, json_encode( $data ) );

	}

	/**
	 * Delete data.
	 *
	 * Data type ($type) may include a '*' wildcard to indicate all data.
	 *
	 * @since 0.0.3
	 * @access public
	 * 
	 * @param string   $user User name/slug.
	 * @param string   $type Data type.
	 * 
	 * @return bool False on failure, true on success.
	 */
	public function delete_data( $user, $type ) {

		if ( empty( $user ) || empty( $type ) ) {
			return false;
		}

		if ( strpos( $type, '*' ) !== false ) {
			array_map( 'unlink', glob( LCTV_DATA_PATH . $user . '.' . $type ) );
			return true;
		} else {
			return unlink( LCTV_DATA_PATH . $user . '.' . $type );
		}

	}

}