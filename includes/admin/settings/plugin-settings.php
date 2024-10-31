<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page
 * The code for the plugins main settings page
 */

$settings = get_option( 'odflc_options' ); ?>

<div class="wrap odflc-settings">
	<h1><?php esc_html_e( 'OneDigiFlow Lead Capture - Settings', 'odflc' ); ?></h1>

	<?php
	// Print error messages
	settings_errors(); ?>

	<form action="options.php" method="post">
		<?php
		settings_fields( 'odflc_plugin_options' ); ?>

		<!-- beginning of the general settings meta box -->
		
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable"><div class="postbox">

				<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'odflc' ); ?>"><br /></div>
				<!-- general settings box title -->
				<h3 class="handle">
					<?php esc_html_e( 'OneDigiFlow API Settings', 'odflc' ); ?>
				</h3>

				<div class="inside">
					<table class="form-table">
						<tr>
							<th><label for="odflc-odf-api-key">
								<?php
								esc_html_e( 'API Key', 'odflc' ); ?>
							</label></th>
							<td>
								<input name="odflc_options[odf_api_key]" value="<?php echo !empty($settings['odf_api_key']) ? $settings['odf_api_key'] : ''; ?>" class="regular-text" id="odflc-odf-api-key" type="text" />

								<input type="submit" name="odflc-settings-save-btn" id="odflc-settings-inline-save-btn" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'odflc' ); ?>" />

								<input type="submit" name="odflc-product-data" id="odflc-product-inline-data" class="button" value="<?php esc_html_e('Refresh Product Data', 'odflc'); ?>">

								<p class="description"><?php esc_html_e( 'Please enter OneDigiFlow API key.', 'odflc' ); ?></p>
							</td>
						</tr>

						<tr>
							<td class="odflc-notice" colspan="2">
								<?php
								esc_html_e( 'You can use the shortcode given below to any page for display form in any page: ', 'odflc' ); ?><br />
								<code>[odflc_form classification="5" backlog="1"]</code>
							</td>
						</tr>

						<tr>
							<th colspan="2" style="padding: 15px 0 7px 0;"><label>
								<?php
								esc_html_e( 'Products', 'odflc' ); ?>
							</label></th>
						</tr>
						<tr>
							<td colspan="2" class="no-padding">
								<div class="odflc-products">
									<table class="wp-list-table widefat fixed striped">
										<?php
										$products = get_option( 'odflc_products_details' );
										
										// check if empty then check in API
										if( empty($products) ) {
											$odfApi = new OneDigiFlow_API();
											$products = $odfApi->odf_get_products();
										}

										$selectedProd = !empty( $settings['odf_products'] ) ? $settings['odf_products'] : array();

										if( ! empty($products) ) {
										foreach( $products as $key => $product ) {

											if( empty($product['product_sku_id']) ) continue;

											$productName = !empty( $product['product_sku_name'] ) ? $product['product_sku_name'] : esc_html__('No Name', 'odflc');

											$sku = $product['product_sku_id'] . '|' . $productName;

											echo '<tr>';
											echo '<th scope="row" class="check-column">';
											echo '<input type="checkbox" id="odf-product-'. $sku .'" name="odflc_options[odf_products][]" value="'.$sku.'" '. checked( in_array($sku, $selectedProd), true, false ) .' />';
											echo '</th>';

											echo '<td class="title column-title  column-primary"><label for="odf-product-' . $sku . '"><strong>' . $productName . '</strong></label></td>';
											echo '</tr>';
										}
										} ?>
									</table>

									<?php
									/* if( !empty($products) ) {
										foreach( $products as $key => $product ) { ?>
											<div class="odflc-product">
												<input name="odflc_options[odf_products][]" value="<?php echo $product; ?>" class="regular-text" type="text" />
											</div>
										<?php
										}
									} else { ?>
										<div class="odflc-product">
											<input name="odflc_options[odf_products][]" value="" class="regular-text" type="text" />
										</div>
									<?php } */ ?>
								</div>
								<!-- <button type="button" class="button odflc-add-product"><?php esc_html_e( 'Add Product', 'odflc' ); ?></button> -->

								<!-- <p class="description"><?php printf( esc_html__('Please enter the products sku and title by pipe line seperated. for example: %1sSKU|Product Title%2s.', 'odflc'), '<code>', '</code>' ); ?></p> -->

								<p class="description"><?php printf( esc_html__('Please select products those you want to display at frontend in dropdown.', 'odflc'), '<code>', '</code>' ); ?></p>
							</td>
						</tr>
					</table>

					<div class="odflc-settings-btns">
						<?php
						if( empty( $GLOBALS['hide_save_button'] ) ) :
							submit_button( esc_html__( 'Save Changes', 'odflc' ), 'primary', 'odflc-settings-save-btn' );
						endif;

						submit_button( esc_html__( 'Refresh Product Data', 'odflc' ), 'secondary', 'odflc-product-data' ); ?>
					</div>
				</div><!-- /.inside -->
			</div></div>
		</div><!-- /.metabox-holder -->

	</form>
</div>

<style type="text/css">
	p.submit{ padding-bottom: 0; }
	.odflc-product{ margin-bottom: 10px; }
	.odflc-products .check-column{
		vertical-align: middle;
		padding-top: 3px !important;
	}
	.odflc-settings td.no-padding{ padding: 0 !important; }
	.odflc-settings-btns p{ display: inline-block; margin: 5px; }
</style>

<script type="text/javascript">
	jQuery( document ).ready( function($) {
		$( document ).on( 'click', '.odflc-add-product', function(e) {

			var thisObj = $( this );
			var wrapObj = thisObj.parents( 'td' );

			var productRow = wrapObj.find( '.odflc-product:last-child' ).clone();
			productRow.find( 'input' ).val('');

			wrapObj.find( '.odflc-products' ).append( productRow );

			return false;
		} );
	} );
</script>