<?php
/**
 * Livecoding.tv Number of Followers Badges.
 *
 * Get the amount of followers for a user and return an appropriate svg image.
 *
 * @param string channel (required) LCTV channel name.
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

/** Initialize. */
require_once( 'lctv_badges_init.php' );

/** Load the API. */
$lctv_api = new LCTVAPI( array(
	'client_id'     => LCTV_CLIENT_ID,
	'client_secret' => LCTV_CLIENT_SECRET,
	'user'          => CHANNEL,
) );

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	exit();
}

/** Get . */
$api_request = $lctv_api->api_request( 'v1/user/followers/' );

/** Bail on error. */
if ( $api_request === false ) {
	exit();
}

/** API returned an error. */
if ( isset( $api_request->result->detail ) ) {
	$follower_count = 0;
} else {
	$follower_count = count( $api_request->result );
}

/** Output svg image. */
header( "Content-type:image/svg+xml" );
echo get_badge_svg( 'lctv followers', ' ' . $follower_count . ' ', '#4c1' );