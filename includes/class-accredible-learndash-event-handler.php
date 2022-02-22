<?php
/**
 * Accredible LearnDash Add-on Event Handler class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Event_Handler' ) ) :
	/**
	 * Accredible LearnDash Add-on Event Handler class
	 */
	class Accredible_Learndash_Event_Handler {
		/**
		 * Handle `learndash_course_completed` Action Hooks
		 *
		 * @param Array $data course data.
		 */
		public static function handle_course_completed( $data ) {
			// TODO: Add logic (NTGR-520).
		}
	}
endif;
