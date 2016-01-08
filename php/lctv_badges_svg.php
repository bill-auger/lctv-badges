<?php
/**
 * Create svg badges.
 * 
 * @package LCTVBadges\BadgesSVG
 * @since 0.0.3
 */

/** Check if script is accessed directly. */
if ( basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
  exit();
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
function get_badge_svg( $left_text = '', $right_text = '', $color = '#4c1' ) {

	$left_text = strtolower( $left_text );
	$right_text = strtolower( $right_text );

	$left_text_width = get_text_width( $left_text );
	$right_text_width = get_text_width( $right_text );
	$width = $left_text_width + $right_text_width + 22;
	$right_color_start = $left_text_width + 11;
	$right_color_width = $width - $left_text_width - 11;
	$left_text_start = ( $left_text_width / 2 ) + 6;
	$right_text_start = $width - ( $right_text_width / 2 ) - 6;

	return
		'<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="20">' .
			'<linearGradient id="a" x2="0" y2="100%">' .
				'<stop offset="0" stop-color="#bbb" stop-opacity=".1"/>' .
				'<stop offset="1" stop-opacity=".1"/>' .
			'</linearGradient>' .
			'<rect rx="3" width="' . $width . '" height="20" fill="#555"/>' .
			'<rect rx="3" width="' . $right_color_width . '" height="20" x="' . $right_color_start . '" fill="' . $color . '"/>' .
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

	$skinny_chars = array( 'f', 'i', 'j', 'l', 't', '.', ',', ';', '|', ':', '-', '!', '1' );

	$skinny_count = 0;
	foreach( $skinny_chars as $skinny_char ) {
		$skinny_count += substr_count( $text, $skinny_char );
	}

	$fat_count = strlen( $text ) - $skinny_count;

	return ( $fat_count * 7 ) + ( $skinny_count * 3 );

}