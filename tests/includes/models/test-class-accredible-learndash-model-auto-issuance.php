<?php
/**
 * Class Accredible_Learndash_Model_Auto_Issuance_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/models/class-accredible-learndash-model-auto-issuance.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/tests/class-accredible-learndash-custom-unit-test-case.php';

/**
 * Unit tests for Accredible_Learndash_Model_Auto_Issuance
 */
class Accredible_Learndash_Model_Auto_Issuance_Test extends Accredible_Learndash_Custom_Unit_Test_Case {
	/**
	 * Test if it returns a list of auto issuances with a WHERE clause.
	 */
	public function test_get_results() {
		$results = Accredible_Learndash_Model_Auto_Issuance::get_results();
		$this->assertCount( 0, $results );

		global $wpdb;
		$data1 = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);
		$data2 = array(
			'kind'                => 'course_completed',
			'post_id'             => 2,
			'accredible_group_id' => 2,
			'created_at'          => time(),
		);
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data1 );
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data2 );

		$results = Accredible_Learndash_Model_Auto_Issuance::get_results();
		$this->assertCount( 2, $results );

		$results = Accredible_Learndash_Model_Auto_Issuance::get_results( "post_id = 1 AND kind = 'course_completed'" );
		$this->assertCount( 1, $results );
	}

	/**
	 * Test if it creates a new record.
	 */
	public function test_insert() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$results    = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 0, $results );

		$before_time = time();
		$data        = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
		);

		$result = Accredible_Learndash_Model_Auto_Issuance::insert( $data );
		$this->assertEquals( 1, $result );

		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );

		$this->assertGreaterThanOrEqual( $before_time, $results[0]->created_at );
	}
}
