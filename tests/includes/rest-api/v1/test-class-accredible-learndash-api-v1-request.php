<?php
/**
 * Class Accredible_Learndash_Api_V1_Request_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/rest-api/v1/class-accredible-learndash-api-v1-request.php';

/**
 * Unit tests for Accredible_Learndash_Api_V1_Request
 */
class Accredible_Learndash_Api_V1_Request_Test extends WP_UnitTestCase {
	/**
	 * Add custom logic to setUp.
	 */
	public function setUp(): void { // phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.voidFound
		parent::setUp();
		$this->base_url      = 'https://api.accredible.com/v1';
		$this->api_key       = 'someapikey';
		$this->post_data     = array(
			'credental' => array(
				'group_id'  => 1,
				'recipient' => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
			),
		);
		$this->response_body = wp_json_encode( array( 'status' => 'success' ) );
	}

	/**
	 * Test if it comes with expected attributes.
	 */
	public function test_construct() {
		$request = new Accredible_Learndash_Api_V1_Request( $this->base_url, $this->api_key );

		$this->assertEquals( $this->base_url, $request->base_url );
		$this->assertEquals(
			array(
				'Authorization'          => 'Token ' . $this->api_key,
				'Content-Type'           => 'application/json',
				'Accredible-Integration' => 'Learndash',
			),
			$request->headers
		);
	}

	/**
	 * Test if it makes a GET request and return parsed body.
	 */
	public function test_get() {
		// Stub the HTTP request.
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'GET', $args['method'] );
				$this->assertEquals( null, $args['body'] );
				$this->assertEquals(
					array(
						'Authorization'          => 'Token ' . $this->api_key,
						'Content-Type'           => 'application/json',
						'Accredible-Integration' => 'Learndash',
					),
					$args['headers']
				);

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

		$request = new Accredible_Learndash_Api_V1_Request( $this->base_url, $this->api_key );
		$res     = $request->get( '/credentials' );
		$this->assertEquals( json_decode( $this->response_body ), $res );
	}

	/**
	 * Test if it makes a POST request and return parsed body.
	 */
	public function test_post() {
		// Stub the HTTP request.
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'POST', $args['method'] );
				$this->assertEquals( wp_json_encode( $this->post_data ), $args['body'] );
				$this->assertEquals(
					array(
						'Authorization'          => 'Token ' . $this->api_key,
						'Content-Type'           => 'application/json',
						'Accredible-Integration' => 'Learndash',
					),
					$args['headers']
				);

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

		$request = new Accredible_Learndash_Api_V1_Request( $this->base_url, $this->api_key );
		$res     = $request->post( '/credentials', $this->post_data );
		$this->assertEquals( json_decode( $this->response_body ), $res );
	}
}
