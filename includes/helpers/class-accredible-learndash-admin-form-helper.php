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
		 * @param array $value value to pass to attribute.
		 */
		public static function value_attr( $value ) {
			if ( isset( $value ) ) {
				echo sprintf(
					'value="%s"',
					esc_attr( $value )
				);
			}
		}
	}
endif;
