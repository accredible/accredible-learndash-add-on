<?php
/**
 * Class Accredible_Learndash_Event_Handler_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-event-handler.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/models/class-accredible-learndash-model-auto-issuance.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/models/class-accredible-learndash-model-auto-issuance-log.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/tests/class-accredible-learndash-custom-unit-test-case.php';

/**
 * Unit tests for Accredible_Learndash_Event_Handler
 */
class Accredible_Learndash_Event_Handler_Test extends Accredible_Learndash_Custom_Unit_Test_Case {
	/**
	 * Test if it handles `learndash_course_completed` Action Hooks.
	 */
	public function test_handle_course_completed() {
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );
		$user_id       = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
				'user_email' => 'tom@example.com',
			)
		);
		$user          = get_user_by( 'id', $user_id );
		$course        = self::init_course_object();
		$data          = array(
			'course' => $course,
			'user'   => $user,
		);
		$auto_issuance = array(
			'post_id'             => $course->ID,
			'kind'                => 'course_completed',
			'accredible_group_id' => 9549,
		);
		Accredible_Learndash_Model_Auto_Issuance::insert( $auto_issuance );

		// Stub the HTTP request.
		$this->request_count = 0;
		$this->post_data     = array(
			'credential' => array(
				'group_id'          => '9549',
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'custom_attributes' => null,
			),
		);
		$this->response_body = file_get_contents( ACCREDILBE_LEARNDASH_API_FIXTURES_PATH . '/credentials/create_success.json' );
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'POST', $args['method'] );
				$this->assertEquals( wp_json_encode( $this->post_data ), $args['body'] );

				$this->request_count++;
				return array(
					'response' => array( 'code' => 200 ),
					'body'     => $this->response_body,
				);
			},
			10,
			3
		);

		$result = Accredible_Learndash_Event_Handler::handle_course_completed( $data );
		$this->assertEquals( 1, $result );
		$this->assertEquals( 1, $this->request_count );
	}

	/**
	 * Test if it handles `learndash_course_completed` Action Hooks when auto issuance not found.
	 */
	public function test_handle_course_completed_when_auto_issuance_not_found() {
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );
		$user_id       = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
				'user_email' => 'tom@example.com',
			)
		);
		$user          = get_user_by( 'id', $user_id );
		$course        = self::init_course_object();
		$data          = array(
			'course' => $course,
			'user'   => $user,
		);
		$auto_issuance = array(
			'post_id'             => 99999,
			'kind'                => 'course_completed',
			'accredible_group_id' => 9549,
		);
		Accredible_Learndash_Model_Auto_Issuance::insert( $auto_issuance );

		// Stub the HTTP request.
		$this->request_count = 0;
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				if ( 'https://api.accredible.com/v1/credentials' === $url && 'POST' === $args['method'] ) {
					$this->request_count++;
				}
			},
			10,
			3
		);

		$result = Accredible_Learndash_Event_Handler::handle_course_completed( $data );
		$this->assertEquals( 0, $result );
		$this->assertEquals( 0, $this->request_count );
	}

	/**
	 * Test if it handles `learndash_course_completed` Action Hooks when API key not found.
	 */
	public function test_handle_course_completed_when_api_key_not_found() {
		update_option( 'accredible_learndash_api_key', '' );
		update_option( 'accredible_learndash_server_region', 'us' );
		$user_id       = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
				'user_email' => 'tom@example.com',
			)
		);
		$user          = get_user_by( 'id', $user_id );
		$course        = self::init_course_object();
		$data          = array(
			'course' => $course,
			'user'   => $user,
		);
		$auto_issuance = array(
			'post_id'             => $course->ID,
			'kind'                => 'course_completed',
			'accredible_group_id' => 9549,
		);
		Accredible_Learndash_Model_Auto_Issuance::insert( $auto_issuance );

		// Stub the HTTP request.
		$this->request_count = 0;
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				if ( 'https://api.accredible.com/v1/credentials' === $url && 'POST' === $args['method'] ) {
					$this->request_count++;
				}
			},
			10,
			3
		);

		$result = Accredible_Learndash_Event_Handler::handle_course_completed( $data );
		$this->assertEquals( 0, $result );
		$this->assertEquals( 0, $this->request_count );
	}

	/**
	 * Test if it returns user's full name.
	 */
	public function test_get_recipient_name() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
			)
		);
		$user    = get_user_by( 'id', $user_id );

		$private_method = self::getMethod( 'get_recipient_name' );
		$this->assertEquals(
			'Tom Test',
			$private_method->invokeArgs( null, array( $user ) )
		);
	}

	/**
	 * Test if it returns user's first name.
	 */
	public function test_get_recipient_name_when_first_name_only() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => '',
			)
		);
		$user    = get_user_by( 'id', $user_id );

		$private_method = self::getMethod( 'get_recipient_name' );
		$this->assertEquals(
			'Tom',
			$private_method->invokeArgs( null, array( $user ) )
		);
	}

	/**
	 * Test if it returns user's last name.
	 */
	public function test_get_recipient_name_when_last_name_only() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => '',
				'last_name'  => 'Test',
			)
		);
		$user    = get_user_by( 'id', $user_id );

		$private_method = self::getMethod( 'get_recipient_name' );
		$this->assertEquals(
			'Test',
			$private_method->invokeArgs( null, array( $user ) )
		);
	}

	/**
	 * Test if it returns user's display name.
	 */
	public function test_get_recipient_name_when_display_name_only() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => '',
				'last_name'  => '',
			)
		);
		$user    = get_user_by( 'id', $user_id );

		$private_method = self::getMethod( 'get_recipient_name' );
		$this->assertEquals(
			$user->display_name,
			$private_method->invokeArgs( null, array( $user ) )
		);
	}

	/**
	 * Test if it calls the endpoint and creates a successful log.
	 */
	public function test_create_credential() {
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
				'user_email' => 'tom@example.com',
			)
		);
		$course  = self::init_course_object();
		Accredible_Learndash_Model_Auto_Issuance::insert(
			array(
				'post_id'             => $course->ID,
				'kind'                => 'course_completed',
				'accredible_group_id' => 9549,
			)
		);

		// Stub the HTTP request.
		$this->request_count = 0;
		$this->post_data     = array(
			'credential' => array(
				'group_id'          => '9549',
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'custom_attributes' => null,
			),
		);
		$this->response_body = file_get_contents( ACCREDILBE_LEARNDASH_API_FIXTURES_PATH . '/credentials/create_success.json' );
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'POST', $args['method'] );
				$this->assertEquals( wp_json_encode( $this->post_data ), $args['body'] );

				$this->request_count++;
				return array(
					'response' => array( 'code' => 200 ),
					'body'     => $this->response_body,
				);
			},
			10,
			3
		);

		$auto_issuance   = Accredible_Learndash_Model_Auto_Issuance::get_results( "kind = 'course_completed' AND post_id = $course->ID" )[0];
		$recipient_name  = 'Tom Test';
		$recipient_email = 'tom@example.com';

		$private_method = self::getMethod( 'create_credential' );
		$private_method->invokeArgs( null, array( $auto_issuance, $user_id, $recipient_name, $recipient_email ) );

		$this->assertEquals( 1, $this->request_count );
		$auto_issuance_log = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( "accredible_learndash_auto_issuance_id = $auto_issuance->id" )[0];
		$this->assertEquals( $user_id, $auto_issuance_log->user_id );
		$this->assertEquals( 9549, $auto_issuance->accredible_group_id );
		$this->assertEquals( 'Example Certificate Design', $auto_issuance_log->accredible_group_name );
		$this->assertEquals( $recipient_name, $auto_issuance_log->recipient_name );
		$this->assertEquals( $recipient_email, $auto_issuance_log->recipient_email );
		$this->assertEmpty( $auto_issuance_log->error_message );
		$this->assertEquals( 'https://www.credential.net/10000005', $auto_issuance_log->credential_url );
	}

	/**
	 * Test if it calls the endpoint and creates an unauthorized log.
	 */
	public function test_create_credential_when_unauthorized() {
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
				'user_email' => 'tom@example.com',
			)
		);
		$course  = self::init_course_object();
		Accredible_Learndash_Model_Auto_Issuance::insert(
			array(
				'post_id'             => $course->ID,
				'kind'                => 'course_completed',
				'accredible_group_id' => 9549,
			)
		);

		// Stub the HTTP request.
		$this->request_count = 0;
		$this->post_data     = array(
			'credential' => array(
				'group_id'          => '9549',
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'custom_attributes' => null,
			),
		);
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'POST', $args['method'] );
				$this->assertEquals( wp_json_encode( $this->post_data ), $args['body'] );

				$this->request_count++;
				return array(
					'response' => array( 'code' => 401 ),
					'body'     => 'HTTP Token: Access denied.',
				);
			},
			10,
			3
		);

		$auto_issuance   = Accredible_Learndash_Model_Auto_Issuance::get_results( "kind = 'course_completed' AND post_id = $course->ID" )[0];
		$recipient_name  = 'Tom Test';
		$recipient_email = 'tom@example.com';

		$private_method = self::getMethod( 'create_credential' );
		$private_method->invokeArgs( null, array( $auto_issuance, $user_id, $recipient_name, $recipient_email ) );

		$this->assertEquals( 1, $this->request_count );
		$auto_issuance_log = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( "accredible_learndash_auto_issuance_id = $auto_issuance->id" )[0];
		$this->assertEquals( $user_id, $auto_issuance_log->user_id );
		$this->assertEquals( 9549, $auto_issuance->accredible_group_id );
		$this->assertEmpty( $auto_issuance_log->accredible_group_name );
		$this->assertEquals( $recipient_name, $auto_issuance_log->recipient_name );
		$this->assertEquals( $recipient_email, $auto_issuance_log->recipient_email );
		$this->assertEquals( '401: HTTP Token: Access denied.', $auto_issuance_log->error_message );
		$this->assertEmpty( $auto_issuance_log->credential_url );
	}

	/**
	 * Test if it calls the endpoint and creates a bad request log.
	 */
	public function test_create_credential_when_bad_request() {
		update_option( 'accredible_learndash_api_key', 'someapikey' );
		update_option( 'accredible_learndash_server_region', 'us' );
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name'  => 'Test',
				'user_email' => 'tom@example.com',
			)
		);
		$course  = self::init_course_object();
		Accredible_Learndash_Model_Auto_Issuance::insert(
			array(
				'post_id'             => $course->ID,
				'kind'                => 'course_completed',
				'accredible_group_id' => 9549,
			)
		);

		// Stub the HTTP request.
		$this->request_count = 0;
		$this->post_data     = array(
			'credential' => array(
				'group_id'          => '9549',
				'recipient'         => array(
					'name'  => 'Tom Test',
					'email' => 'tom@example.com',
				),
				'custom_attributes' => null,
			),
		);
		add_filter(
			'pre_http_request',
			function( $_preempt, $args, $url ) {
				$this->assertEquals( 'https://api.accredible.com/v1/credentials', $url );
				$this->assertEquals( 'POST', $args['method'] );
				$this->assertEquals( wp_json_encode( $this->post_data ), $args['body'] );

				$this->request_count++;
				return array(
					'response' => array( 'code' => 400 ),
					'body'     => wp_json_encode( array( 'errors' => 'Invalid group' ) ),
				);
			},
			10,
			3
		);

		$auto_issuance   = Accredible_Learndash_Model_Auto_Issuance::get_results( "kind = 'course_completed' AND post_id = $course->ID" )[0];
		$recipient_name  = 'Tom Test';
		$recipient_email = 'tom@example.com';

		$private_method = self::getMethod( 'create_credential' );
		$private_method->invokeArgs( null, array( $auto_issuance, $user_id, $recipient_name, $recipient_email ) );

		$this->assertEquals( 1, $this->request_count );
		$auto_issuance_log = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( "accredible_learndash_auto_issuance_id = $auto_issuance->id" )[0];
		$this->assertEquals( $user_id, $auto_issuance_log->user_id );
		$this->assertEquals( 9549, $auto_issuance->accredible_group_id );
		$this->assertEmpty( $auto_issuance_log->accredible_group_name );
		$this->assertEquals( $recipient_name, $auto_issuance_log->recipient_name );
		$this->assertEquals( $recipient_email, $auto_issuance_log->recipient_email );
		$this->assertEquals( 'Invalid group', $auto_issuance_log->error_message );
		$this->assertEmpty( $auto_issuance_log->credential_url );
	}

	/**
	 * Access private methods with `ReflectionMethod::invokeArgs`.
	 *
	 * @param string $name Name of the private method.
	 */
	private static function getMethod( $name ) {
		$class  = new ReflectionClass( 'Accredible_Learndash_Event_Handler' );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );
		return $method;
	}

	/**
	 * Initialize a mock course object.
	 */
	private static function init_course_object() {
		return (object) array(
			'ID' => 1,
		);
	}
}
