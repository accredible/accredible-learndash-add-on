<?php
/**
 * Class Accredible_Learndash_Admin_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin.php';

/**
 * Unit tests for Accredible_Learndash_Admin
 */
class Accredible_Learndash_Admin_Test extends WP_UnitTestCase {
	/**
	 * Test if it adds admin WP hooks.
	 */
	public function test_init() {
		$activation_hook_name = 'activate_' . ACCREDILBE_LEARNDASH_PLUGIN_BASENAME;
		// Reset related WP filters.
		remove_all_filters( 'admin_init' );
		remove_all_filters( 'admin_menu' );
		remove_all_filters( $activation_hook_name );

		Accredible_Learndash_Admin::init();

		$this->assertEquals(
			10,
			has_filter( 'admin_init', array( 'Accredible_Learndash_Admin_Setting', 'register' ) )
		);
		$this->assertEquals(
			10,
			has_filter( 'admin_menu', array( 'Accredible_Learndash_Admin_Menu', 'add' ) )
		);
		$this->assertEquals(
			10,
			has_filter(
				$activation_hook_name,
				array( 'Accredible_Learndash_Admin_Setting', 'set_default' )
			)
		);
		$this->assertEquals(
			10,
			has_filter(
				$activation_hook_name,
				array( 'Accredible_Learndash_Admin_Database', 'setup' )
			)
		);
	}
}