<?php
/**
 * Accredible LearnDash Add-on API v1 client class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-api-v1-request.php';

if ( ! class_exists( 'Accredible_Learndash_Api_V1_Client' ) ) :
	/**
	 * Accredible LearnDash Add-on API v1 client class
	 */
	class Accredible_Learndash_Api_V1_Client {
		/**
		 * The HTTP request object used to make HTTP requests.
		 *
		 * @var Accredible_Learndash_Api_V1_Request $request Make HTTP requests.
		 */
		private $request;

		/**
		 * Accredible_Learndash_Api_V1 constructor
		 *
		 * @param Accredible_Learndash_Api_V1_Request $request Pass a mock request when unit testing.
		 */
		public function __construct( $request = null ) {
			$server_region = get_option( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION );
			if ( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU === $server_region ) {
				$base_url = 'https://eu.api.accredible.com/v1';
			} else {
				$base_url = 'https://api.accredible.com/v1';
			}
			$api_key = get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY );

			// A mock request is passed when unit testing.
			if ( $request ) {
				$this->request = $request;
			} else {
				$this->request = new Accredible_Learndash_Api_V1_Request( $base_url, $api_key );
			}
		}

		/**
		 * Accredible_Learndash_Api_V1 constructor
		 *
		 * @param int    $group_id Accredible Group ID.
		 * @param string $recipient_name Recipient name.
		 * @param string $recipient_email Recipient email.
		 * @param array  $custom_attributes Custom attributes.
		 */
		public function create_credential( $group_id, $recipient_name, $recipient_email, $custom_attributes = null ) {
			$body = array(
				'credential' => array(
					'group_id'          => $group_id,
					'recipient'         => array(
						'name'  => $recipient_name,
						'email' => strtolower( $recipient_email ),
					),
					'custom_attributes' => $custom_attributes,
				),
			);
			return $this->request->post( '/credentials', $body );
		}
	}
endif;
