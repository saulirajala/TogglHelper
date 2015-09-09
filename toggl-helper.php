<?php

/*
  Plugin Name: Toggl Helper
  Plugin URI: http://irajala.com
  Description: Plugin for managing time entries of Toggl.
  Version: 1.0
  Author: Sauli Rajala
  Author URI: http://irajala.com http://valu.fi
  Requires at least:
  Tested up to:
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Toggl_Helper class.
 */
class Toggl_Helper {

	// Singleton variable
	private static $_this;
	public $textdomain	 = 'toggl-helper';
	public $hook_prefix	 = 'toggl_helper_';

	/**
	 * __construct function.
	 */
	function __construct() {
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( "%s is a singleton class and your cannot create a second instance.", $this->textdomain ), get_class( $this ) ) );
		}

		self::$_this = $this;

		// Define constants
		define( 'TOGGL_HELPER_VERSION', '1.0' );
		define( 'TOGGL_HELPER_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'TOGGL_HELPER_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'TOGGL_HELPER_TEXTDOMAIN', $this->textdomain );
		define( 'TOGGL_HELPER_HOOK_PREFIX', $this->hook_prefix );

		require TOGGL_HELPER_PLUGIN_DIR. '/vendor/autoload.php';

		// Additional files
		include( 'includes/custom-post-types.php' );
		include( 'includes/admin/admin-settings.php' );

		$this->toggl_helper_admin_settings	 = new Toggl_Helper_Admin_Settings();
		$this->toggl_helper_acf = new Toggl_Helper_Day_Entries();
		
		$toggl_api_version = 'v8';
		
		
		if ( get_option( 'toggl_helper_settings' ) === '' ) {
			$toggl_api_key = 0;
		}else {
			$toggl_api_key = get_option( 'toggl_helper_settings' )[ 'toggl_helper_field_toggl_api_key' ];
		}
		
		if ( get_option( 'toggl_helper_settings' ) === '' ) {
			$other_works_id = 0;
		}else {
			$other_works_id = get_option( 'toggl_helper_settings' )[ 'toggl_helper_field_project_id' ];
		}
		
		if ( get_option( 'toggl_helper_settings' ) === '' ) {
			$description = 0;
		}elseif ( !array_key_exists( "toggl_helper_field_time_entry_description", get_option( 'toggl_helper_settings' ) ) ) {
			$description = 0;
		}else {
			$description = get_option( 'toggl_helper_settings' )[ 'toggl_helper_field_time_entry_description' ];
		}
		
		if ( get_option( 'toggl_helper_settings' ) === '' ) {
			$workhours = 7.5;
		}elseif ( !array_key_exists( "toggl_helper_field_workhours_in_day", get_option( 'toggl_helper_settings' ) ) ) {
			$workhours = 7.5;
		}else {
			$workhours = get_option( 'toggl_helper_settings' )[ 'toggl_helper_field_workhours_in_day' ];
		}
		
		
		$this->toggl_api_key = $toggl_api_key;
		$this->other_works_id = (int)$other_works_id;
		$this->description = $description;
		$this->workhours = $workhours;
		
		include( 'includes/template-functions.php' );

		// Actions
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
	}

	/**
	 * Get the singleton instance
	 * @return type
	 */
	function this() {
		return self::$_this;
	}

	/**
	 * Localisation
	 *
	 * @access private
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'toggl-helper', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * frontend_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_scripts() {
		
	}

}

$GLOBALS[ 'Toggl_Helper' ] = new Toggl_Helper();
