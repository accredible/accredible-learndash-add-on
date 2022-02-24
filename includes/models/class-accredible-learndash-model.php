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
		 * @param string $where_sql SQL where clause.
		 */
		public static function get_results( $where_sql = '' ) {
			global $wpdb;
			$sql = 'SELECT * FROM ' . static::table_name();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
			}
			// XXX `$where_sql` is a raw SQL so `$wpdb->prepare` cannot be used.
			return $wpdb->get_results( $sql ); // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
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
