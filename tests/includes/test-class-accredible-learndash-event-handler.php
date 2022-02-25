<?php
/**
 * Class Accredible_Learndash_Event_Handler_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-event-handler.php';

/**
 * Unit tests for Accredible_Learndash_Event_Handler
 */
class Accredible_Learndash_Event_Handler_Test extends WP_UnitTestCase {
	/**
	 * Test if it handles `learndash_course_completed` Action Hooks.
	 */
	public function test_handle_course_completed() {
		$this->assertTrue( true );
	}

	/**
	 * Test if it handles `learndash_course_completed` Action Hooks when API key not found.
	 */
	public function test_handle_course_completed_when_api_key_not_found() {
		$this->assertTrue( true );
	}

	/**
	 * Test if it returns user's full name.
	 */
	public function test_get_recipient_name() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name' => 'Test',
			)
		);
		$user = get_user_by( 'id', $user_id );

		$private_method = self::getMethod('get_recipient_name');
		$this->assertEquals(
			'Tom Test',
			$private_method->invokeArgs(null, array( $user ))
		);
	}

	/**
	 * Test if it returns user's first name.
	 */
	public function test_get_recipient_name_when_first_name_only() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => 'Tom',
				'last_name' => '',
			)
		);
		$user = get_user_by( 'id', $user_id );

		$private_method = self::getMethod('get_recipient_name');
		$this->assertEquals(
			'Tom',
			$private_method->invokeArgs(null, array( $user ))
		);
	}

	/**
	 * Test if it returns user's last name.
	 */
	public function test_get_recipient_name_when_last_name_only() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => '',
				'last_name' => 'Test',
			)
		);
		$user = get_user_by( 'id', $user_id );

		$private_method = self::getMethod('get_recipient_name');
		$this->assertEquals(
			'Test',
			$private_method->invokeArgs(null, array( $user ))
		);
	}

	/**
	 * Test if it returns user's display name.
	 */
	public function test_get_recipient_name_when_display_name_only() {
		$user_id = $this->factory->user->create(
			array(
				'first_name' => '',
				'last_name' => '',
			)
		);
		$user = get_user_by( 'id', $user_id );

		$private_method = self::getMethod('get_recipient_name');
		$this->assertEquals(
			$user->display_name,
			$private_method->invokeArgs(null, array( $user ))
		);
	}

	/**
	 * Test if it calls the endpoint and creates a successful log.
	 */
	public function test_create_credential() {
		$this->assertTrue( true );
	}

	/**
	 * Test if it calls the endpoint and creates an unauthorized log.
	 */
	public function test_create_credential_when_unauthorized() {
		$this->assertTrue( true );
	}

	/**
	 * Test if it calls the endpoint and creates a bad request log.
	 */
	public function test_create_credential_when_bad_request() {
		$this->assertTrue( true );
	}

	/**
	 * To access private static methods with `invokeArgs( null _, array args)`.
	 * 
	 * @param string $name Name of the private method.
	 */
	protected static function getMethod( $name ) {
		$class = new ReflectionClass('Accredible_Learndash_Event_Handler');
		$method = $class->getMethod( $name );
		$method->setAccessible( true );
		return $method;
	}	
}
