<?php
/**
 * Accredible LearnDash Add-on model class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Model' ) ) :
	/**
	 * Accredible LearnDash Add-on model class
	 */
	abstract class Accredible_Learndash_Model {
		/**
		 * Return a list of DB records.
		 *
		 * @param string $where SQL where clause.
		 */
		public static function get_results( $where = '' ) {
			global $wpdb;
			$sql = 'SELECT * FROM ' . static::table_name();
			if ( ! empty( $where ) ) {
				$sql .= " WHERE $where";
			}
			return $wpdb->get_results(
				$wpdb->prepare( '%1s;', $sql ) // phpcs:disable WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
			);
		}

		/**
		 * Insert a new record to the DB table.
		 *
		 * @param array $data Inserting data.
		 */
		public static function insert( $data ) {
			$data['created_at'] = time();
			global $wpdb;
			return $wpdb->insert( static::table_name(), $data );
		}

		/**
		 * Define the DB table name in the sub class.
		 */
		abstract protected static function table_name();
	}
endif;
