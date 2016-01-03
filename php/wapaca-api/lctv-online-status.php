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

require( 'livecodingAuth.php' );
require( 'online-status-svg-v1.php' );


define( "CLIENT_ID", getenv( 'LCTV_CLIENT_ID' ) );
define( "CLIENT_SECRET", getenv( 'LCTV_CLIENT_SECRET' ) );
define( "REDIRECT_URL", getenv( 'LCTV_REDIRECT_URL' ) );
define( "CHANNEL_NAME", ( ( isset($_GET['channel']) ) ? strtolower( htmlspecialchars( $_GET['channel'] ) ) : '' ) );
define( "BADGE_STYLE", ( ( isset($_GET['style']) ) ? strtolower( htmlspecialchars( $_GET['style'] ) ) : '' ) );
define( 'INVALID_CHANNEL_MSG', 'You must specify a channel name like: lctv-online-status.php?channel=channel-name.' );
define( 'NOT_AUTHORIZED_MSG', 'This app is not yet authorized with the livecoding.tv API. Try runnig authorize.php first.' );
define( "V2_IMAGE_STYLE", 'online-status-v2-png' );
define( "V2_ONLINE_PNG", '../img/lctv-online.png' );
define( "V2_OFFLINE_PNG", '../img/lctv-offline.png' );
define( "V3_IMAGE_STYLE", 'online-status-v3-svg' );
define( "V3_ONLINE_SVG", 'lctv-online.svg' );
define( "V3_OFFLINE_SVG", 'lctv-offline.svg' );


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

	global $LivecodingAuth;

	// Fetch some data from the API
	$data = $LivecodingAuth->fetchData( "livestreams/$channel/" );
var_dump($data);
	// Select the online status from the data struct
	$is_online = $data->is_live;

	return $is_online;

}


/* Bail if no channel name. */
if ( empty( constant( "CHANNEL_NAME" ) ) ) {
	die( INVALID_CHANNEL_MSG );
}

/* Instantiate authorizarion helper. */
try {

// 	$LivecodingAuth = new LivecodingAuth( CLIENT_ID, CLIENT_SECRET, REDIRECT_URL,
// 	                                      READ_SCOPE, TEXT_STORE );
	$LivecodingAuth = new LivecodingAuth( CLIENT_ID, CLIENT_SECRET, REDIRECT_URL );
}
catch( Exception $ex ) {
	// Error initializing library. Display error message.

	die( $ex->getMessage() );

}

/* Bail if we are not authorized with the livecoding.tv API. */
if ( !$LivecodingAuth->getIsAuthorized() ) {
	die( NOT_AUTHORIZED_MSG );
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
} else if ( $is_online ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'livecoding.tv', 'streaming now', '#4c1' );
} else {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'livecoding.tv', 'offline', '#e05d44' );
}
