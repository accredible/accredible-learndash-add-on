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
		// TODO: NTGR-520.
		$this->assertTrue( true );
	}
}
