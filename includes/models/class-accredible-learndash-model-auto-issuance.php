<?php
/**
 * Accredible LearnDash Add-on auto issuance model class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-model.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';

if ( ! class_exists( 'Accredible_Learndash_Model_Auto_Issuance' ) ) :
	/**
	 * Accredible LearnDash Add-on auto issuance model class
	 */
	class Accredible_Learndash_Model_Auto_Issuance extends Accredible_Learndash_Model {
		/**
		 * Define the DB table name.
		 */
		protected static function table_name() {
			global $wpdb;
			return $wpdb->prefix . Accredible_Learndash_Admin_Database::AUTO_ISSUANCES_TABLE_NAME;
		}

		/**
		 * Get group options. This method is only called via ajax.
		 *
		 * @return array
		 */
		public static function search_groups_ajax() {
			$groups   = array();
			$response = array();

			if ( isset( $_REQUEST['search_term'] ) && ! empty( $_REQUEST['search_term'] ) ) {
				$search_term = $_REQUEST['search_term'];
				$api_client  = new Accredible_Learndash_Api_V1_Client();
				$response    = $api_client->get_groups( $search_term );
			}

			if ( ! isset( $response['errors'] ) ) {
				foreach ( $response['groups'] as $value ) {
					array_push(
						$groups,
						array( 
							'value' => $value['id'],
							'label' => $value['name'],
						)
					);
				}

				wp_send_json_success( $groups );
			} else {
				wp_send_json_error();
			}

			wp_die();
		}
	}
endif;
