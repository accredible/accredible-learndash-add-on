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
		public static function get_courses( $post_type ) {
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
	}
endif;
