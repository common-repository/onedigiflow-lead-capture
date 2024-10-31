<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Lead Capture form template
 */

$settings = get_option( 'odflc_options' ); ?>

<div class="odflc-form-wrap">
	<form action="<?php echo get_permalink(); ?>" class="odflc-lead-capture-form" method="post">
		<?php
		wp_nonce_field( 'odflc_form_submit', 'odflc_form_nonce' );

		if( !empty($classification) ) {
			echo '<input type="hidden" name="classification" value="' . esc_attr($classification) . '" />';
		}

		if( !empty($backlog) ) {
			echo '<input type="hidden" name="backlog" value="' . esc_attr($backlog) . '" />';
		} ?>

		<div class="odflc-field-wrap odflc-field-name">
			<input type="text" name="name" class="odflc-required" placeholder="<?php esc_html_e( 'Enter your name', 'odflc' ); ?>" />
		</div>
		<div class="odflc-field-wrap odflc-field-email">
			<input type="email" name="email" class="odflc-required odflc-email" placeholder="<?php esc_html_e( 'Enter your email address', 'odflc' ); ?>" />
		</div>
		<div class="odflc-field-wrap odflc-field-phone">
			<input type="tel" name="phone" class="odflc-required" placeholder="<?php esc_html_e( 'Enter your phone number', 'odflc' ); ?>" />
		</div>
		<div class="odflc-field-wrap odflc-field-summary">
			<textarea name="summary" class="odflc-required" placeholder="<?php esc_html_e( 'Enter your comments', 'odflc' ); ?>"></textarea>
		</div>
		<div class="odflc-field-wrap odflc-field-skus">
			<select name="skus" class="odflc-required">
				<option value=""><?php esc_html_e( '-- Select Product --', 'odflc' ); ?></option>

				<?php
				$products = isset( $settings['odf_products'] ) ? $settings['odf_products'] : array();
				foreach( $products as $key => $product ) {
					$prodArr = explode( '|', $product );

					$sku	= isset( $prodArr[0] ) ? $prodArr[0] : '';
					$title	= isset( $prodArr[1] ) ? $prodArr[1] : '';

					// Check if blank value
					if( empty($sku) || empty($title) ) continue; ?>
					
					<option value="<?php echo esc_attr( $sku ); ?>" data-title="<?php esc_html_e( $title ); ?>"><?php esc_html_e( $title ); ?></option>
				<?php } ?>
			</select>
		</div>

		<div class="odflc-field-submit">
			<input type="submit" name="submit" class="btn button odflc-lead-capture-submit" value="<?php esc_html_e( 'Submit', 'odflc' ); ?>" />

			<img src="<?php echo site_url('/'); ?>wp-includes/images/spinner-2x.gif" alt="Loading ..." class="odflc-ajax-loader-img" />
		</div>
		<div class="odflc-form-msgs"></div>
	</form>
</div>

<style type="text/css">
	.odflc-field-wrap{ margin-bottom: 1.5rem; }
	.odflc-lead-capture-submit{ display: inline-block; }
	.odflc-ajax-loader-img{
		display: inline-block;
		display: none;
		vertical-align: middle;
	}
	.odflc-field-wrap input,
	.odflc-field-wrap textarea{
		display: block;
		width: 100%;
		padding: 12px 15px;
		border-size: 1px;
		border-style: solid;
		border-color: #dcd7ca;
	}
	.odflc-field-wrap .iti{ width: 100%; }
	.odflc-field-wrap select{
		display: block;
		width: 100%;
		padding: 12px 15px;
		border-radius: 0;
		border-color: #dcd7ca;
	}
	.odflc-has-error{ border-color: #ff0000 !important; }
	.odflc-form-msgs.error{
		margin-top: 10px;
		padding: 7px 15px;
		background-color: #FFD2D2;
		border: 1px solid #D8000C;
		color: #D8000C;
	}
	.odflc-form-msgs.success{
		margin-top: 10px;
		padding: 7px 15px;
		background-color: #DFF2BF;
		border: 1px solid #4F8A10;
		color: #4F8A10;
	}
</style>