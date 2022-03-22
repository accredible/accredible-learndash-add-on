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
		 * Get available courses.
		 *
		 * @param string $post_type post_type to filter options.
		 *
		 * @return array
		 */
		public static function get_course_options( $post_type = 'sfwd-courses' ) {
			$args    = array(
				'post_type' => $post_type,
			);
			$courses = array();
			$posts   = get_posts( $args );

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $value ) {
					$course_id             = get_post_field( 'ID', $value );
					$course_name           = get_the_title( $value );
					$courses[ $course_id ] = $course_name;
				}
			}

			return $courses;
		}

		/**
		 * Get group options.
		 *
		 * @return array
		 */
		public static function get_group_options() {
			$groups     = array();
			$api_client = new Accredible_Learndash_Api_V1_Client();
			$response   = $api_client->get_groups();

			if ( ! isset( $response['errors'] ) ) {
				foreach ( $response['groups'] as $value ) {
					$groups[ $value['id'] ] = $value['name'];
				}
			}

			return $groups;
		}
	}
endif;
