<?php
/**
 * Livecoding.tv Badges.
 *
 * Queries the livecoding.tv API for the current online status
 * of a channel and displays an online or offline badge.
 *
 * @param string channel - (required) LCTV channel name
 * @param string style   - (optional) badge style - one of:
 *                           * online-status-v2-png
 *                           * online-status-v3-svg
 *
 * @author  Trevor Anderson <andtrev@gmail.com>
 * @license GPLv3
 * @package LCTVBadges
 * @version 0.0.3
 */


require( '../api/LctvApi.php' );
require( '../img/v1/lctv-badges-svg-v1.php' );


define( 'CHANNEL_NAME', ( ( isset($_GET['channel']) ) ? strtolower( htmlspecialchars( $_GET['channel'] ) ) : '' ) );
define( 'BADGE_STYLE', ( ( isset($_GET['style']) ) ? strtolower( htmlspecialchars( $_GET['style'] ) ) : '' ) );
define( 'INVALID_CHANNEL_MSG', 'You must specify a channel name like: lctv-online-status.php?channel=YOUR_LCTV_CHANNEL_NAME.' );
define( 'V1_IMAGE_STYLE', 'online-status-v1' );
define( 'V2_IMAGE_STYLE', 'online-status-v2' );
define( 'V2_ONLINE_PNG', '../img/v2/lctv-online.png' );
define( 'V2_OFFLINE_PNG', '../img/v2/lctv-offline.png' );
define( 'V3_IMAGE_STYLE', 'online-status-v3' );
define( 'V3_ONLINE_SVG', '../img/v3/lctv-online.svg' );
define( 'V3_ONLINE_HOVER_SVG', '../img/v3/lctv-online-hover.svg' );
define( 'V3_ONLINE_PUSHED_SVG', '../img/v3/lctv-online-pushed.svg' );
define( 'V3_OFFLINE_SVG', '../img/v3/lctv-offline.svg' );
define( 'V3_OFFLINE_HOVER_SVG', '../img/v3/lctv-offline-hover.svg' );
define( 'V3_OFFLINE_PUSHED_SVG', '../img/v3/lctv-offline-pushed.svg' );


/**
 * Fetch channel online status from the livecoding.tv API.
 *
 * @since 0.0.2
 *
 * @param string $channel - Name of channel to query.
 *
 * @return boolean        - TRUE if channel is online or else FALSE.
 */
function is_online( $channel ) {

	global $Api;

	// Fetch some data from the API
	$api_request = $Api->api_request( 'v1/livestreams/' . urlencode( $channel ) . '/' );
	$data = $api_request->result;

	/** Bail on error. */
	if ( $api_request === false ) {
		header( "Content-type:image/svg+xml" );
		echo make_badge_svg_v1( 'livecoding.tv', 'error', '#e05d44' );
		exit();
	}

	/** API returned an error. This happens if user is not streaming. */
	if ( isset( $data->detail ) ) {
		$data->is_live = false;
	}

	// Select the online status from the data struct
	$is_online = $data->is_live;

	return $is_online;

}


/* Bail if no channel name. */
if ( empty( constant( "CHANNEL_NAME" ) ) ) {
	die( INVALID_CHANNEL_MSG );
}


/* Instantiate the API wrapper. */
try {
	$Api = new LCTVAPI( array(
		'data_store'    => LCTVAPI_DATA_STORE_CLASS,
		'client_id'     => LCTV_CLIENT_ID,
		'client_secret' => LCTV_CLIENT_SECRET,
		'user'          => LCTV_MASTER_USER,
	) );
}
catch( Exception $ex ) {
	// Error initializing library. Display error message.

	die( $ex->getMessage() );

}

/* Bail if we are not authorized with the livecoding.tv API. */
if ( ! $Api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'livecoding.tv', 'error', '#e05d44' );
	exit();
}

/* Fetch online status. */
$is_online = ( is_online( CHANNEL_NAME ) !== false );

/* Output SVG image. */
if ( BADGE_STYLE == V2_IMAGE_STYLE ) {


	echo "bye";
	$status_img_url = ( $is_online ) ? V2_ONLINE_PNG : V2_OFFLINE_PNG;
	header( "Location: $status_img_url" );
	echo "wtf";exit;



} else if ( BADGE_STYLE == V3_IMAGE_STYLE ) {
	header( "Content-type:image/svg+xml" );
	echo file_get_contents( ( $is_online ) ? V3_ONLINE_SVG : V3_OFFLINE_SVG );
} else /* if ( BADGE_STYLE == V1_IMAGE_STYLE ) */ {
	$right_text  = ( $is_online ) ? 'LIVE' : 'offline' ;
	$right_color = ( $is_online ) ? '#4c1' : '#e05d44' ;

	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'livecoding.tv', $right_text, $right_color );
}
