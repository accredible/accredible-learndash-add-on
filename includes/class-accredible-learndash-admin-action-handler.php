<?php
/**
 * Server-side actions on the admin panel.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Action_Handler' ) ) :
	/**
	 * Accredible LearnDash Add-on admin action handler class
	 */
	class Accredible_Learndash_Admin_Action_Handler {
		/**
		 * Register plugin options to WP options
		 *
		 * @param string $data Data for the action.
		 */
		public static function delete_auto_issuance( $data ) {
			self::verify_nonce( $data['nonce'], 'delete-auto-issuance-' . $data['id'] );
			// TODO: delete action.
			self::redirect_to();
		}

		/**
		 * Verify WP nonce for the action.
		 *
		 * @param string $nonce Nonce value that was used for verification.
		 * @param string $action Should give context to what is taking place and be the same when nonce was created.
		 */
		private static function verify_nonce( $nonce, $action ) {
			if ( ! ( isset( $nonce ) && wp_verify_nonce( $nonce, $action ) ) ) {
				wp_die( 'Invalid nonce.' );
			};
		}

		/**
		 * Redirect to the page.
		 *
		 * @param string $redirect_url Redirect URL.
		 */
		private static function redirect_to( $redirect_url = null ) {
			if ( empty( $redirect_url ) ) {
				$redirect_url = wp_get_referer();
			}
			// You cannot use `wp_redirect` at the `admin_menu` Action Hook callback
			// since http headers were already sent to the browser.
			echo '<p>Processing...</p>';
			print( "<script>window.location.href='" . esc_html( $redirect_url ) . "'</script>" );
		}
	}
endif;
