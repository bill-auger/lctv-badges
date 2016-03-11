<?php
/**
 * Livecoding.tv Last Streamed Badges.
 *
 * Get the last video date and return an appropriate svg image.
 *
 * @param string channel (required) LCTV channel name.
 * @param string link    (optional) true/false to automatically link to channel.
 *
 * @package LCTVBadges\Badges
 * @since 0.0.4
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
$lctv_api = new LCTVAPI($channel);

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'lctv last streamed', 'error', '#e05d44' );
	exit();
}

/** Get users latest videos. */
$api_request = $lctv_api->api_request( 'v1/user/videos/latest/' );

/** Bail on error. */
if ( $api_request === false || isset( $api->request->detail ) ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'lctv last streamed', 'error', '#e05d44' );
	exit();
}

/** Check to auto link. */
if ( isset( $_GET['link'] ) && strtolower( $_GET['link'] ) === 'true' ) {
	$link = 'https://www.livecoding.tv/' . urlencode( $channel ) . '/';
} else {
	$link = '';
}

/** Output svg image. */
header( "Content-type:image/svg+xml" );
if ( is_array( $api_request->result ) && ! empty( $api_request->result[0]->creation_time ) ) {
	echo make_badge_svg_v1( 'lctv last streamed', date( 'M j, Y' ,strtotime( $api_request->result[0]->creation_time) ), '#4c1', $link );
} else {
	echo make_badge_svg_v1( 'lctv last streamed', 'never', '#e05d44', $link );
}