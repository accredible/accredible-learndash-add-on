<?php
/**
 * Class Accredible_Learndash_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-event-handler.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash.php';

/**
 * Unit tests for Accredible_Learndash
 */
class Accredible_Learndash_Test extends WP_UnitTestCase {
	/**
	 * Test if it does nothing without LearnDash.
	 */
	public function test_init_without_learndash() {
		// Reset related WP filters.
		remove_all_filters( 'learndash_course_completed' );

		Accredible_Learndash::init();

		$this->assertFalse(
			has_filter(
				'learndash_course_completed',
				array( 'Accredible_Learndash_Event_Handler', 'handle_course_completed' )
			)
		);
	}

	/**
	 * Test if it adds Action Hooks with LearnDash.
	 */
	public function test_init_with_learndash() {
		// Reset related WP filters.
		remove_all_filters( 'learndash_course_completed' );

		// Mock the LearnDash class.
		$this->getMockBuilder( 'SFWD_LMS' )->getMock();

		Accredible_Learndash::init();

		$this->assertEquals(
			20,
			has_filter(
				'learndash_course_completed',
				array( 'Accredible_Learndash_Event_Handler', 'handle_course_completed' )
			)
		);
	}
}
