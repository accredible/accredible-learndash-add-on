<?php
/**
 * Accredible LearnDash Add-on form table helper
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Form_Helper' ) ) :
	/**
	 * Accredible LearnDash Add-on admin form helper class
	 */
	class Accredible_Learndash_Admin_Form_Helper {
		/**
		 * Returns value attribute if value is present.
		 *
		 * @param array $obj contains value.
		 * @param array $key key to get value.
		 */
		public static function value_attr( $obj, $key ) {
			if ( ! empty( $obj ) && isset( $obj[ $key ] ) ) {
				echo sprintf(
					'value="%s"',
					esc_attr( $obj[ $key ] )
				);
			}
		}
	}
endif;
