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
require_once( 'lctv_badges_init.php' );

/** Load the API. */
$lctv_api = new LCTVAPI( array(
	'data_store'    => LCTVAPI_DATA_STORE_CLASS,
	'client_id'     => LCTV_CLIENT_ID,
	'client_secret' => LCTV_CLIENT_SECRET,
	'user'          => $channel,
) );

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo get_badge_svg( 'lctv followers', 'error', '#e05d44' );
	exit();
}

/** Get user followers. */
$api_request = $lctv_api->api_request( 'v1/user/followers/' );

/** Bail on error. */
if ( $api_request === false || isset( $api_request->result->detail ) ) {
	header( "Content-type:image/svg+xml" );
	echo get_badge_svg( 'lctv followers', 'error', '#e05d44' );
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
echo get_badge_svg( 'lctv followers', $follower_count, '#4c1', $link );