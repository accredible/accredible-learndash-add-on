<?php
/**
 * Accredible LearnDash Add-on admin scripts class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;


if ( ! class_exists( 'Accredible_Learndash_Admin_Scripts' ) ) :
	/**
	 * Accredible LearnDash Add-on admin scripts class
	 */
	class Accredible_Learndash_Admin_Scripts {
		/**
		 * Enqueues styles for front-end.
		 */
		public static function load_resources() {
			wp_enqueue_style(
				'accredible-learndash-admin-theme',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/css/accredible-admin-theme.css',
				array(),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN
			);

			wp_enqueue_style(
				'accredible-learndash-admin-settings',
				ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/css/accredible-admin-settings.css',
				array(),
				ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN
			);
		}

		/**
		 * Admin body class Filter.
		 *
		 * @param string $classes Optional. The admin body CSS classes. Default empty.
		 *
		 * @return string Admin body CSS classes.
		 */
		public static function add_admin_body_class( $classes = '' ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( is_admin() && isset( $_GET['page'] ) && stripos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 'accredible_learndash' ) !== false ) {
				$classes .= ' accredible-learndash-admin ';
			}

			return $classes;
		}

	}

endif;
