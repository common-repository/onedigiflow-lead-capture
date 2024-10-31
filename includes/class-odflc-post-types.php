<?php 
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Post Types Class
 *
 * Class for post types of plugin
 *
 * @package OneDigiFlow - Lead Caputure
 * @since 1.0.0
 */
class ODFLC_Post_Types{
	public function __construct() {
		add_action( 'init', array($this, 'register_theme_post_types') );
	}

	/**
	 * Manage Custom Post Types
	 */
	public function register_theme_post_types() {
		/** Theme Leads */
		register_post_type( 'odflc-leads',
			apply_filters( 'odflc_leads_post_type_args',
				array(
					'labels'	=> array(
						'name'					=> esc_html__( 'Leads', 'odflc' ),
						'singular_name'			=> esc_html__( 'Lead', 'odflc' ),
						'menu_name'				=> esc_html__( 'Lead Capture', 'odflc' ),
						'all_items'				=> esc_html__( 'All Leads', 'odflc' ),
						'add_new'				=> esc_html__( 'Add Lead', 'odflc' ),
						'add_new_item'			=> esc_html__( 'Add New Lead', 'odflc' ),
						'edit'					=> esc_html__( 'Edit Lead', 'odflc' ),
						'edit_item'				=> esc_html__( 'Lead Info', 'odflc' ),
						'new_item'				=> esc_html__( 'New Lead', 'odflc' ),
						'view'					=> esc_html__( 'View Lead', 'odflc' ),
						'view_item'				=> esc_html__( 'View Lead', 'odflc' ),
						'search_items'			=> esc_html__( 'Search Lead', 'odflc' ),
						'not_found'				=> esc_html__( 'No Lead found', 'odflc' ),
						'not_found_in_trash'	=> esc_html__( 'No Lead found in trash', 'odflc' ),
						'filter_items_list'		=> esc_html__( 'Filter Lead', 'odflc' ),
						'items_list_navigation'	=> esc_html__( 'Lead navigation', 'odflc' ),
						'items_list'			=> esc_html__( 'Lead list', 'odflc' ),
					),
					'description'			=> esc_html__('This is where you can add new leads item.', 'odflc'),
					'public'				=> false,
					'show_ui'				=> true,
					'capability_type'		=> 'post',
					'publicly_queryable'	=> false,
					'exclude_from_search'	=> true,
					'hierarchical'			=> false,
					'query_var'				=> false,
					'rewrite' 				=> false,
					'supports'				=> array( 'title', 'editor' ),
					'has_archive'			=> false,
					'show_in_nav_menus'		=> false,
					'menu_position'			=> 25,
					'menu_icon'				=> 'dashicons-clipboard',
				)
			)
		);
	}
}
return new ODFLC_Post_Types();