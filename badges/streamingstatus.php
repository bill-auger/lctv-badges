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


/** LCTV API */
require_once( '../api/LctvApi.php' );
/** Badge v1-svg creator. */
require_once( '../img/v1//lctv-badges-svg-v1.php' );


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

/** Load the API. */
$lctv_api = new LCTVAPI(LCTV_MASTER_USER);

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'livecoding.tv', 'error', '#e05d44' );
	exit();
}

/** Get live streaming info for a channel. */
$api_request = $lctv_api->api_request( 'v1/livestreams/' . urlencode( $channel ) . '/' );

/** Bail on error. */
if ( $api_request === false ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'livecoding.tv', 'error', '#e05d44' );
	exit();
}

/** API returned an error. This happens if user is not streaming. */
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
	echo make_badge_svg_v1( 'livecoding.tv', $online_message, '#4c1', $link );
} else {
	echo make_badge_svg_v1( 'livecoding.tv', $offline_message, '#e05d44', $link );
}