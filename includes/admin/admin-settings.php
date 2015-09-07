<?php
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/**
 * Toggl_Helper_Admin_Settings class.
 */

class Toggl_Helper_Admin_Settings {

	// Singleton variable
	private static $_this;

	/**
	 * __construct function.
	 */
	function __construct() {
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( "%s is a singleton class and your cannot create a second instance.", TOGGL_HELPER_TEXTDOMAIN ), get_class( $this ) ) );
		}
		self::$_this = $this;

		/*If needed, use http://wpsettingsapi.jeroensormani.com/ */
		
	
	}

	/**
	 * Get the singleton instance
	 * @return type
	 */
	function this() {
		return self::$_this;
	}

}
