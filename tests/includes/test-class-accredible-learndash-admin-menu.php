<?php
/**
 * Class Accredible_Learndash_Admin_Menu_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-menu.php';

/**
 * Unit tests for Accredible_Learndash_Admin_Menu
 */
class Accredible_Learndash_Admin_Menu_Test extends WP_UnitTestCase {
	/**
	 * Test if it adds admin page and admin sub pages.
	 */
	public function test_add() {
		global $submenu, $menu;
		// Reset menus.
		$submenu = array();
		$menu    = array();

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		Accredible_Learndash_Admin_Menu::add();

		$main_menu_slug = 'accredible_learndash';
		$this->assertSame( $main_menu_slug, $submenu[ $main_menu_slug ][0][2] );
		$this->assertSame( 'accredible_learndash_issuance_list', $submenu[ $main_menu_slug ][1][2] );
		$this->assertSame( 'accredible_learndash_issuance_log', $submenu[ $main_menu_slug ][2][2] );
		$this->assertSame( 'accredible_learndash_settings', $submenu[ $main_menu_slug ][3][2] );

		$this->assertSame( 'accredible_learndash_admin_action', $submenu[''][0][2] );
	}

	/**
	 * Test if it adds the plugin custom links.
	 */
	public function test_add_action_links() {
		$links  = array( "<a href='https://example.com'>Link 1</a>" );
		$result = Accredible_Learndash_Admin_Menu::add_action_links( $links );

		$this->assertSame(
			array(
				'<a href="' . admin_url( 'admin.php?page=accredible_learndash_settings' ) . '">Settings</a>',
				"<a href='https://example.com'>Link 1</a>",
			),
			$result
		);
	}
}
