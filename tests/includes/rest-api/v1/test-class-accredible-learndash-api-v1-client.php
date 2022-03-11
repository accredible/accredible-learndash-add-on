<?php
/**
 * Class Accredible_Learndash_Api_V1_Client_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/rest-api/v1/class-accredible-learndash-api-v1-client.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/tests/class-accredible-learndash-custom-unit-test-case.php';

/**
 * Unit tests for Accredible_Learndash_Api_V1_Client
 */
class Accredible_Learndash_Api_V1_Client_Test extends Accredible_Learndash_Custom_Unit_Test_Case {
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
	 * Test if it comes with expected attributes for the local client.
	 */
	public function test_construct_as_local() {
		$api_key = 'someapikey';
		update_option( 'accredible_learndash_api_key', $api_key );
		update_option( 'accredible_learndash_server_region', 'us' );
		putenv( 'ACCREDIBLE_LEARNDASH_API_ENDPOINT=http://localhost:3000/v1' );

		$client = new Accredible_Learndash_Api_V1_Client();
		$this->assertEquals( 'http://localhost:3000/v1', $client->request->base_url );
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
				'meta_data'         => array(
					'learndash_post_id' => 123,
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
					'response' => array( 'code' => 200 ),
					'body'     => $this->response_body,
				);
			},
			10,
			3
		);

		$client = new Accredible_Learndash_Api_V1_Client();
		$res    = $client->create_credential( 9549, 'Tom Test', 'TOM@example.com', 123, array( 'grade' => '100' ) );
		$this->assertEquals( json_decode( $this->response_body, true ), $res );
	}

	/**
	 * Test if it makes a POST request and return parsed body.
	 */
	public function test_create_credential_when_unauthorized() {
		$this->post_data = array(
			'credential' => array(
				'group_id'          => 9549,
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'meta_data'         => array(
					'learndash_post_id' => 123,
				),
				'custom_attributes' => array(
					'grade' => '100',
				),
			),
		);
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
					'response' => array( 'code' => 401 ),
					'body'     => 'HTTP Token: Access denied.',
				);
			},
			10,
			3
		);

		$client = new Accredible_Learndash_Api_V1_Client();
		$res    = $client->create_credential( 9549, 'Tom Test', 'TOM@example.com', 123, array( 'grade' => '100' ) );
		$this->assertEquals( array( 'errors' => '401: HTTP Token: Access denied.' ), $res );
	}

	/**
	 * Test if it makes a POST request and return parsed body.
	 */
	public function test_create_credential_when_bad_request() {
		$this->post_data = array(
			'credential' => array(
				'group_id'          => 9549,
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'meta_data'         => array(
					'learndash_post_id' => 123,
				),
				'custom_attributes' => array(
					'grade' => '100',
				),
			),
		);
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
					'response' => array( 'code' => 400 ),
					'body'     => wp_json_encode( array( 'errors' => 'Invalid group' ) ),
				);
			},
			10,
			3
		);

		$client = new Accredible_Learndash_Api_V1_Client();
		$res    = $client->create_credential( 9549, 'Tom Test', 'TOM@example.com', 123, array( 'grade' => '100' ) );
		$this->assertEquals( array( 'errors' => 'Invalid group' ), $res );
	}

	/**
	 * Test if it makes a GET request and return parsed body.
	 */
	public function test_organization_search() {
		$this->response_body = file_get_contents( ACCREDILBE_LEARNDASH_API_FIXTURES_PATH . '/organizations/search_success.json' );
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );

		// Stub the HTTP request.
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/issuer/details', $url );
				$this->assertEquals( 'GET', $args['method'] );

				return array(
					'response' => array( 'code' => 200 ),
					'body'     => $this->response_body,
				);
			},
			10,
			3
		);

		$client = new Accredible_Learndash_Api_V1_Client();
		$res    = $client->organization_search();
		$this->assertEquals( json_decode( $this->response_body, true ), $res );
	}

	/**
	 * Test if it makes a GET request and return parsed body.
	 */
	public function test_organization_search_when_unauthorized() {
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'eu' );

		// Stub the HTTP request.
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://eu.api.accredible.com/v1/issuer/details', $url );
				$this->assertEquals( 'GET', $args['method'] );

				return array(
					'response' => array( 'code' => 401 ),
					'body'     => 'HTTP Token: Access denied.',
				);
			},
			10,
			3
		);

		$client = new Accredible_Learndash_Api_V1_Client();
		$res    = $client->organization_search();
		$this->assertEquals( array( 'errors' => '401: HTTP Token: Access denied.' ), $res );
	}
}
