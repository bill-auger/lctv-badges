<?php
/**
 * Livecoding.tv Badges.
 *
 * Searches https://www.livecoding.tv/livestreams/ for
 * channel name and displays a streaming now or offline
 * badge.
 *
 * @param string channel - (required) LCTV channel name
 * @param string style - (optional) badge style - one of:
 *                       * online-status-v2
 *
 * @author  Trevor Anderson <andtrev@gmail.com>
 * @license GPLv3
 * @package LCTVBadges
 * @version 0.0.2
 */


define( "CHANNEL", strtolower( htmlspecialchars( $_GET['channel'] ) ) );
define( "BADGE_STYLE", strtolower( htmlspecialchars( $_GET['style'] ) ) );
define( "V2_IMAGE_STYLE", 'online-status-v2' );
define( "V2_ONLINE_SVG", 'lctv-online.svg' );
define( "V2_OFFLINE_SVG", 'lctv-offline.svg' );


/** Bail if curl is not installed. */
if (!function_exists('curl_version')) {
	die( 'Curl is not installed.' );
}

/** Bail if no channel name. */
if ( empty( constant( "CHANNEL" ) ) ) {
	die( 'No channel name specified.' );
}


/**
 * Return a badge in svg format.
 *
 * @since 0.0.1
 *
 * @param string $left_text  Text to display on the left side of the button.
 * @param string $right_text Text to display on the right side of the button.
 * @param string $color      Hexidecimal (or other HTML acceptable color) for
 *                           right side of the button.
 *
 * @return string An svg image.
 */
function get_badge_svg_v1( $left_text = '', $right_text = '', $color = '#4c1' ) {

	$left_text = strtolower( $left_text );
	$right_text = strtolower( $right_text );

	$left_text_width = get_text_width( $left_text );
	$right_text_width = get_text_width( $right_text );
	$width = $left_text_width + $right_text_width + 22;
	$right_color_start = $left_text_width + 11;
	$right_color_width = $width - $left_text_width - 11;
	$left_text_start = ( $left_text_width / 2 ) + 6;
	$right_text_start = $width - ( $right_text_width / 2 ) - 6;

  return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="20">' .
           '<linearGradient id="a" x2="0" y2="100%">' .
             '<stop offset="0" stop-color="#bbb" stop-opacity=".1"/>' .
             '<stop offset="1" stop-opacity=".1"/>' .
           '</linearGradient>' .
           '<rect rx="3" width="' . $width . '" height="20" fill="#555"/>' .
           '<rect rx="3" width="' . $right_color_width . '" height="20"' .
                  ' x="' . $right_color_start . '" fill="' . $color . '"/>' .
           '<path fill="' . $color . '" d="M' . $right_color_start . ' 0h4v20h-4z"/>' .
           '<rect rx="3" width="' . $width . '" height="20" fill="url(#a)"/>' .
           '<g fill="#fff" text-anchor="middle" font-family="sans-serif" font-size="12">' .
             '<text x="' . $left_text_start . '" y="15" fill="#010101" fill-opacity=".3">' .
                $left_text .
             '</text>' .
             '<text x="' . $left_text_start . '" y="14">' .
               $left_text .
             '</text>' .
             '<text x="' . $right_text_start . '" y="15" fill="#010101" fill-opacity=".3">' .
               $right_text .
             '</text>' .
             '<text x="' . $right_text_start . '" y="14">' .
               $right_text .
             '</text>' .
           '</g>' .
         '</svg>';

}

/**
 * Return a best guess for text width in pixels.
 *
 * @since 0.0.1
 *
 * @param string $text Text.
 *
 * @return integer Guesstimate of text width in pixels.
 */
function get_text_width( $text ) {

	$skinny_chars = array( 'f', 'i', 'j', 'l', 't', '.', ',', ';' );

	$skinny_count = 0;
	foreach( $skinny_chars as $skinny_char ) {
		$skinny_count += substr_count( $text, $skinny_char );
	}

	$fat_count = strlen( $text ) - $skinny_count;

	return ( $fat_count * 7 ) + ( $skinny_count * 3 );

}

/**
 * Retrieve channel online status from livecoding.tv or from cache 'livestreams.cached'.
 * Use cache for 120 seconds from retrieval time.
 *
 * @since 0.0.2
 *
 * @param string $channel name of channel to query.
 *
 * @return boolean is online or is offline.
 */

function get_is_online( $channel ) {

	if ( file_exists( 'livestreams.cached' ) ) {
		$cached_time = filemtime( 'livestreams.cached' );
	} else {
		$cached_time = false;
	}
	if ( $cached_time === false || ( time() - $cached_time ) > 120 ) {

		$ch = curl_init();
		curl_setopt_array( $ch, array(
			CURLOPT_URL            => "https://www.livecoding.tv/livestreams/",
			CURLOPT_RETURNTRANSFER => true,
		) );
		$livestreams_html = curl_exec( $ch );
		curl_close( $ch );

		$fp = @fopen( 'livestreams.cached', 'w' );
		@fwrite( $fp, $livestreams_html );
		@fclose( $fp );

	} else {

		$fp = @fopen( 'livestreams.cached', 'r' );
		$livestreams_html = @fread( $fp, filesize( 'livestreams.cached' ) );
		@fclose( $fp );

	}

	/** Search for channel name. */
	$needle = '/video/livestream/' . $channel . '/thumbnail' ;
	$is_online = strpos( $livestreams_html, $needle ) !== false;

	return $is_online ;
}


/** Fetch online status. */
$is_online = (get_is_online(CHANNEL) !== false);

/** Output svg image. */
header( "Content-type:image/svg+xml" );
if ( BADGE_STYLE == V2_IMAGE_STYLE ) {
	echo file_get_contents( ( $is_online ) ? V2_ONLINE_SVG : V2_OFFLINE_SVG );
} else if ( $is_online ) {
	echo get_badge_svg_v1( 'livecoding.tv', 'streaming now', '#4c1' );
} else {
	echo get_badge_svg_v1( 'livecoding.tv', 'offline', '#e05d44' );
}
