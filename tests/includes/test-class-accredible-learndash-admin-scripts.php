<?php
/**
 * Class Accredible_Learndash_Admin_Scripts_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-scripts.php';

/**
 * Unit tests for Accredible_Learndash_Scripts
 */
class Accredible_Learndash_Admin_Scripts_Test extends WP_UnitTestCase {
	/**
	 * Test if it enqueues styles.
	 */
	public function test_load_resources() {
		// Reset related WP styles.
		wp_dequeue_style( 'accredible-learndash-admin-theme' );
		wp_dequeue_style( 'accredible-learndash-admin-settings' );

		Accredible_Learndash_Admin_Scripts::load_resources();

		global $wp_styles;
		$this->assertNotNull( $wp_styles->registered['accredible-learndash-admin-theme'] );
		$this->assertNotNull( $wp_styles->registered['accredible-learndash-admin-settings'] );
	}

	/**
	 * Test if it adds the class if in plugin page.
	 */
	public function test_should_add_admin_body_class() {
		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );

		// Navigate to plugin page.
		$this->go_to( admin_url( 'admin.php?page=accredible_learndash' ) );
		set_current_screen( 'toplevel_page_accredible_learndash' );

		$admin_class = ' accredible-learndash-admin ';
		$extra_class = 'extra-class';

		$this->assertSame( $admin_class, Accredible_Learndash_Admin_Scripts::add_admin_body_class() );
		$this->assertSame( $extra_class . $admin_class, Accredible_Learndash_Admin_Scripts::add_admin_body_class( $extra_class ) );
	}

	/**
	 * Test if it doesn't add the admin class if not in plugin page.
	 */
	public function test_should_not_add_admin_body_class() {
		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );

		// Navigate to dashboard page.
		set_current_screen( 'dashboard' );

		$extra_class = 'extra-class';

		$this->assertSame( '', Accredible_Learndash_Admin_Scripts::add_admin_body_class() );
		$this->assertSame( $extra_class, Accredible_Learndash_Admin_Scripts::add_admin_body_class( $extra_class ) );
	}

	/**
	 * Test if it doesn't add the admin class if not in admin view.
	 */
	public function test_should_not_add_admin_body_class_if_not_in_admin() {
		// Navigate to dashboard page.
		set_current_screen( 'dashboard' );

		$extra_class = 'extra-class';

		$this->assertSame( '', Accredible_Learndash_Admin_Scripts::add_admin_body_class() );
		$this->assertSame( $extra_class, Accredible_Learndash_Admin_Scripts::add_admin_body_class( $extra_class ) );
	}
}
