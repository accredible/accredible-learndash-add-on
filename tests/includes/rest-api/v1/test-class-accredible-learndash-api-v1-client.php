<?php
/**
 * Class Accredible_Learndash_Api_V1_Client_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/rest-api/v1/class-accredible-learndash-api-v1-client.php';

/**
 * Unit tests for Accredible_Learndash_Api_V1_Client
 */
class Accredible_Learndash_Api_V1_Client_Test extends WP_UnitTestCase {
	/**
	 * Test if it comes with expected attributes for the US client.
	 */
	public function test_construct_as_us() {
		$api_key = 'someapikey';
		update_option( 'accredible_learndash_api_key', $api_key );
		update_option( 'accredible_learndash_server_region', 'us' );

		$client = new Accredible_Learndash_Api_V1_Client();
		$this->assertEquals( 'https://api.accredible.com/v1', $client->request->base_url );
		$this->assertEquals(
			'Token ' . $api_key,
			$client->request->headers['Authorization']
		);
	}

	/**
	 * Test if it comes with expected attributes for the EU client.
	 */
	public function test_construct_as_eu() {
		$api_key = 'someapikey';
		update_option( 'accredible_learndash_api_key', $api_key );
		update_option( 'accredible_learndash_server_region', 'eu' );

		$client = new Accredible_Learndash_Api_V1_Client();
		$this->assertEquals( 'https://eu.api.accredible.com/v1', $client->request->base_url );
		$this->assertEquals(
			'Token ' . $api_key,
			$client->request->headers['Authorization']
		);
	}

	/**
	 * Test if it makes a POST request and return parsed body.
	 */
	public function test_create_credential() {
		$this->post_data     = array(
			'credential' => array(
				'group_id'          => 9549,
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'custom_attributes' => array(
					'grade' => '100',
				),
			),
		);
		$this->response_body = file_get_contents( ACCREDILBE_LEARNDASH_API_FIXTURES_PATH . '/credentials/create_success.json' );
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );

		// Stub the HTTP request.
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'POST', $args['method'] );
				$this->assertEquals( wp_json_encode( $this->post_data ), $args['body'] );

				return array(
					'headers'     => array(),
					'cookies'     => array(),
					'filename'    => null,
					'response'    => 200,
					'status_code' => 200,
					'success'     => 1,
					'body'        => $this->response_body,
				);
			},
			10,
			3
		);

		$client = new Accredible_Learndash_Api_V1_Client();
		$res    = $client->create_credential( 9549, 'Tom Test', 'TOM@example.com', array( 'grade' => '100' ) );
		$this->assertEquals( json_decode( $this->response_body ), $res );
	}
}
