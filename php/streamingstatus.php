<?php
/**
 * Livecoding.tv Streaming Status Badges.
 *
 * Check if the channel is currently streaming and return an appropriate svg image.
 *
 * @param string channel (required) LCTV channel name.
 * @param string online  (optional) Button message if status is streaming.
 * @param string offline (optional) Button message if status is not streaming.
 * @param string title   (optional) true/false to show streaming title when live,
 *                                  this will override the online message.
 * @param string link    (optional) true/false to automatically link to channel.
 *
 * @package LCTVBadges\Badges
 * @since 0.0.3
 */

/** Bail if no channel name. */
if ( ! isset( $_GET['channel'] ) || empty( $_GET['channel'] ) ) {
	exit();
}

/** Set the channel name. */
$channel = strtolower( $_GET['channel'] );
/** Set the online message. */
$online_message = ( isset( $_GET['online'] ) && ! empty( $_GET['online'] ) ) ? $_GET['online'] : 'online';
/** Set the offline message. */
$offline_message = ( isset( $_GET['offline'] ) && ! empty( $_GET['offline'] ) ) ? $_GET['offline'] : 'offline';

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
$api_request = $lctv_api->api_request( 'v1/livestreams/' . urlencode( $channel ) . '/' );

/** Bail on error. */
if ( $api_request === false ) {
	exit();
}

/** API returned an error. */
if ( isset( $api_request->result->detail ) ) {
	$api_request->result->is_live = false;
}

/** Display live stream title instead of 'online'. */
if ( isset( $_GET['title'] ) && strtolower( $_GET['title'] ) === 'true' ) {
	if ( ! empty( $api_request->result->title ) ) {
		$online_message = $api_request->result->title;
	}
}

/** Check to auto link. */
if ( isset( $_GET['link'] ) && strtolower( $_GET['link'] ) === 'true' ) {
	$link = 'https://www.livecoding.tv/' . urlencode( $channel ) . '/';
} else {
	$link = '';
}

/** Output svg image. */
header( "Content-type:image/svg+xml" );
if ( $api_request->result->is_live ) {
	echo get_badge_svg( 'livecoding.tv', $online_message, '#4c1', $link );
} else {
	echo get_badge_svg( 'livecoding.tv', $offline_message, '#e05d44', $link );
}