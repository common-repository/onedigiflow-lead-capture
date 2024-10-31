<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Class
 *
 * Class for manage public side functionalities
 *
 * @package OneDigiFlow - Lead Caputure
 * @since 1.0.0
 */
class ODFLC_Public{
	public function __construct() {

		// Form submit ajax request
		add_action( 'wp_ajax_odflc_submit_lead_capture_form', array($this, 'odflc_submit_lead_capture_form_process') );
		add_action( 'wp_ajax_nopriv_odflc_submit_lead_capture_form', array($this, 'odflc_submit_lead_capture_form_process') );
	}

	/**
	 * Form Submit process
	 */
	public function odflc_submit_lead_capture_form_process() {
		
		$postArr = array();
		$postArr['name']		= isset( $_POST['name'] ) ? sanitize_text_field($_POST['name']) : '';
		$postArr['email']		= isset( $_POST['email'] ) ? sanitize_email($_POST['email']) : '';
		$postArr['phone']		= isset( $_POST['phone'] ) ? sanitize_text_field($_POST['phone']) : '';
		$postArr['summary']		= isset( $_POST['summary'] ) ? sanitize_textarea_field($_POST['summary']) : '';
		$postArr['skus']		= isset( $_POST['skus'] ) ? sanitize_text_field($_POST['skus']) : '';
		$postArr['prodTitle']	= isset( $_POST['product_title'] ) ? sanitize_title($_POST['product_title']) : '';
		$postArr['phone_code']	= isset( $_POST['phone_code'] ) ? sanitize_text_field($_POST['phone_code']) : '';
		$postArr['backlog']			= isset( $_POST['backlog'] ) ? sanitize_text_field($_POST['backlog']) : '';
		$postArr['classification']	= isset( $_POST['classification'] ) ? sanitize_text_field($_POST['classification']) : '';

		// args
		$args = array(
			'post_title' => $postArr['name'],
			'post_content' => $postArr['summary'],
			'post_type' => 'odflc-leads',
			'post_status' => 'publish'
		);

		// insert post
		$post_id = wp_insert_post( $args );

		// inset post meta
		if( $post_id ) {
			update_post_meta( $post_id, '_odflc_lead_email', $postArr['email'] );
			update_post_meta( $post_id, '_odflc_lead_phone_code', $postArr['phone_code'] );
			update_post_meta( $post_id, '_odflc_lead_phone', $postArr['phone'] );
			update_post_meta( $post_id, '_odflc_lead_sku', $postArr['skus'] );
			update_post_meta( $post_id, '_odflc_lead_product_title', $postArr['prodTitle'] );
			update_post_meta( $post_id, '_odflc_classification', $postArr['classification'] );
			update_post_meta( $post_id, '_odflc_backlog', $postArr['backlog'] );

			// Add contact data to ODF
			$odfApi = new OneDigiFlow_API();

			$contact = $odfApi->odf_add_contact( $postArr );
			if( $contact && !empty($contact['id']) ){

				$_POST['contact_id'] = $contact['id'];
				update_post_meta( $post_id, '_odflc_is_odf_contact_id', $contact['id'] );

				// Add deal data to ODF
				$deal = $odfApi->odf_add_deal( $postArr );
				if( $deal ){
					update_post_meta( $post_id, '_odflc_is_odf_deal_created', 'yes' );
				}
			} else {
				$response['status'] = 0;
				$response['msg'] = esc_html__( 'Sorry, there seems to be an issue with the submission. Please try again later', 'odflc' );

				echo json_encode( $response );
				exit;
			}

			/**
			 * Admin Email
			 */

			// Set admin header
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			// Send email to admin
			$to[] = get_option( 'admin_email' );
			//$to[] = 'jaydeep.nimavat@gmail.com';

			// Subject
			$subject = esc_html__( 'Received new lead', 'odflc' );

			// Create mail content
			$body = "Hello, <br><br>";
			$body .= "You have received a new lead, please find details below: <br><br>";

			$body .= "Name: " . $postArr['name'] . "<br>"; 
			$body .= "Email: " . $postArr['email'] . "<br>"; 
			$body .= "Phone: +" . $postArr['phone_code'] .' '. $phone . "<br>"; 
			$body .= "Product: " . $postArr['prodTitle'] . "<br>"; 
			$body .= "Summary: " . $postArr['summary'] . "<br><br>";

			$body .= "Thanks and Regards, <br>";
			$body .= "Support Team.";

			// Send email to admin
			$mail = wp_mail( $to, $subject, $body, $headers );

			/**
			 * Customer email
			 */

			// Set admin header
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: '. get_bloginfo( 'name' ) .'<' . get_option( 'admin_email' ) . '>';

			// Send email to admin
			$to[] = $postArr['email'];
			//$to[] = 'jaydeep.nimavat@gmail.com';

			// Subject
			$subject = esc_html__( 'Thank you for reaching out to us', 'odflc' );

			// Create mail content
			$body = "Hello, <br><br>";
			$body .= "Thank you for submitting the details: <br><br>";

			$body .= "Name: " . $postArr['name'] . "<br>"; 
			$body .= "Email: " . $postArr['email'] . "<br>"; 
			$body .= "Phone: +" . $postArr['phone_code'] . ' ' . $postArr['phone'] . "<br>"; 
			$body .= "Product: " . $postArr['prodTitle'] . "<br>"; 
			$body .= "Summary: " . $postArr['summary'] . "<br><br>";

			$body .= "We shall reach out to you as soon as we can. <br><br>";

			$body .= "Thanks and Regards, <br>";
			$body .= "Support Team.";

			// Send email to customer
			$mail = wp_mail( $to, $subject, $body, $headers );

			$response['status'] = 1;
			$response['mail'] = $mail;
			$response['msg'] = esc_html__( 'Thank you for reaching out to us, we shall get back to you as soon as we can.', 'odflc' );
		} else {
			$response['status'] = 0;
			$response['msg'] = esc_html__( 'Sorry, there seems to be an issue with the submission. Please try again later', 'odflc' );
		}

		echo json_encode( $response );
		exit;
	}
}
return new ODFLC_Public();