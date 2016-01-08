<?php
/**
 * Livecoding.tv Streaming Status Badges.
 *
 * Check if the channel is currently streaming and return an appropriate svg image.
 *
 * @param string channel (required) LCTV channel name.
 * @param string online  (optional) Button message if status is streaming.
 * @param string offline (optional) Button message if status is not streaming.
 *
 * @package LCTVBadges\Badges
 * @since 0.0.3
 */

/** Bail if no channel name. */
if ( ! isset( $_GET['channel'] ) || empty( $_GET['channel'] ) ) {
	exit();
}

/** Set the channel name. */
define( 'CHANNEL', htmlspecialchars( strtolower( urldecode( $_GET['channel'] ) ) ) );
/** Set the online message. */
define( 'ONLINE_MESSAGE', ( isset( $_GET['online'] ) && ! empty( $_GET['online'] ) ) ? htmlspecialchars( urldecode( $_GET['online'] ) ) : 'online' );
/** Set the offline message. */
define( 'OFFLINE_MESSAGE', ( isset( $_GET['offline'] ) && ! empty( $_GET['offline'] ) ) ? htmlspecialchars( urldecode( $_GET['offline'] ) ) : 'offline' );

/** Initialize. */
require_once( 'lctv_badges_init.php' );

/** Load the API. */
$lctv_api = new LCTVAPI( array(
	'client_id'     => LCTV_CLIENT_ID,
	'client_secret' => LCTV_CLIENT_SECRET,
	'user'          => LCTV_MASTER_USER,
) );

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	exit();
}

/** Get live streaming info for a channel. */
$api_request = $lctv_api->api_request( 'v1/livestreams/' . urlencode( CHANNEL ) . '/' );

/** Bail on error. */
if ( $api_request === false ) {
	exit();
}

/** API returned an error. */
if ( isset( $api_request->result->detail ) ) {
	$api_request->result->is_live = false;
}

/** Output svg image. */
header( "Content-type:image/svg+xml" );
if ( $api_request->result->is_live ) {
	echo get_badge_svg( 'livecoding.tv', ONLINE_MESSAGE, '#4c1' );
} else {
	echo get_badge_svg( 'livecoding.tv', OFFLINE_MESSAGE, '#e05d44' );
}