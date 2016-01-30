<?php
/**
 * Livecoding.tv Streaming Status Badges.
 *
 * Check if the channel has a scheduled streaming date and return an appropriate svg image.
 *
 * @param string channel (required) LCTV channel name.
 * @param string link    (optional) true/false to automatically link to channel.
 *
 * @package LCTVBadges\Badges
 * @since 0.0.7
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
	'user'          => LCTV_MASTER_USER,
) );

/** Bail if API isn't authorized. */
if ( ! $lctv_api->is_authorized() ) {
	header( "Content-type:image/svg+xml" );
	echo get_badge_svg( 'livecoding.tv', 'error', '#e05d44' );
	exit();
}

/** Get live streaming info for a channel. */
$api_request = $lctv_api->api_request( 'v1/scheduledbroadcast/?limit=500' );

/** Bail on error. */
if ( $api_request === false || isset( $api_request->result->detail ) ) {
	header( "Content-type:image/svg+xml" );
	echo get_badge_svg( 'livecoding.tv', 'error', '#e05d44' );
	exit();
}

/** Get scheduled streams and search for channel name. */
$next_stream = '';
$api_request->result->results = array_reverse( $api_request->result->results );
foreach ( $api_request->result->results as $scheduled ) {
	if ( strpos( $scheduled->livestream, $channel ) !== false ) {
		$next_stream = strtotime( $scheduled->start_time_original_timezone );
		break;
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
if ( $next_stream ) {
	echo get_badge_svg( 'next stream', date( 'M j @ g:i a', $next_stream ), '#4c1', $link );
} else {
	echo get_badge_svg( 'next stream', 'no stream scheduled', '#e05d44', $link );
}