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
	 * Test if it enqueues styles and scripts.
	 */
	public function test_load_resources() {
		// Reset related WP styles.
		wp_dequeue_style( 'accredible-learndash-admin-theme' );
		wp_dequeue_style( 'accredible-learndash-admin-settings' );
		// Reset related WP scripts.
		wp_dequeue_script( 'jquery' );
		wp_dequeue_script( 'jquery-ui-autocomplete' );

		Accredible_Learndash_Admin_Scripts::load_resources();

		global $wp_styles, $wp_scripts;
		$this->assertNotNull( $wp_styles->registered['accredible-learndash-admin-theme'] );
		$this->assertNotNull( $wp_styles->registered['accredible-learndash-admin-settings'] );
		$this->assertNotNull( $wp_scripts->registered['jquery'] );
		$this->assertNotNull( $wp_scripts->registered['jquery-ui-autocomplete'] );
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

	/**
	 * Test if it enqueues page ajax scripts.
	 */
	public function test_load_page_ajax() {
		// Reset related WP scripts.
		wp_dequeue_script( 'accredible-learndash-groups-autocomplete' );

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );

		// Navigate to auto_issuance form page.
		$this->go_to( admin_url( 'admin.php?page=accredible_learndash_auto_issuance' ) );
		set_current_screen( 'toplevel_page_accredible_learndash_auto_issuance' );

		Accredible_Learndash_Admin_Scripts::load_page_ajax();

		global $wp_scripts;
		$this->assertNotNull( $wp_scripts->registered['accredible-learndash-groups-autocomplete'] );
	}

	/**
	 * Test if it doesn't load page ajax scripts.
	 */
	public function test_should_not_load_page_ajax() {
		// Reset related WP scripts.
		wp_deregister_script( 'accredible-learndash-groups-autocomplete' );

		// Navigate to dashboard page.
		set_current_screen( 'dashboard' );

		Accredible_Learndash_Admin_Scripts::load_page_ajax();

		global $wp_scripts;
		$this->assertTrue( empty( $wp_scripts->registered['accredible-learndash-groups-autocomplete'] ) );
	}
}
