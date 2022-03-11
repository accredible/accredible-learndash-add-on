<?php
/**
 * Accredible LearnDash Add-on admin menu class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-action-handler.php';

if ( ! class_exists( 'Accredible_Learndash_Admin_Menu' ) ) :
	/**
	 * Accredible LearnDash Add-on admin menu class
	 */
	class Accredible_Learndash_Admin_Menu {
		/**
		 * Add plugin pages to wp menu
		 */
		public static function add() {
			add_menu_page(
				'Accredible LearnDash Add-on',
				'Accredible LearnDash Add-on',
				'administrator',
				'accredible_learndash',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_auto_issuances_index_page' ),
				'dashicons-awards'
			);

			add_submenu_page(
				'accredible_learndash',
				'Settings',
				'Settings',
				'administrator',
				'accredible_learndash_settings',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_settings_page' )
			);

			add_submenu_page(
				null,
				'Server Auto Issuance',
				'Server Auto Issuance',
				'administrator',
				'accredible_learndash_admin_action',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_action' )
			);
		}

		/**
		 * Render admin auto issuances index page
		 */
		public static function admin_auto_issuances_index_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-auto-issuances-index.php';
		}

		/**
		 * Render admin settings page
		 */
		public static function admin_settings_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-settings.php';
		}

		/**
		 * Render admin settings page
		 */
		public static function admin_action() {
			$action        = isset( $_REQUEST['action'] ) ? esc_attr( wp_unslash( $_REQUEST['action'] ) ) : null; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			$class_methods = get_class_methods( 'Accredible_Learndash_Admin_Action_Handler' );
			if ( in_array( $action, $class_methods, true ) ) {
				// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
				$data = array(
					'id'    => isset( $_REQUEST['id'] ) ? esc_attr( wp_unslash( $_REQUEST['id'] ) ) : null,
					'nonce' => isset( $_REQUEST['_mynonce'] ) ? esc_attr( wp_unslash( $_REQUEST['_mynonce'] ) ) : null,
				);
				// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
				Accredible_Learndash_Admin_Action_Handler::$action( $data );
			} else {
				wp_die( 'An action type mismatch has been detected.' );
			}
		}
	}
endif;
