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
		const DEFAULT_PAGE_SIZE = 50;
		const REQUIRED_FIELDS   = array();
		const ORDER_BY_OPTIONS  = array( 'id ASC', 'id DESC' );

		/**
		 * Return a list of DB records.
		 *
		 * @param string $where_sql SQL where clause with %d / %s placeholders.
		 * @param array  $where_args Values for the placeholders in `$where_sql`.
		 * @param int    $limit Limit value.
		 * @param int    $offset Offset value.
		 * @param array  $options SQL options.
		 */
		public static function get_results( $where_sql = '', $where_args = array(), $limit = null, $offset = null, $options = array() ) {
			global $wpdb;
			$sql = self::build_query( 'SELECT *', $where_sql, $where_args, $limit, $offset, $options );
			return $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- prepared in build_query.
		}

		/**
		 * Return a DB record.
		 *
		 * @param string $where_sql SQL where clause with %d / %s placeholders.
		 * @param array  $where_args Values for the placeholders in `$where_sql`.
		 */
		public static function get_row( $where_sql = '', $where_args = array() ) {
			global $wpdb;
			$sql = self::build_query( 'SELECT *', $where_sql, $where_args );
			return $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- prepared in build_query.
		}

		/**
		 * Return the total count of the records.
		 *
		 * @param string $where_sql SQL where clause with %d / %s placeholders.
		 * @param array  $where_args Values for the placeholders in `$where_sql`.
		 */
		public static function get_total_count( $where_sql = '', $where_args = array() ) {
			global $wpdb;
			$sql = self::build_query( 'SELECT COUNT(*)', $where_sql, $where_args );
			return $wpdb->get_var( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- prepared in build_query.
		}

		/**
		 * Return paginated results.
		 *
		 * @param int    $page_num The current page number.
		 * @param int    $page_size Page size.
		 * @param string $where_sql SQL where clause with %d / %s placeholders.
		 * @param array  $where_args Values for the placeholders in `$where_sql`.
		 * @param array  $options SQL options.
		 */
		public static function get_paginated_results( $page_num, $page_size, $where_sql = '', $where_args = array(), $options = array() ) {
			if ( empty( $page_size ) ) {
				$page_size = static::DEFAULT_PAGE_SIZE;
			}
			$current_page = max( 1, absint( $page_num ) );
			$offset       = $page_size * ( $current_page - 1 );

			$results     = static::get_results( $where_sql, $where_args, $page_size, $offset, $options );
			$total_count = static::get_total_count( $where_sql, $where_args );
			$total_pages = ceil( $total_count / $page_size );
			$next_page   = $current_page < $total_pages ? $current_page + 1 : null;
			$prev_page   = $current_page > 1 ? $current_page - 1 : null;

			return array(
				'results' => $results,
				'meta'    => array(
					'current_page' => $current_page,
					'next_page'    => $next_page,
					'prev_page'    => $prev_page,
					'total_pages'  => $total_pages,
					'total_count'  => $total_count,
					'page_size'    => $page_size,
				),
			);
		}

		/**
		 * Build a prepared SELECT query.
		 *
		 * @throws Exception Exception when the order_by option is not allowed.
		 *
		 * @param string $select SELECT expression.
		 * @param string $where_sql SQL where clause with %d / %s placeholders.
		 * @param array  $where_args Values for the placeholders in `$where_sql`.
		 * @param int    $limit Limit value.
		 * @param int    $offset Offset value.
		 * @param array  $options SQL options.
		 */
		private static function build_query( $select, $where_sql, $where_args, $limit = null, $offset = null, $options = array() ) {
			global $wpdb;
			$sql  = $select . ' FROM ' . static::table_name();
			$args = array();
			if ( ! empty( $where_sql ) ) {
				$sql .= " WHERE $where_sql";
				$args = $where_args;
			}
			if ( isset( $options['order_by'] ) ) {
				if ( ! in_array( $options['order_by'], self::ORDER_BY_OPTIONS, true ) ) {
					throw new Exception( esc_attr( $options['order_by'] ) . ' is an invalid order_by option.' );
				}
				$sql .= ' ORDER BY ' . $options['order_by'];
			}
			if ( ! empty( $limit ) ) {
				$sql   .= ' LIMIT %d';
				$args[] = $limit;
			}
			if ( ! empty( $offset ) ) {
				$sql   .= ' OFFSET %d';
				$args[] = $offset;
			}
			if ( empty( $args ) ) {
				return $sql;
			}
			return $wpdb->prepare( $sql, $args ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- the only interpolated parts are the table name, the allowlisted ORDER BY and placeholders.
		}

		/**
		 * Insert a new record to the DB table.
		 *
		 * @param array $data Inserting data.
		 */
		public static function insert( $data ) {
			$data['created_at'] = time();
			static::validate( $data );
			global $wpdb;
			$wpdb->insert( static::table_name(), $data );
			$insert_id = $wpdb->insert_id;
			return $insert_id;
		}

		/**
		 * Update a record from DB table.
		 *
		 * @param int   $id ID of model to update.
		 * @param array $data Updating data.
		 */
		public static function update( $id, $data ) {
			static::validate( $data, $id );
			global $wpdb;
			return $wpdb->update( static::table_name(), $data, array( 'id' => $id ) );
		}

		/**
		 * Delete a record from the DB table.
		 *
		 * @param int $id ID.
		 */
		public static function delete( $id ) {
			global $wpdb;
			return $wpdb->delete( static::table_name(), array( 'id' => $id ), array( '%d' ) );
		}

		/**
		 * Validate inserting or updating data.
		 *
		 * @throws Exception Exception containing the validation error message.
		 *
		 * @param array $data Inserting or updating data.
		 * @param int   $id ID of the record.
		 */
		public static function validate( $data, $id = null ) {
			foreach ( static::REQUIRED_FIELDS as $field ) {
				if ( null === $id ) {
					if ( ! isset( $data[ $field ] ) || '' === trim( $data[ $field ] ) ) {
						throw new Exception( esc_attr( $field ) . ' is a required field.' );
					}
				} elseif ( array_key_exists( $field, $data ) && ( null === $data[ $field ] || '' === trim( $data[ $field ] ) ) ) {
						throw new Exception( esc_attr( $field ) . ' is a required field.' );
				}
			}
		}

		/**
		 * Define the DB table name in the sub class.
		 */
		abstract protected static function table_name();
	}
endif;
