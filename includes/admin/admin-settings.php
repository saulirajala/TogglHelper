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

		add_action( 'admin_menu', array( $this, 'toggl_helper_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'toggl_helper_settings_init' ) );
	}

	/**
	 * Get the singleton instance
	 * @return type
	 */
	function this() {
		return self::$_this;
	}

	/**
	 * Add sub menu page to the Settings menu
	 */
	function toggl_helper_add_admin_menu() {
		add_options_page( __( 'Toggl Helper', TOGGL_HELPER_TEXTDOMAIN  ), __( 'Toggl Helper', TOGGL_HELPER_TEXTDOMAIN  ), 'manage_options', 'toggl_helper', array( $this, 'toggl_helper_options_page' ) );
	}

	/**
	 * Register settings and add section and fields
	 */
	function toggl_helper_settings_init() {

		register_setting( 'togglHelperPlugin', 'toggl_helper_settings' );

		add_settings_section(
		'toggl_helper_section', '', array( $this, 'toggl_helper_settings_section_callback' ), 'togglHelperPlugin'
		);

		add_settings_field(
		'toggl_helper_field_toggl_api_key', __( 'Toggl API token', TOGGL_HELPER_TEXTDOMAIN ), array( $this, 'toggl_helper_field_toggl_api_key_render' ), 'togglHelperPlugin', 'toggl_helper_section'
		);

		add_settings_field(
		'toggl_helper_field_project_id', __( 'Project ID (What is the project where you want to create time entry?)', TOGGL_HELPER_TEXTDOMAIN ), array( $this, 'toggl_helper_field_project_id_render' ), 'togglHelperPlugin', 'toggl_helper_section'
		);
		
		add_settings_field(
		'toggl_helper_field_time_entry_description', __( 'Description of Time entry, that plugin creates', TOGGL_HELPER_TEXTDOMAIN ), array( $this, 'toggl_helper_field_time_entry_description_render' ), 'togglHelperPlugin', 'toggl_helper_section'
		);
	}

	/**
	 * Prints input-field for Toggl API token
	 */
	function toggl_helper_field_toggl_api_key_render() {
		if ( !get_option( 'toggl_helper_settings' ) ) {
			$options = array();
		} else {
			$options = get_option( 'toggl_helper_settings' );
		}

		if ( !array_key_exists( "toggl_helper_field_toggl_api_key", $options ) ) {
			$options[ 'toggl_helper_field_toggl_api_key' ] = 0;
		} elseif ( !is_numeric( $options[ 'toggl_helper_field_toggl_api_key' ] ) ) {
			$options[ 'toggl_helper_field_max_articles' ] = 0;
		}
		?>
		<input type='text' name='toggl_helper_settings[toggl_helper_field_toggl_api_key]' value='<?php echo esc_html( $options[ 'toggl_helper_field_toggl_api_key' ] ); ?>'>
		<?php
	}

	/**
	 * Prints input-field for project id
	 * project_id = the id of the project, where plugins create time entry if needed
	 * 
	 * For example:
	 * If your time entries duration in Toggl are 6,5h and you have worked 8h in day, 
	 * plugins creates following time entry to Toggl:
	 * Duration = 1,5h
	 * Description = the description that user has given in settings-page
	 * Project ID  = project_id
	 */
	function toggl_helper_field_project_id_render() {
		if ( !get_option( 'toggl_helper_settings' ) ) {
			$options = array();
		} else {
			$options = get_option( 'toggl_helper_settings' );
		}
		
		if ( !array_key_exists( "toggl_helper_field_project_id", $options ) ) {
			$options[ 'toggl_helper_field_project_id' ] = 0;
		} elseif ( !is_numeric( $options[ 'toggl_helper_field_project_id' ] ) ) {
			$options[ 'toggl_helper_field_project_id' ] = 0;
		}
		?>
		<input type='number' name='toggl_helper_settings[toggl_helper_field_project_id]' value='<?php echo esc_html( $options[ 'toggl_helper_field_project_id' ] ); ?>'>
		<span><?php _e( 'Leave to zero, if you don\'t want to create time entry to Toggl', TOGGL_HELPER_TEXTDOMAIN ) ?></span>
			<?php
	}
	
	/**
	 * Description of new time entry
	 */
	function toggl_helper_field_time_entry_description_render() {
		if ( !get_option( 'toggl_helper_settings' ) ) {
			$options = array();
		} else {
			$options = get_option( 'toggl_helper_settings' );
		}
		
		if ( !array_key_exists( "toggl_helper_field_time_entry_description", $options ) ) {
			$options[ 'toggl_helper_field_time_entry_description' ] = 0;
		}
		?>
		<input type='text' name='toggl_helper_settings[toggl_helper_field_time_entry_description]' value='<?php echo esc_html( $options[ 'toggl_helper_field_time_entry_description' ] ); ?>'>
			<?php
	}

	function toggl_helper_settings_section_callback() {

		echo __( 'Here you can change settings for Toggl Helper -plugin', TOGGL_HELPER_TEXTDOMAIN );
	}

	function toggl_helper_options_page() {
		?>
		<form action='options.php' method='post'>

			<h2><?php echo __( 'Toggl Helper', TOGGL_HELPER_TEXTDOMAIN ); ?></h2>

		<?php
		settings_fields( 'togglHelperPlugin' );
		do_settings_sections( 'togglHelperPlugin' );
		submit_button();
		?>

		</form>
		<?php
	}
}
