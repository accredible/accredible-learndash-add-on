<?php
/**
 * Accredible LearnDash Add-on custom WP_UnitTestCase class.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Custom_Unit_Test_Case' ) ) :
	/**
	 * Accredible LearnDash Add-on custom WP_UnitTestCase class.
	 */
	abstract class Accredible_Learndash_Custom_Unit_Test_Case extends WP_UnitTestCase {
		/**
		 * Add custom logic to setUp.
		 */
		public function set_up() {
			parent::set_up();
			// XXX Stop transforming all `CREATE TABLE` to `CREATE TEMPORARY TABLE`.
			remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
			Accredible_Learndash_Admin_Database::setup();

			// Unset the development environment variable.
			putenv( 'ACCREDIBLE_LEARNDASH_API_ENDPOINT' );
		}

		/**
		 * Add custom logic to tearDown.
		 */
		public function tear_down() {
			parent::tear_down();
			Accredible_Learndash_Admin_Database::drop_all();
		}
	}
endif;
