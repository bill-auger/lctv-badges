<?php
/**
 * Livecoding.tv Number of Followers Badges.
 *
 * Get the amount of followers for a user and return an appropriate svg image.
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
$lctv_api = new LCTVAPI($channel);

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'lctv followers', 'error', '#e05d44' );
	exit();
}

/** Get user followers. */
$api_request = $lctv_api->api_request( 'v1/user/followers/' );

/** Bail on error. */
if ( $api_request === false || isset( $api_request->result->detail ) ) {
	header( "Content-type:image/svg+xml" );
	echo make_badge_svg_v1( 'lctv followers', 'error', '#e05d44' );
	exit();
}

/** Count number of followers. */
$follower_count = count( $api_request->result );

/** Check to auto link. */
if ( isset( $_GET['link'] ) && strtolower( $_GET['link'] ) === 'true' ) {
	$link = 'https://www.livecoding.tv/' . urlencode( $channel ) . '/';
} else {
	$link = '';
}

/** Output svg image. */
header( "Content-type:image/svg+xml" );
echo make_badge_svg_v1( 'lctv followers', $follower_count, '#4c1', $link );