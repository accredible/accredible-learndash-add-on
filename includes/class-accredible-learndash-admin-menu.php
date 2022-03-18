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
				'Issuance List',
				'Issuance List',
				'administrator',
				'accredible_learndash_issuance_list',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_issuance_list_page' )
			);

			add_submenu_page(
				'accredible_learndash',
				'Configure Auto Issuance',
				'Auto Issuance',
				'administrator',
				'accredible_learndash_auto_issuance',
				array( 'Accredible_Learndash_Admin_Menu', 'auto_issuance_form_page' )
			);

			add_submenu_page(
				'accredible_learndash',
				'Issuance Logs',
				'Issuance Logs',
				'administrator',
				'accredible_learndash_issuance_log',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_issuance_logs_page' )
			);

			add_submenu_page(
				'accredible_learndash',
				'Settings',
				'Settings',
				'administrator',
				'accredible_learndash_settings',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_settings_page' )
			);

			// Admin action without a view template.
			add_submenu_page(
				null,
				'Admin Action',
				'Admin Action',
				'administrator',
				'accredible_learndash_admin_action',
				array( 'Accredible_Learndash_Admin_Menu', 'admin_action' )
			);
		}

		/**
		 * Render admin settings page
		 */
		public static function admin_settings_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-settings.php';
		}

		/**
		 * Render admin issuance list page
		 */
		public static function admin_issuance_list_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-issuance-list.php';
		}

		/**
		 * Render admin auto issuance logs page
		 */
		public static function admin_issuance_logs_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-auto-issuance-logs.php';
		}

		/**
		 * Render admin auto issuance form page
		 */
		public static function auto_issuance_form_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/admin-auto-issuance-form.php';
		}

		/**
		 * Render admin action page
		 */
		public static function admin_action() {
			$action        = isset( $_REQUEST['action'] ) ? esc_attr( wp_unslash( $_REQUEST['action'] ) ) : null; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			$class_methods = get_class_methods( 'Accredible_Learndash_Admin_Action_Handler' );
			if ( in_array( $action, $class_methods, true ) ) {
				// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
				$data = array(
					'id'           => isset( $_REQUEST['id'] ) ? esc_attr( wp_unslash( $_REQUEST['id'] ) ) : null,
					'nonce'        => isset( $_REQUEST['_mynonce'] ) ? esc_attr( wp_unslash( $_REQUEST['_mynonce'] ) ) : null,
					'redirect_url' => isset( $_REQUEST['redirect_url'] ) ? esc_attr( wp_unslash( $_REQUEST['redirect_url'] ) ) : wp_get_referer(),
				);
				// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
				Accredible_Learndash_Admin_Action_Handler::$action( $data );
			} else {
				wp_die( 'An action type mismatch has been detected.' );
			}
		}
	}
endif;
