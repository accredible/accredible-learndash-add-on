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
		const ISSUANCE_GROUP_NAME   = 'accredible_group_id';
		const ISSUANCE_KIND         = 'kind';
		const ISSUANCE_DATE_CREATED = 'created_at';

		const TABLE_INDEX   = 'index';
		const TABLE_ACTIONS = 'actions';

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
		 * Public constructor for class.
		 *
		 * @param int $current_page Current page.
		 * @param int $page_size Page size (optional).
		 */
		public function __construct( $current_page, $page_size = self::DEFAULT_PAGE_SIZE ) {
			self::$current_page = $current_page;
			self::$page_size    = $page_size;
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
		 * @param mixed $cell_value value in cell.
		 *
		 * @return string
		 */
		private static function table_cell( $cell_value ) {
			return '<td>' . $cell_value . '</td>';
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
				case 'course_completion':
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
	}
endif;
