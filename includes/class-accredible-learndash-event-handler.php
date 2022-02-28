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
			$api_key = get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY );
			if ( empty( $api_key ) ) {
				return 0;
			}

			$course_id      = $data['course']->ID;
			$where_sql      = "kind = 'course_completed' AND post_id = $course_id";
			$auto_issuances = Accredible_Learndash_Model_Auto_Issuance::get_results( $where_sql );
			if ( empty( $auto_issuances ) ) {
				return 0;
			}

			$user            = $data['user'];
			$recipient_email = $user->user_email;
			$recipient_name  = self::get_recipient_name( $user );

			foreach ( $auto_issuances as $auto_issuance ) {
				self::create_credential( $auto_issuance, $user->ID, $recipient_name, $recipient_email );
			}
			return count( $auto_issuances );
		}

		/**
		 * Return the recipient name for credential issuance.
		 *
		 * @param object $user User object.
		 */
		private static function get_recipient_name( $user ) {
			$user_names     = array_filter(
				array(
					get_user_meta( $user->ID, 'first_name', true ),
					get_user_meta( $user->ID, 'last_name', true ),
				)
			);
			$recipient_name = join( ' ', $user_names );
			if ( empty( $recipient_name ) ) {
				$recipient_name = $user->display_name;
			}
			return $recipient_name;
		}

		/**
		 * Create a credential as auto-issuance.
		 *
		 * @param object $auto_issuance An auto issuance record.
		 * @param int    $user_id User ID.
		 * @param string $recipient_name Recipient's name.
		 * @param string $recipient_email Recipient's email.
		 */
		private static function create_credential( $auto_issuance, $user_id, $recipient_name, $recipient_email ) {
			$client = new Accredible_Learndash_Api_V1_Client();
			$res    = $client->create_credential(
				$auto_issuance->accredible_group_id,
				$recipient_name,
				$recipient_email
			);

			// Create an AutoIssuanceLog.
			$auto_issuance_id_name = Accredible_Learndash_Admin_Database::PLUGIN_PREFIX . 'auto_issuance_id';
			$auto_issuance_log     = array(
				$auto_issuance_id_name => $auto_issuance->id,
				'user_id'              => $user_id,
				'accredible_group_id'  => $auto_issuance->accredible_group_id,
				'recipient_name'       => $recipient_name,
				'recipient_email'      => $recipient_email,
			);
			if ( isset( $res['errors'] ) ) {
				$auto_issuance_log['error_message'] =
					is_string( $res['errors'] ) ? $res['errors'] : $res['errors']['credential'][0];
			} else {
				$auto_issuance_log['accredible_group_name'] = $res['credential']['group_name'];
				$auto_issuance_log['credential_url']        = $res['credential']['url'];
			}
			Accredible_Learndash_Model_Auto_Issuance_Log::insert( $auto_issuance_log );
		}
	}
endif;
