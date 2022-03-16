<?php
/**
 * Server-side actions on the admin panel.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . 'models/class-accredible-learndash-model-auto-issuance.php';

if ( ! class_exists( 'Accredible_Learndash_Admin_Action_Handler' ) ) :
	/**
	 * Accredible LearnDash Add-on admin action handler class
	 */
	class Accredible_Learndash_Admin_Action_Handler {
		/**
		 * Delete an auto issuance.
		 *
		 * @param string $data Data for the action.
		 */
		public static function delete_auto_issuance( $data ) {
			self::verify_nonce( $data['nonce'], 'delete_auto_issuance' . $data['id'] );
			$result = Accredible_Learndash_Model_Auto_Issuance::delete( $data['id'] );
			if ( false === $result ) {
				wp_die( 'Failed to delete.' );
			} else {
				self::redirect_to( $data['redirect_url'] );
			}
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
		private static function redirect_to( $redirect_url ) {
			// You cannot use `wp_redirect` at the `admin_menu` Action Hook callback
			// since http headers were already sent to the browser.
			echo '<p>Processing...</p>';
			print( "<script>window.location.href='" . esc_html( $redirect_url ) . "'</script>" );
		}
	}
endif;
