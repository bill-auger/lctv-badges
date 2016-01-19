<?php
/**
 * Initialize Livecoding.tv Badges.
 * 
 * @package LCTVBadges\Initialize
 * @since 0.0.3
 */

/** Check if script is accessed directly. */
if ( basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
  exit();
}

/** Set client id. */
define( 'LCTV_CLIENT_ID', getenv( 'LCTV_CLIENT_ID' ) );
/** Set client secret. */
define( 'LCTV_CLIENT_SECRET', getenv( 'LCTV_CLIENT_SECRET' ) );
/** Set API redirect url. */
define( 'LCTV_REDIRECT_URL', 'http://example.com/authorize.php' );
/** Set master account name to access public api information. */
define( 'LCTV_MASTER_USER', 'username' );
/**
 * Set path for flat file data storage.
 * Default set in LCTVAPI.php: __DIR__
 */
//define( 'LCTVAPI_DATA_PATH', __DIR__ . '/data/' );
/**
 * Set cache expire time.
 * Default set in LCTVAPI.php: 300
 */
//define( 'LCTVAPI_CACHE_EXPIRES_IN', 300 );

/** LCTV API */
require_once( 'LCTVAPI.php' );
/** Badge svg creator. */
require_once( 'lctv_badges_svg.php' );