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
		 * @param int    $limit Limit value.
		 * @param int    $offset Offset value.
		 */
		public static function get_results( $where_sql = '', $limit = null, $offset = null ) {
			global $wpdb;
			$sql = 'SELECT * FROM ' . static::table_name();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
			}
			if ( ! empty( $limit ) ) {
				$sql .= " LIMIT $limit";
			}
			if ( ! empty( $offset ) ) {
				$sql .= " OFFSET $offset";
			}
			// XXX `$where_sql` is a raw SQL so `$wpdb->prepare` cannot be used.
			return $wpdb->get_results( $sql ); // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		}

		/**
		 * Return the total count of the records.
		 *
		 * @param string $where_sql SQL where clause.
		 */
		public static function get_total_count( $where_sql = '' ) {
			global $wpdb;
			$sql = 'SELECT COUNT(*) FROM ' . static::table_name();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
			}
			// XXX `$where_sql` is a raw SQL so `$wpdb->prepare` cannot be used.
			$wpdb->get_results( $sql ); // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			return $wpdb->num_rows;
		}

		/**
		 * Return paginated results.
		 *
		 * @param int    $page_num The current page number.
		 * @param int    $page_size Page size.
		 * @param string $where_sql SQL where clause.
		 */
		public static function get_paginated_results( $page_num, $page_size = 50, $where_sql = '' ) {
			$current_page = empty( $page_num ) ? 1 : $page_num;
			$offset       = $page_size * ( $current_page - 1 );
			$results      = static::get_results( $where_sql, $page_size, $offset );
			$total_count  = static::get_total_count( $where_sql );
			$total_pages  = $total_count % $page_size;
			$next_page    = $current_page < $total_pages ? $current_page + 1 : null;
			$prev_page    = $current_page > 1 ? $current_page - 1 : null;

			return array(
				'results'      => $results,
				'current_page' => $current_page,
				'next_page'    => $next_page,
				'prev_page'    => $prev_page,
				'total_pages'  => $total_pages,
				'total_count'  => $total_count,
				'page_size'    => $page_size,
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
