<?php
/**
 * Accredible LearnDash Add-on admin menu class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Menu' ) ) :
	/**
	 * Accredible LearnDash Add-on admin menu class
	 */
	class Accredible_Learndash_Admin_Menu {
		/**
		 * Add plugin pages to wp menu
		 */
		public static function add() {
			$menu_position = 3; // Show our plugin just below LearnDash.

			add_menu_page(
				'Accredible LearnDash Add-on',
				'Accredible LearnDash Add-on',
				'administrator',
				'accredible_learndash',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_settings_page' ),
				'dashicons-awards',
				$menu_position
			);

			add_submenu_page(
				'accredible_learndash',
				'Settings',
				'Settings',
				'administrator',
				'accredible_learndash_settings',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_settings_page' )
			);
		}

		/**
		 * Render admin settings page
		 */
		public static function admin_settings_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-settings.php';
		}
	}
endif;
