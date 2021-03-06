<?php
/**
 * Class Accredible_Learndash_Admin_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/ajax/class-accredible-learndash-ajax.php';

/**
 * Unit tests for Accredible_Learndash_Admin
 */
class Accredible_Learndash_Admin_Test extends WP_UnitTestCase {
	/**
	 * Test if it adds admin WP hooks.
	 */
	public function test_init() {
		$activation_hook_name   = 'activate_' . ACCREDILBE_LEARNDASH_PLUGIN_BASENAME;
		$action_links_hook_name = 'plugin_action_links_' . ACCREDILBE_LEARNDASH_PLUGIN_BASENAME;
		// Reset related WP filters.
		remove_all_filters( 'admin_init' );
		remove_all_filters( 'admin_menu' );
		remove_all_filters( 'admin_enqueue_scripts' );
		remove_all_filters( 'admin_body_class' );
		remove_all_filters( 'wp_ajax_accredible_learndash_ajax_search_groups' );
		remove_all_filters( $activation_hook_name );
		remove_all_filters( $action_links_hook_name );

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
			has_filter( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_resources' ) )
		);
		$this->assertEquals(
			10,
			has_filter( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_page_ajax' ) )
		);
		$this->assertEquals(
			10,
			has_filter( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_page_scripts' ) )
		);
		$this->assertEquals(
			10,
			has_filter( 'admin_body_class', array( 'Accredible_Learndash_Admin_Scripts', 'add_admin_body_class' ) )
		);
		$this->assertEquals(
			10,
			has_filter(
				'wp_ajax_accredible_learndash_ajax_search_groups',
				array( 'Accredible_Learndash_Ajax', 'search_groups' )
			)
		);
		$this->assertEquals(
			10,
			has_filter(
				'wp_ajax_accredible_learndash_ajax_load_issuer_html',
				array( 'Accredible_Learndash_Ajax', 'load_issuer_html' )
			)
		);
		$this->assertEquals(
			10,
			has_filter(
				'wp_ajax_accredible_learndash_ajax_load_auto_issuance_list_html',
				array( 'Accredible_Learndash_Ajax', 'load_auto_issuance_list_html' )
			)
		);
		$this->assertEquals(
			10,
			has_filter(
				$action_links_hook_name,
				array( 'Accredible_Learndash_Admin_Menu', 'add_action_links' )
			)
		);
	}
}
