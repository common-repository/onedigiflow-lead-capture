<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Lead Info Metabox file
 */
global $post;

$post_id = isset( $post->ID ) ? $post->ID : '';

$email		= get_post_meta( $post_id, '_odflc_lead_email', true );
$phone_code = get_post_meta( $post_id, '_odflc_lead_phone_code', true );
$phone		= get_post_meta( $post_id, '_odflc_lead_phone', true );
$sku		= get_post_meta( $post_id, '_odflc_lead_sku', true );

$contact	= get_post_meta( $post_id, '_odflc_is_odf_contact_id', true );
$deal		= get_post_meta( $post_id, '_odflc_is_odf_deal_created', true ); ?>

<div class="odflc-lead-info">
	<table class="form-table">
		<tr>
			<th><label for="odflc-lead-email">
				<?php esc_html_e( 'Lead Email', 'odflc' ); ?>
			</label></th>
			<td>
				<input type="text" name="_odflc_lead_email" id="odflc-lead-email" value="<?php echo $email; ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="odflc-lead-phone-code">
				<?php esc_html_e( 'Phone Code', 'odflc' ); ?>
			</label></th>
			<td>
				<input type="text" name="_odflc_lead_phone_code" id="odflc-lead-phone-code" value="<?php echo $phone_code; ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="odflc-lead-phone">
				<?php esc_html_e( 'Lead Phone', 'odflc' ); ?>
			</label></th>
			<td>
				<input type="text" name="_odflc_lead_phone" id="odflc-lead-phone" value="<?php echo $phone; ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="odflc-lead-sku">
				<?php esc_html_e( 'Lead SKU', 'odflc' ); ?>
			</label></th>
			<td>
				<input type="text" name="_odflc_lead_sku" id="odflc-lead-sku" value="<?php echo $sku; ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label>
				<?php esc_html_e( 'Contact Created?', 'odflc' ) ?>
			</label></th>
			<td><div class="odflc-contact-meta-wrap">
				<?php
				if( empty($contact) ) { ?>
					<button class="button odflc-create-contact" data-postid="<?php echo esc_attr($post_id); ?>">
						<?php esc_html_e( 'Create Contact', 'odflc' ); ?>
					</button>
					<img src="<?php echo site_url('/'); ?>wp-includes/images/spinner.gif" alt="Loading ..." class="odflc-ajax-loader-img" />
				<?php
				} else {
					esc_html_e( 'Yes', 'odflc' );
				} ?>
			</div></td>
		</tr>
		<tr>
			<th><label>
				<?php esc_html_e( 'Deal Created?', 'odflc' ) ?>
			</label></th>
			<td><div class="odflc-deal-meta-wrap">
				<?php
				if( empty($deal) ) { ?>
					<button class="button odflc-create-deal" data-postid="<?php echo esc_attr($post_id); ?>">
						<?php esc_html_e( 'Create Deal', 'odflc' ); ?>
					</button>
					<img src="<?php echo site_url('/'); ?>wp-includes/images/spinner.gif" alt="Loading ..." class="odflc-ajax-loader-img" />
				<?php
				} else {
					esc_html_e( 'Yes', 'odflc' );
				} ?>
			</div></td>
		</tr>
	</table>
</div>

<style type="text/css">
	.odflc-ajax-loader-img{ margin: 4px; display: none;  }
</style>