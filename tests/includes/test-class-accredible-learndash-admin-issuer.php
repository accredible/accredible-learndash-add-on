<?php
/**
 * Class Accredible_Learndash_Admin_Menu_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-issuer.php';

/**
 * Unit tests for Accredible_Learndash_Admin_Issuer
 */
class Accredible_Learndash_Admin_Issuer_Test extends WP_UnitTestCase {
	/**
	 * Test if issuer is returned
	 */
	public function test_search_issuer() {
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

		$response          = Accredible_Learndash_Admin_Issuer::search_issuer();
		$expected_response = array(
			'id'                 => 124,
			'name'               => 'Organization Example',
			'emil'               => 'test@example.com',
			'certificate_left'   => 1200,
			'language'           => 'en',
			'url'                => 'https://www.accredible.com',
			'whitelisted_domain' => null,
		);

		$this->assertEquals( $expected_response, $response );
	}

	/**
	 * Test if nothing is returned when incorrect API KEY
	 */
	public function test_search_issuer_when_unauthorized() {
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

		$response = Accredible_Learndash_Admin_Issuer::search_issuer();

		$this->assertEquals( null, $response );
	}
}
