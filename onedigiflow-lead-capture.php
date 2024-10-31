<?php
/**
 * Plugin Name: OneDigiFlow - Lead Capture
 * Plugin URI: https://wordpress.org/plugins/onedigiflow-lead-capture/
 * Description: OneDigiFlow - Lead Capture plugin allow to put a form on any pages using a shortcode <code>[odflc_form]</code> and capture leads in OneDigiFlow CRM.
 * Version: 0.0.1
 * Author: OneDigiStore
 * Author URI: https://onedigistore.com
 * Text Domain: odflc
 * Domain Path: languages
 * 
 * @package OneDigiFlow - Lead Caputure
 * @category Core
 * @author OneDigiStore
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 */
if( !defined( 'ODFLC_PLUGIN_VERSION' ) ) {
	define( 'ODFLC_PLUGIN_VERSION', '0.0.1' ); //Plugin version number
}
if( !defined( 'ODFLC_PLUGIN_DIR' ) ) {
	define( 'ODFLC_PLUGIN_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'ODFLC_PLUGIN_URL' ) ) {
	define( 'ODFLC_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'ODFLC_ADMIN_DIR' ) ) {
	define( 'ODFLC_ADMIN_DIR', ODFLC_PLUGIN_DIR . '/includes/admin' ); // plugin admin dir
}

/**
 * Load Text Domain
 */
function odflc_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'odflc' );

	load_textdomain( 'odflc', WP_LANG_DIR . '/onedigiflow-lead-capture/odflc-' . $locale . '.mo' );
	load_plugin_textdomain( 'odflc', false, EPVC_PLUGIN_DIR . '/languages' );
}
add_action( 'load_plugins', 'odflc_load_plugin_textdomain' );

/**
 * Activation Hook
 * Register plugin activation hook.
 */
register_activation_hook( __FILE__, 'odflc_plugin_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 */
function odflc_plugin_install() {
	//activation code here
}

/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 */
register_deactivation_hook( __FILE__, 'odflc_plugin_uninstall');

/**
 * Plugin Setup (On Deactivation)
 * Delete plugin options.
 */
function odflc_plugin_uninstall() {
	//deactivation code here
}

/**
 * Include the require files
 */
global $odflc_shortcodes;

// Includes scripts class file
require_once( ODFLC_PLUGIN_DIR . '/includes/class-odflc-scripts.php' );

// Includes post types class file
require_once( ODFLC_PLUGIN_DIR . '/includes/class-odflc-post-types.php' );

// Includes API class to manage OneDigiFlow APIs
require_once( ODFLC_PLUGIN_DIR . '/includes/class-odflc-api.php' );

// Includes shortcodes class file
$odflc_shortcodes = require_once( ODFLC_PLUGIN_DIR . '/includes/class-odflc-shortcodes.php' );

// Includes public class file
require_once( ODFLC_PLUGIN_DIR . '/includes/class-odflc-public.php' );

// Includes admin side files
if( is_admin() ) {
	require_once( ODFLC_ADMIN_DIR . '/class-odflc-admin.php' );
}