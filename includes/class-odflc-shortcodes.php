<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode Class
 *
 * Class for shortcodes of plugin
 *
 * @package OneDigiFlow - Lead Caputure
 * @since 1.0.0
 */
class ODFLC_Shortcodes{

	public $scriptFlag;

	public function __construct() {
		add_shortcode( 'odflc_form', array($this, 'odflc_form_shortcode') );
		$this->scriptFlag = false;
	}

	/**
	 * Form Shortcode
	 */
	public function odflc_form_shortcode( $atts, $content ){
		extract( shortcode_atts( array(
			'classification' => '',
			'backlog' => '',
		), $atts ) );

		$this->scriptFlag = true;

		wp_enqueue_style( 'intlTelInput-style' );
		wp_enqueue_script( 'intlTelInput-script' );
		wp_enqueue_script( 'odflc-script' );

		ob_start();
		include( ODFLC_PLUGIN_DIR . '/includes/templates/lc-form.php' );
		return ob_get_clean();
	}
}
return new ODFLC_Shortcodes();