<?php
/**
 * Accredible LearnDash Add-on admin issuance list class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Issuance_List' ) ) :
	/**
	 * Accredible LearnDash Add-on admin issuance list class
	 */
	class Accredible_Learndash_Admin_Issuance_List {
		/**
		 * Issuances mock data.
		 *
		 * @var array
		 */
		public static $issuances = array(
			array(
				'post_id'               => 1,
				'accredible_group_name' => 'Course 100 Series',
				'kind'                  => 'course_completed',
				'created_at'            => '2022-02-17 13:09:00',
			),
			array(
				'post_id'               => 8,
				'accredible_group_name' => 'Course 100 Series',
				'kind'                  => 'course_completed',
				'created_at'            => '2022-01-10 11:45:00',
			),
			array(
				'post_id'               => 12,
				'accredible_group_name' => 'Course 100 Series',
				'kind'                  => 'course_completed',
				'created_at'            => '2022-01-08 10:07:00',
			),
			array(
				'post_id'               => 3,
				'accredible_group_name' => 'Course 200 Series',
				'kind'                  => 'course_completed',
				'created_at'            => '2021-12-01 09:31:00',
			),
		);
	}
endif;

