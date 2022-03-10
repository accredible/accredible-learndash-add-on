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
		const ISSUANCE_POST_ID      = 'post_id';
		const ISSUANCE_GROUP_NAME   = 'accredible_group_name';
		const ISSUANCE_KIND         = 'kind';
		const ISSUANCE_DATE_CREATED = 'created_at';

		const TABLE_INDEX   = 'index';
		const TABLE_ACTIONS = 'actions';

		/**
		 * Issuances data.
		 *
		 * @var array
		 */
		public $issuances = array(
			array(
				self::ISSUANCE_POST_ID      => 1,
				self::ISSUANCE_GROUP_NAME   => 'Course 100 Series',
				self::ISSUANCE_KIND         => 'course_completion',
				self::ISSUANCE_DATE_CREATED => '2022-02-17 13:09:00',
			),
			array(
				self::ISSUANCE_POST_ID      => 2,
				self::ISSUANCE_GROUP_NAME   => 'Course 100 Series',
				self::ISSUANCE_KIND         => 'course_completion',
				self::ISSUANCE_DATE_CREATED => '2022-01-10 11:45:00',
			),
			array(
				self::ISSUANCE_POST_ID      => 3,
				self::ISSUANCE_GROUP_NAME   => 'Course 100 Series',
				self::ISSUANCE_KIND         => 'course_completion',
				self::ISSUANCE_DATE_CREATED => '2022-01-08 10:07:00',
			),
			array(
				self::ISSUANCE_POST_ID      => 4,
				self::ISSUANCE_GROUP_NAME   => 'Course 200 Series',
				self::ISSUANCE_KIND         => 'course_completion',
				self::ISSUANCE_DATE_CREATED => '2021-12-01 09:31:00',
			),
		);

		/**
		 * Table rows.
		 *
		 * @var string
		 */
		public static $table_rows = null;

		/**
		 * Public constructor for class.
		 */
		public function __construct() {
			self::$table_rows = $this->build_table_rows( $this->issuances );
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
				$row_cells .= self::table_cell( $index + 1 );
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
						$value = get_option( $value, self::eval_error( 'Not found' ) );
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

				$table_cells .= self::table_cell( $value );
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
	}
endif;
new Accredible_Learndash_Admin_Issuance_List();
