<?php
/**
 * Livecoding.tv Live Viewers Badges.
 *
 * Get the amount of live viewers for a channel and return an appropriate svg image.
 *
 * @param string channel (required) LCTV channel name.
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

/** Initialize. */
require_once( '../api/LctvApi.php' );
require_once( '../img/v1/lctv-badges-svg-v1.php' );

/** Load the API. */
$lctv_api = new LCTVAPI(LCTV_MASTER_USER);

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'lctv viewers', 'error', '#e05d44' );
	exit();
}

/** Get live streaming info for a channel. */
$api_request = $lctv_api->api_request( 'v1/livestreams/' . urlencode( $channel ) . '/' );

/** Bail on error. */
if ( $api_request === false ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'lctv viewers', 'error', '#e05d44' );
	exit();
}

/** API returned an error. This happens if user is not streaming. */
if ( isset( $api_request->result->detail ) ) {
	$api_request->result->is_live = false;
	$api_request->result->viewers_live = 0;
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
	echo make_badge_svg_v1( 'lctv viewers', $api_request->result->viewers_live, '#4c1', $link );
} else {
	echo make_badge_svg_v1( 'lctv viewers', $api_request->result->viewers_live, '#e05d44', $link );
}