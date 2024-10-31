<?php 
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Manage admin side functionality
 *
 * @package OneDigiFlow - Lead Caputure
 * @since 1.0.0
 */
class ODFLC_Admin{
	public function __construct() {
		add_action( 'add_meta_boxes', array($this, 'register_metaboxes') );
		add_action( 'save_post', array($this, 'save_metaboxes') );

		// Register settings in init
		add_action ( 'admin_init', array( $this, 'register_plugin_settings') );

		// Add admin menu pages
		add_action ( 'admin_menu', array( $this, 'manage_admin_menus' ) );

		// Custom columns for lead post type
		add_filter( 'manage_odflc-leads_posts_columns', array($this, 'manage_leads_columns') );
		add_action( 'manage_odflc-leads_posts_custom_column' , array($this, 'manage_leads_column_value'), 10, 2 );

		// ajax request to create contact request
		add_action( 'wp_ajax_odflc_create_contact', array($this, 'odflc_create_contact_ajax') );
		add_action( 'wp_ajax_nopriv_odflc_create_contact', array($this, 'odflc_create_contact_ajax') );

		// ajax request to create deal request
		add_action( 'wp_ajax_odflc_create_deal', array($this, 'odflc_create_deal_ajax') );
		add_action( 'wp_ajax_nopriv_odflc_create_deal', array($this, 'odflc_create_deal_ajax') );
	}

	/**
	 * Register Metaboxes
	 * for custom post type
	 */
	public function register_metaboxes() {
		// Lead info metabox
		add_meta_box( 'odflc-lead-info',
			esc_html__( 'Lead Info', 'odflc' ),
			array($this, 'lead_info_metabox'),
			'odflc-leads', 'normal', 'high'
		);
	}

	/**
	 * Lead info metaboxes
	 */
	public function lead_info_metabox() {
		require_once( ODFLC_ADMIN_DIR . '/metaboxes/lead-info-metaboxes.php' );
	}

	/**
	 * Save post meta
	 */
	public function save_metaboxes( $post_id ) {
		global $post_type;

		// Get post type object
		$post_type_object = get_post_type_object( $post_type );

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( isset($post_type_object->cap->edit_post) && !current_user_can($post_type_object->cap->edit_post, $post_id) ) )  // Check permission
		{
			return $post_id;
		};

		// If lead capture post type
		if( $post_type == 'odflc-lead' ) {
			if( isset($_POST['_odflc_lead_email']) ) {
				update_post_meta( $post_id, '_odflc_lead_email', sanitize_email($_POST['_odflc_lead_email']) );
			}
			if( isset($_POST['_odflc_lead_phone']) ) {
				update_post_meta( $post_id, '_odflc_lead_phone', sanitize_text_field($_POST['_odflc_lead_phone']) );
			}
			if( isset($_POST['_odflc_lead_sku']) ) {
				update_post_meta( $post_id, '_odflc_lead_sku', sanitize_text_field($_POST['_odflc_lead_sku']) );
			}
		}
	}

	/**
	 * Register Plugin Settings
	 */
	public function register_plugin_settings() {
		register_setting( 'odflc_plugin_options', 'odflc_options', array( $this, 'odflc_validate_options' ) );
	}

	/**
	 * Validate plugin settings
	 */
	public function odflc_validate_options( $input ) {

		$input['odf_api_key']	= !empty($input['odf_api_key']) ? $input['odf_api_key'] : '';
		$input['odf_products']	= !empty($input['odf_products']) ? array_filter( $input['odf_products'] ) : array();

		$settings = get_option( 'odflc_options' );
		if( (isset($settings['odf_api_key']) && $settings['odf_api_key'] != $input['odf_api_key'])
			|| !empty($_POST['odflc-product-data']) ){

			// Refresh the proucts again
			$odfApi = new OneDigiFlow_API();
			$products = $odfApi->odf_get_products();
		}

		add_settings_error( 'odflc-settings-notices', '', __( 'Your settings have been saved successfully!', 'odflc' ), 'updated' );
		return $input;
	}

	/**
	 * Add settings menu page
	 */
	public function manage_admin_menus() {
		add_submenu_page( 'edit.php?post_type=odflc-leads',  esc_html__('Plugin Settings', 'odflc'), esc_html__('Settings', 'odflc'), 'manage_options', 'odflc-settings', array( $this, 'plugin_settings_page' ) );

	}

	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		require_once( ODFLC_ADMIN_DIR . '/settings/plugin-settings.php' );
	}

	/**
	 * Manage Leads post type columns
	 */
	public function manage_leads_columns( $columns ) {
		$data = $columns['date'];

		unset( $columns['date'] );

		$columns['product']	= esc_html__( 'Product', 'odflc' );
		$columns['email']	= esc_html__( 'Email', 'odflc' );
		$columns['phone']	= esc_html__( 'Phone', 'odflc' );
		$columns['date']	= esc_html__( 'Date', 'odflc' );

		return $columns;
	}

	/**
	 * Leads post type custom column value
	 */
	public function manage_leads_column_value( $column, $post_id ) {
		switch ( $column ) {
			case 'product':
				$title = get_post_meta( $post_id, '_odflc_lead_product_title', true );
				if( !empty($title) ) {
					print( $title );
				} else {
					echo get_post_meta( $post_id, '_odflc_lead_sku', true );
				}
			break;
			case 'email':
				echo get_post_meta( $post_id, '_odflc_lead_email', true );
			break;
			case 'phone':
				echo get_post_meta( $post_id, '_odflc_lead_phone', true );
			break;
		}
	}

	/**
	 * Create contact by ajax
	 */
	public function odflc_create_contact_ajax() {

		$postid = isset( $_POST['postid'] ) ? sanitize_text_field($_POST['postid']) : '';

		$res = array(
			'status' => '0',
			'msg' => esc_html__( 'Somethig gone wrong, please try again.', 'odflc' )
		);

		if( empty($postid) ) {
			echo json_encode( $res );
			exit;
		}

		$data['name'] 			= get_the_title( $postid );
		$data['email']			= get_post_meta( $postid, '_odflc_lead_email', true );
		$data['phone_code']		= get_post_meta( $postid, '_odflc_lead_phone_code', true );
		$data['phone']			= get_post_meta( $postid, '_odflc_lead_phone', true );
		$data['skus']			= get_post_meta( $postid, '_odflc_lead_sku', true );
		$data['classification']	= get_post_meta( $postid, '_odflc_classification', true );

		// Add contact data to ODF
		$odfApi = new OneDigiFlow_API();
		$contact = $odfApi->odf_add_contact( $data );
		if( $contact && !empty($contact['id']) ){
			update_post_meta( $postid, '_odflc_is_odf_contact_id', $contact['id'] );

			$res = array(
				'status' => '1',
				'msg' => esc_html__( 'Somethig gone wrong, please try again.', 'odflc' ),
				'html' => esc_html__( 'Yes', 'odflc' ),
			);
		}

		echo json_encode( $res );
		exit;
	}

	/**
	 * Create deal ajax request
	 */
	public function odflc_create_deal_ajax() {

		$postid = isset( $_POST['postid'] ) ? sanitize_text_field($_POST['postid']) : '';

		$res = array(
			'status' => '0',
			'msg' => esc_html__( 'Somethig gone wrong, please try again.', 'odflc' )
		);

		if( empty($postid) ) {
			echo json_encode( $res );
			exit;
		}

		$data['summary']		= get_post_field( 'post_content', $postid );
		$data['contact_id']		= get_post_meta( $postid, '_odflc_is_odf_contact_id', true );
		$data['skus']			= get_post_meta( $postid, '_odflc_lead_sku', true );
		$data['backlog']		= get_post_meta( $postid, '_odflc_backlog', true );

		// Add deal data to ODF
		$odfApi = new OneDigiFlow_API();
		$deal = $odfApi->odf_add_deal( $data );
		if( $deal ){
			update_post_meta( $postid, '_odflc_is_odf_deal_created', 'yes' );

			$res = array(
				'status' => '1',
				'msg' => esc_html__( 'Somethig gone wrong, please try again.', 'odflc' ),
				'html' => esc_html__( 'Yes', 'odflc' ),
			);
		}

		echo json_encode( $res );
		exit;
	}
}
return new ODFLC_Admin();