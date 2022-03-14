<?php
/**
 * Accredible LearnDash Add-on admin table helper
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Accredible_Learndash_Admin_Table_Helper' ) ) :
	/**
	 * Accredible LearnDash Add-on admin table helper class
	 */
	class Accredible_Learndash_Admin_Table_Helper {
		const ISSUANCE_POST_ID      = 'post_id';
		const ISSUANCE_GROUP_ID     = 'accredible_group_id';
		const ISSUANCE_KIND         = 'kind';
		const ISSUANCE_DATE_CREATED = 'created_at';

		const DEFAULT_PAGE_SIZE = 50;

		/**
		 * Current results page.
		 *
		 * @var int $page.
		 */
		private static $current_page;

		/**
		 * Results page size.
		 *
		 * @var int $page_size.
		 */
		private static $page_size;

		/**
		 * Row actions.
		 *
		 * @var array $row_actions.
		 */
		private static $row_actions;

		/**
		 * Public constructor for class.
		 *
		 * @param int   $current_page Current page.
		 * @param int   $page_size Page size (optional).
		 * @param array $row_actions Row actions (optional). Defined as array( 'action' => 'edit', 'label' => 'Edit' ).
		 */
		public function __construct( $current_page, $page_size = self::DEFAULT_PAGE_SIZE, $row_actions = array() ) {
			self::$current_page = $current_page;
			self::$page_size    = $page_size;
			self::$row_actions  = $row_actions;
		}

		/**
		 * Build table rows.
		 *
		 * @param mixed $issuances issuances data.
		 *
		 * @return string
		 */
		public function build_table_rows( $issuances ) {
			$row_cells = '';
			foreach ( $issuances as $index => $issuance ) {
				$row_cells .= '<tr class="accredible-row">';
				$row_cells .= self::table_cell( self::eval_row_num( $index + 1 ) );
				$row_cells .= self::get_table_cells( $issuance );
				$row_cells .= self::table_cell( self::eval_actions( $issuance['post_id'] ), 'accredible-cell-actions' );
				$row_cells .= '</tr>';
			}

			return $row_cells;
		}

		/**
		 *
		 * Returns a table cell.
		 *
		 * @param array $issuance index used for table numbering.
		 *
		 * @return string
		 */
		private static function get_table_cells( $issuance ) {
			$table_cells = '';
			foreach ( $issuance as $key => $value ) {
				switch ( $key ) {
					case self::ISSUANCE_POST_ID:
						$course_name = get_the_title( $value );
						$value       = ! empty( $course_name ) ? $course_name : self::eval_error( 'Not found' );
						break;
					case self::ISSUANCE_GROUP_ID:
						$group_name = apply_filters( 'accredible_learndash_get_group', $value ); // TODO - create and register this filter.
						$value      = ! empty( $group_name ) ? $group_name : $value; // TODO - Update to show error.
						break;
					case self::ISSUANCE_KIND:
						$value = self::eval_kind( $value );
						break;
					case self::ISSUANCE_DATE_CREATED:
						$value = self::eval_date_time( $value );
						break;
					default:
						$value;
				}

				$table_cells .= '<td>' . $value . '</td>';
			}

			return $table_cells;
		}

		/**
		 * Build a table cell tag .
		 *
		 * @param mixed  $cell_value value in cell.
		 * @param string $classes style classes applied to <td> tag.
		 *
		 * @return string
		 */
		private static function table_cell( $cell_value, $classes = '' ) {
			$start_cell_tag = empty( $classes ) ? '<td>' : '<td class="' . $classes . '">';
			return $start_cell_tag . $cell_value . '</td>';
		}

		/**
		 * Evaluates kind enum to string.
		 *
		 * @param string $kind enum value.
		 *
		 * @return string
		 */
		private static function eval_kind( $kind ) {
			switch ( $kind ) {
				case 'course_completed':
					$kind = 'Course Completed';
					break;
				default:
					$kind;
			}

			return $kind;
		}

		/**
		 * Evaluates date values to string.
		 *
		 * @param string $created_at date value.
		 *
		 * @return string
		 */
		private static function eval_date_time( $created_at ) {
			$date_format = 'd M Y';
			$time_format = 'G:i A';
			$date_time   = date_create( $created_at );

			return sprintf(
				'<span> %1s </span> <span class="accredible-cell-time"> %2s </span>',
				date_format( $date_time, $date_format ),
				date_format( $date_time, $time_format )
			);
		}

		/**
		 * Returns cell error.
		 *
		 * @param string $error_message enum value.
		 *
		 * @return string
		 */
		private static function eval_error( $error_message ) {
			return '<span class="cell-value-error">' . $error_message . '</span>';
		}

		/**
		 * Returns row number.
		 *
		 * @param int $index item index.
		 *
		 * @return int
		 */
		private static function eval_row_num( $index ) {
			return ( self::$current_page - 1 ) * self::$page_size + $index;
		}

		/**
		 * Returns row actions.
		 *
		 * @param int $id id.
		 *
		 * @return string
		 */
		private static function eval_actions( $id ) {
			$actions = '';
			if ( ! empty( self::$row_actions ) ) {
				foreach ( self::$row_actions as $value ) {
					if ( ( ! empty( $value['label'] ) ) && ( ! empty( $value['action'] ) ) ) {
						$actions .= sprintf(
							'<a href="%s" class="button accredible-button-outline-natural accredible-button-small">' . $value['label'] . '</a>',
							wp_nonce_url(
								admin_url( 'admin.php?page=accredible_learndash_admin_action&action=' . $value['action'] . '&page_num=' . self::$current_page . '&id=' . $id ),
								$value['action'] . $id,
								'_mynonce'
							)
						);
					}
				}
			}
			return $actions;
		}
	}
endif;
