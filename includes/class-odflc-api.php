<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * OneDigiFlow API Class
 *
 * Class for manage public side functionalities
 *
 * @package OneDigiFlow - Lead Caputure
 * @since 1.0.0
 */
class OneDigiFlow_API{

	private $apikey;
	public function __construct() {

		$this->init_api();
	}

	/**
	 * Init the required varialbles for APIs
	 */
	public function init_api() {
		$settings = get_option( 'odflc_options' );
		$this->apikey = !empty( $settings['odf_api_key'] ) ? $settings['odf_api_key'] : '';
	}

	/**
	 * Get Products
	 */
	public function odf_get_products(){

		// check if no api key inserted
		if( empty($this->apikey) ) return false;

		// Get API URL
		$apiURL = 'https://app.onedigiflow.com/api/v3/GetProductdetails';

		// manage arguments
		$args = array(
			'blocking' => true,
			'redirection' => 10,
			'timeout' => 30,
			'httpversion' => 1.1,
			'headers' => array(
				'auth_api_key' => $this->apikey,
				'Content-Type' => 'application/json'
			)
		);

		$response = wp_remote_post( $apiURL, $args );
		$response = wp_remote_retrieve_body( $response );

		$data = json_decode( $response, true );
		if( isset($data['status']) && $data['status'] == '1' 
			&& isset($data['data']['productDetail']) ) {

			update_option( 'odflc_products_details', $data['data']['productDetail'] );
			return $data['data']['productDetail'];
		} else {
			update_option( 'odflc_products_details', array() );
		}

		return false;
	}

	/**
	 * add contact
	 */
	public function odf_add_contact($contact) {
		// check if no api key inserted
		if( empty($this->apikey) ) return false;

		$apiURL = 'https://app.onedigiflow.com/api/v3/addContactData';

		$fields = array(
			'contact_name'		=> isset($contact['name']) ? $contact['name'] : '',
			'mobile'			=> isset($contact['phone']) ? $contact['phone'] : '',
			'email'				=> isset($contact['email']) ? $contact['email'] : '',
			'descriptioin'		=> '',
			'address'			=> '',
			'client_id'			=> '',
			'designation'		=> '',
			'classification'	=> isset($contact['classification']) ? $contact['classification'] : '',
			'country_code'		=> isset($contact['phone_code']) ? '+' + $contact['phone_code'] : '',
		);

		// Manage arguments
		$args = array(
			'body' => json_encode($fields),
			'blocking' => true,
			'redirection' => 10,
			'timeout' => 30,
			'httpversion' => 1.1,
			'headers' => array(
				'auth_api_key' => $this->apikey,
				'Content-Type' => 'application/json'
			)
		);

		$response = wp_remote_post( $apiURL, $args );
		$response = wp_remote_retrieve_body( $response );

		$data = json_decode( $response, true );

		if( isset($data['status']) && $data['status'] == '1' 
			&& isset($data['data']) ) {

			$data['data']['id'] = isset( $data['last_contact_id'] ) ? $data['last_contact_id'] : '';

			return $data['data'];
		}

		return false;
	}

	/**
	 * Create deal
	 */
	public function odf_add_deal( $data ) {
		// check if no api key inserted
		if( empty($this->apikey) ) return false;

		$apiURL = 'https://app.onedigiflow.com/api/v3/addDealData';

		$deal_name = !empty($data['skus']) ? $data['skus'] : '';

		// Cut to 20 charactors
		$deal_name = ( strlen($deal_name) > 20 ) ? substr( $deal_name, 0, 20 ) : $deal_name;

		$fields = array(
			'deal_name'			=> $deal_name,
			'deal_date'			=> date('Y-m-d'),
			'deal_value'		=> '',
			'deal_contact'		=> $data['contact_id'],
			'deal_owner'		=> '',
			'deal_id'			=> '',
			'deal_product_id'	=> $data['skus'],
			'deal_source'		=> '',
			'deal_document'		=> '',
			'deal_stage'		=> isset( $data['backlog'] ) ? $data['backlog'] : '1',
			'deal_description'	=> isset( $data['summary'] ) ? $data['summary'] : '',
		);

		// Manage arguments
		$args = array(
			'customrequest' => 'POST',
			'method' => 'POST',
			'body' => $fields,
			'blocking' => true,
			'redirection' => 10,
			'timeout' => 45,
			'httpversion' => 1.1,
			'headers' => array(
				'auth_api_key' => $this->apikey,
				'accept' => 'application/json',
				'Content-Type' => 'multipart/form-data'
			)
		);

		$response = wp_remote_request( $apiURL, $args );
		$response = wp_remote_retrieve_body( $response );
		
		$data = json_decode( $response, true );

		if( isset($data['status']) && $data['status'] == '1' 
			&& isset($data['data']) ) {
			return $data['data'];
		}

		return false;
	}
}