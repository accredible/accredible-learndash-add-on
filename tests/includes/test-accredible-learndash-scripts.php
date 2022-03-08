<?php
/**
 * Class Accredible_Learndash_Scripts_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/accredible-learndash-scripts.php';

/**
 * Unit tests for Accredible_Learndash_Scripts
 */
class Accredible_Learndash_Scripts_Test extends WP_UnitTestCase {
	/**
	 * Test if it loads styles.
	 */
	public function test_accredible_learndash_load_resources() {
		// Reset related WP styles.
		wp_dequeue_style( 'accredible-learndash-admin-theme' );
		wp_dequeue_style( 'accredible-learndash-admin-settings' );

		accredible_learndash_load_resources();

		global $wp_styles;
		$this->assertNotNull( $wp_styles->registered['accredible-learndash-admin-theme'] );
		$this->assertNotNull( $wp_styles->registered['accredible-learndash-admin-settings'] );
	}

	/**
	 * Test if it adds 'accredible_learndash' class to body.
	 */
	public function test_accredible_learndash_admin_body_class() {
		// Reset admin_body_class filter.
		remove_filter( 'admin_body_class', 10 );

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );

		// Navigate to plugin page.
		$this->go_to( admin_url( 'admin.php?page=accredible_learndash' ) );
		set_current_screen( 'toplevel_page_accredible_learndash' );

		$classes = accredible_learndash_admin_body_class();
		$value   = ' accredible-learndash-admin ';

		$this->assertEquals( 10, has_filter( 'admin_body_class', 'accredible_learndash_admin_body_class' ) );
		$this->assertSame( $value, $classes ); // TODO - consider using get_body_class().
	}
}
