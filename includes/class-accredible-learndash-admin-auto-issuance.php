<?php
/**
 * Accredible LearnDash Add-on admin auto issuance class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Auto_Issuance' ) ) :
	/**
	 * Accredible LearnDash Add-on admin auto issuance class
	 */
	class Accredible_Learndash_Admin_Auto_Issuance {
		/**
		 * Get available courses filter.
		 *
		 * @param string $post_type post_type to filter options.
		 *
		 * @return array
		 */
		public static function get_course_options( $post_type ) {
			$args    = array(
				'post_type' => empty( $post_type ) ? 'sfwd-courses' : $post_type,
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
		 * Get groups filter.
		 *
		 * @param array $args filter arguement.
		 *
		 * @return array
		 */
		public static function get_group_options( $args ) {
			$groups     = array();
			$api_client = new Accredible_Learndash_Api_V1_Client();
			$response   = $api_client->get_groups();

			if ( ! isset( $response['errors'] ) ) {
				foreach ( $response['groups'] as $value ) {
					$groups[ $value->id ] = $value->name;
				}
			}

			return $groups;
		}
	}
endif;
