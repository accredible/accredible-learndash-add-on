<?php
/**
 * Accredible LearnDash Add-on admin menu class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Issuer' ) ) :
	/**
	 * Accredible LearnDash Add-on admin menu class
	 */
	class Accredible_Learndash_Admin_Issuer {
		/**
		 * Search for the Accredible Issuer who owns the API key
		 */
		public static function search_issuer() {
			$client = new Accredible_Learndash_Api_V1_Client();
			$res    = $client->organization_search();

			if ( isset( $res['errors'] ) ) {
				return;
			} else {
				return $res['issuer'];
			}
		}
	}
endif;
