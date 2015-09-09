<?php

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/**
 * Toggl_Helper_Day_Entries class.
 */

class Toggl_Helper_Day_Entries {

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



		// Actions & Filters
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
		add_action( 'init', array( $this, 'register_acf_meta' ) );
	}

	/**
	 * Register 
	 */
	function register_post_type() {
		// Register Custom Post Type

		$labels	 = array(
			'name'				 => _x( 'Days', 'Post Type General Name', 'toggl_helper' ),
			'singular_name'		 => _x( 'Day', 'Post Type Singular Name', 'toggl_helper' ),
			'menu_name'			 => __( 'Days', 'toggl_helper' ),
			'name_admin_bar'	 => __( 'Days', 'toggl_helper' ),
			'parent_item_colon'	 => __( 'Parent day:', 'toggl_helper' ),
			'all_items'			 => __( 'All Days', 'toggl_helper' ),
			'add_new_item'		 => __( 'Add New Day', 'toggl_helper' ),
			'add_new'			 => __( 'Add New', 'toggl_helper' ),
			'new_item'			 => __( 'New Day', 'toggl_helper' ),
			'edit_item'			 => __( 'Edit Day', 'toggl_helper' ),
			'update_item'		 => __( 'Update Day', 'toggl_helper' ),
			'view_item'			 => __( 'View Day', 'toggl_helper' ),
			'search_items'		 => __( 'Search Day', 'toggl_helper' ),
			'not_found'			 => __( 'Not found', 'toggl_helper' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'toggl_helper' ),
		);
		$args	 = array(
			'label'					 => __( 'Day', 'toggl_helper' ),
			'description'			 => __( 'Days total workhour', 'toggl_helper' ),
			'labels'				 => $labels,
			'supports'				 => array( 'editor' ),
			'taxonomies'			 => array( 'category', 'post_tag' ),
			'hierarchical'			 => false,
			'public'				 => true,
			'show_ui'				 => true,
			'show_in_menu'			 => true,
			'menu_position'			 => 5,
			'menu_icon'				 => 'dashicons-clock',
			'show_in_admin_bar'		 => true,
			'show_in_nav_menus'		 => true,
			'can_export'			 => true,
			'has_archive'			 => true,
			'exclude_from_search'	 => false,
			'publicly_queryable'	 => true,
			'capability_type'		 => 'post',
		);
		register_post_type( 'valu_toggl_day', $args );
	}

	/**
	 *
	 */
	function register_taxonomies() {
		
	}

	/**
	 * Registers meta fields using ACF
	 */
	function register_acf_meta() {

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( apply_filters( TOGGL_HELPER_TEXTDOMAIN . 'acf_args', array(
				'id'		 => 'acf_toggl_helper_details',
				'title'		 => __( 'Hours', TOGGL_HELPER_TEXTDOMAIN ),
				'fields'	 => array(
					array(
						'key'			 => 'field_546613dab92d4',
						'label'			 => __( 'What day it is?', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'day',
						'type'			 => 'date_picker',
						'date_format'	 => 'yy-mm-dd',
						'display_format' => 'yy-mm-dd',
					),
					array(
						'key'			 => 'field_546304dab92d4',
						'label'			 => __( 'When did you start working? In format hh:mm', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'start_time',
						'type'			 => 'text',
						'default_value'	 => '',
						'placeholder'	 => '',
						'prepend'		 => '',
						'append'		 => '',
						'formatting'	 => 'none',
						'maxlength'		 => '5',
					),
					array(
						'key'			 => 'field_546304dab93c8',
						'label'			 => __( 'When did you end working? In format hh:mm', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'end_time',
						'type'			 => 'text',
						'default_value'	 => '',
						'placeholder'	 => '',
						'prepend'		 => '',
						'append'		 => '',
						'formatting'	 => 'none',
						'maxlength'		 => '5',
					),
					array(
						'key'			 => 'field_654304dab93c8',
						'label'			 => __( 'How long (in hours) you were on a break (lunchbreak or other). In format 0.5', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'break_time',
						'type'			 => 'text',
						'default_value'	 => '0.5',
						'placeholder'	 => '',
						'prepend'		 => '',
						'append'		 => '',
						'formatting'	 => 'none',
						'maxlength'		 => '5',
					),
					array(
						'key'			 => 'field_546304ceb92d3',
						'label'			 => __( 'How many hours and minutes did you work?', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'real_hours',
						'type'			 => 'text',
						'default_value'	 => '',
						'placeholder'	 => '',
						'prepend'		 => '',
						'append'		 => '',
						'formatting'	 => 'none',
						'maxlength'		 => '',
					),
					array(
						'key'			 => 'field_546304dab94d3',
						'label'			 => __( '+/- hours', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'total_hours',
						'type'			 => 'text',
						'default_value'	 => '',
						'placeholder'	 => '',
						'prepend'		 => '',
						'append'		 => '',
						'formatting'	 => 'none',
						'maxlength'		 => '',
					),
					array(
						'key'			 => 'field_5ash304dab94d3',
						'label'			 => __( 'Description', TOGGL_HELPER_TEXTDOMAIN ),
						'name'			 => 'description',
						'type'			 => 'textarea',
						'default_value'	 => '',
						'placeholder'	 => '',
						'prepend'		 => '',
						'append'		 => '',
						'formatting'	 => 'none',
						'maxlength'		 => '',
					),
				),
				'location'	 => array(
					array(
						array(
							'param'		 => 'post_type',
							'operator'	 => '==',
							'value'		 => 'valu_toggl_day',
							'order_no'	 => 0,
							'group_no'	 => 0,
						),
					),
				),
				'options'	 => array(
					'position'		 => 'after_title',
					'layout'		 => 'no_box',
					'hide_on_screen' => array(
						0	 => 'excerpt',
						1	 => 'custom_fields',
						2	 => 'discussion',
						3	 => 'comments',
						4	 => 'revisions',
						5	 => 'author',
						6	 => 'format',
						7	 => 'featured_image',
						8	 => 'send-trackbacks',
						9	 => 'the_content',
					),
				),
				'menu_order' => 0,
			) ) );
		}
	}

	/**
	 * Get the singleton instance
	 * @return type
	 */
	function this() {
		return self::$_this;
	}

}
