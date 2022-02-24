<?php
/**
 * Accredible LearnDash Add-on Event Handler class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-database.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __FILE__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';
require_once plugin_dir_path( __FILE__ ) . '/models/class-accredible-learndash-model-auto-issuance-log.php';
require_once plugin_dir_path( __FILE__ ) . '/rest-api/v1/class-accredible-learndash-api-v1-client.php';

if ( ! class_exists( 'Accredible_Learndash_Event_Handler' ) ) :
	/**
	 * Accredible LearnDash Add-on Event Handler class
	 */
	class Accredible_Learndash_Event_Handler {
		/**
		 * Handle `learndash_course_completed` Action Hooks
		 *
		 * @param Array $data course data.
		 */
		public static function handle_course_completed( $data ) {
			$course_id       = $data['course']->id;
			$user            = $data['user'];
			$recipient_email = $user->email;
			$user_names      = array_filter(
				array(
					get_user_meta( $user->id, 'first_name', true ),
					get_user_meta( $user->id, 'last_name', true ),
				)
			);
			$recipient_name  = join( ' ', $user_names );
			if ( empty( $recipient_name ) ) {
				$recipient_name = $user->display_name;
			}

			$where   = "kind = 'course_completed' AND post_id = 1";
			$results = Accredible_Learndash_Model_Auto_Issuance::get_results( $where );
			if ( empty( $results ) ) {
				return;
			}

			$api_key = get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY );
			if ( false === $api_key ) {
				return;
			}
			$client = new Accredible_Learndash_Api_V1_Client();
			foreach ( $results as $auto_issuance ) {
				$res = $client->create_credential(
					$auto_issuance->accredible_group_id,
					$recipient_name,
					$recipient_email
				);

				$auto_issuance_id_name = Accredible_Learndash_Admin_Database::PLUGIN_PREFIX . 'auto_issuance_id';
				$auto_issuance_log     = array(
					$auto_issuance_id_name => $auto_issuance->id,
					'user_id'              => $user->id,
					'accredible_group_id'  => $auto_issuance->accredible_group_id,
					'recipient_name'       => $recipient_name,
					'recipient_email'      => $recipient_email,
				);
				if ( empty( $res ) ) {
					$auto_issuance_log['error_message'] = 'The server could not respond. Your API key might be invalid.';
				} elseif ( $res->errors ) {
					$auto_issuance_log['error_message'] =
						is_string( $res->errors ) ? $res->errors : $res->errors->credential[0];
				} else {
					$auto_issuance_log['accredible_group_name'] = $res->credential->group_name;
					$auto_issuance_log['credential_url']        = $res->credential->url;
				}
				Accredible_Learndash_Model_Auto_Issuance_Log::insert( $auto_issuance_log );
			}
		}
	}
endif;
