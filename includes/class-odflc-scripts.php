<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode Class
 *
 * Class for inclide scripts
 *
 * @package OneDigiFlow - Lead Caputure
 * @since 1.0.0
 */
class ODFLC_Scripts{
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array($this, 'load_admin_scripts') );
		add_action( 'wp_enqueue_scripts', array($this, 'load_frontend_scripts') );
	}

	/**
	 * Load admin scripts
	 */
	public function load_admin_scripts( $hook_suffix ) {

		$allowedHooks = array( 'post.php' );

		if( ! in_array($hook_suffix, $allowedHooks) ) return;

		wp_register_script( 'odflc-admin-script', ODFLC_PLUGIN_URL . 'js/odflc-admin-script.js', array('jquery'), ODFLC_PLUGIN_VERSION, true );

		// Localize script
		wp_localize_script( 'odflc-admin-script', 'ODFLC', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		) );

		wp_enqueue_script( 'odflc-admin-script' );
	}

	/**
	 * Load plugin style & scripts
	 */
	public function load_frontend_scripts() {

		wp_register_style( 'intlTelInput-style', ODFLC_PLUGIN_URL . 'css/intlTelInput.min.css', array(), ODFLC_PLUGIN_VERSION );

		wp_register_script( 'intlTelInput-script', ODFLC_PLUGIN_URL . 'js/intlTelInput.min.js', array('jquery'), ODFLC_PLUGIN_VERSION, true );

		wp_register_script( 'odflc-script', ODFLC_PLUGIN_URL . 'js/odflc-script.js', array('jquery'), ODFLC_PLUGIN_VERSION, true );

		// Localize array
		wp_localize_script( 'odflc-script', 'ODFLC', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		) );
	}
}
return new ODFLC_Scripts();