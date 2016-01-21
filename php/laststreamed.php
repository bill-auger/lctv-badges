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
require_once( 'lctv_badges_init.php' );

/** Load the API. */
$lctv_api = new LCTVAPI( array(
	'client_id'     => LCTV_CLIENT_ID,
	'client_secret' => LCTV_CLIENT_SECRET,
	'user'          => $channel,
) );

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo get_badge_svg( 'lctv last streamed', 'error', '#e05d44' );
	exit();
}

/** Get users latest videos. */
$api_request = $lctv_api->api_request( 'v1/user/videos/latest/' );

/** Bail on error. */
if ( $api_request === false || isset( $api->request->detail ) ) {
	header( "Content-type:image/svg+xml" );
	echo get_badge_svg( 'lctv last streamed', 'error', '#e05d44' );
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
	echo get_badge_svg( 'lctv last streamed', date( 'M j, Y' ,strtotime( $api_request->result[0]->creation_time) ), '#4c1', $link );
} else {
	echo get_badge_svg( 'lctv last streamed', 'never', '#e05d44', $link );
}