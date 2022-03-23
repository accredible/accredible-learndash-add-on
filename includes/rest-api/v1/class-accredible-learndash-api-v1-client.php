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
		 * Accredible_Learndash_Api_V1_Client constructor
		 */
		public function __construct() {
			$server_region = get_option( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION );
			if ( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU === $server_region ) {
				$base_url = 'https://eu.api.accredible.com/v1';
			} else {
				$base_url = 'https://api.accredible.com/v1';
			}
			$local_url = getenv( 'ACCREDIBLE_LEARNDASH_API_ENDPOINT' );
			if ( $local_url ) {
				$base_url = $local_url;
			}

			$api_key = get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY );

			$this->request = new Accredible_Learndash_Api_V1_Request( $base_url, $api_key );
		}

		/**
		 * Search the organization with the provided API Key.
		 */
		public function organization_search() {
			return $this->request->get( '/issuer/details' );
		}

		/**
		 * Get groups.
		 *
		 * @param string $group_name group name used for search.
		 *
		 * @return array
		 */
		public function get_groups( $group_name ) {
			$body = array(
				'name'      => $group_name,
				'page_size' => 10,
			);
			return $this->request->post( '/issuer/groups/search', $body );
		}

		/**
		 * Issue a credential.
		 *
		 * @param int    $group_id Accredible Group ID.
		 * @param string $recipient_name Recipient name.
		 * @param string $recipient_email Recipient email.
		 * @param string $post_id LearnDash post ID.
		 * @param array  $custom_attributes Custom attributes.
		 */
		public function create_credential( $group_id, $recipient_name, $recipient_email, $post_id, $custom_attributes = null ) {
			$body = array(
				'credential' => array(
					'group_id'  => $group_id,
					'recipient' => array(
						'name'  => $recipient_name,
						'email' => strtolower( $recipient_email ),
					),
					'meta_data' => array(
						'learndash_post_id' => $post_id,
					),
				),
			);
			if ( $custom_attributes ) {
				$body['credential']['custom_attributes'] = $custom_attributes;
			}
			return $this->request->post( '/credentials', $body );
		}

		/**
		 * Fetch a group.
		 *
		 * @param int $group_id Accredible Group ID.
		 */
		public function get_group( $group_id ) {
			return $this->request->get( '/issuer/groups/' . $group_id );
		}
	}
endif;
