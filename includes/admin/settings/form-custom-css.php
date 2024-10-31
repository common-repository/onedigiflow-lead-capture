<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Custom CSS Page
 * The code for the plugins custom css for form
 */

// enqueue code editor
wp_enqueue_code_editor( array('type' => 'text/html') );

$css = get_option( 'odflc_form_custom_css' ); ?>

<div class="wrap odflc-form-custom-css">
	<h1><?php esc_html_e( 'OneDigiFlow Lead Capture - From Custom CSS', 'odflc' ); ?></h1>

	<?php
	// Print error messages
	settings_errors(); ?>

	<form action="" method="post">

		<?php wp_nonce_field( 'odflc_save_custom_css', 'odflc_nounce_field' ); ?>

		<!-- beginning of the general settings meta box -->
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable"><div class="postbox">
				<div class="handlediv" title="<?php esc_html_e('Click to toggle', 'odflc'); ?>"><br /></div>
					
				<!-- general settings box title -->
				<h3 class="handle">
					<?php esc_html_e( 'Form Custom CSS', 'odflc' ); ?>
				</h3>

				<div class="inside">

					<table class="form-table">
						<!-- <tr><th class="no-padding"><label for="odflc-custom-css">
							<?php esc_html_e( 'Custom CSS', 'ecsnippets' ); ?>
						</label></th></tr> -->

						<tr><td class="no-padding">
							<textarea name="odflc_custom_css" id="odflc-custom-css" class="odflc-css-editor"><?php echo wp_unslash( $css ); ?></textarea>
						</td></tr>
					</table>

					<div class="odflc-settings-btns">
						<?php
						if( empty( $GLOBALS['hide_save_button'] ) ) :
							submit_button( esc_html__( 'Save Changes', 'odflc' ), 'primary', 'odflc-settings-save-btn' );
						endif; ?>
					</div>
				</div><!-- /.inside -->
			</div></div>
		</div><!-- /.metabox-holder -->

	</form>
</div>

<style type="text/css">
	.odflc-form-custom-css th.no-padding,
	.odflc-form-custom-css td.no-padding{ padding: 0 !important; }
	.odflc-form-custom-css .CodeMirror{ border: 1px solid #e3e3e3; }
</style>

<script type="text/javascript">
	jQuery( document ).ready( function($) {

	// Initilise html code editor
	if( $('.odflc-css-editor').length ) {
		var editorCssSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
		editorCssSettings.codemirror = _.extend(
			{},
			editorCssSettings.codemirror,
			{
				indentUnit: 2,
				tabSize: 2,
				mode: 'css',
			}
			);

		$('.odflc-css-editor').each( function() {
			var editor = wp.codeEditor.initialize( $(this), editorCssSettings );
		} );
	}
	
} );
</script>