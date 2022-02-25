<?php
/**
 * Class Accredible_Learndash_Model_Auto_Issuance_Log_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/models/class-accredible-learndash-model-auto-issuance-log.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/tests/class-accredible-learndash-custom-unit-test-case.php';

/**
 * Unit tests for Accredible_Learndash_Model_Auto_Issuance_Log
 */
class Accredible_Learndash_Model_Auto_Issuance_Log_Test extends Accredible_Learndash_Custom_Unit_Test_Case {
	/**
	 * Test if it returns a list of auto issuance logs with a WHERE clause.
	 */
	public function test_get_results() {
		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results();
		$this->assertCount( 0, $results );

		global $wpdb;
		$data1 = array(
			'accredible_learndash_auto_issuance_id' => 1,
			'user_id'                               => 1,
			'accredible_group_id'                   => 1,
			'accredible_group_name'                 => 'My Course 1',
			'recipient_name'                        => 'Tom Test',
			'recipient_email'                       => 'tom@example.com',
			'credential_url'                        => 'https://www.credential.net/10000000',
			'created_at'                            => time(),
		);
		$data2 = array(
			'accredible_learndash_auto_issuance_id' => 2,
			'user_id'                               => 2,
			'accredible_group_id'                   => 2,
			'recipient_name'                        => 'Jerry Test',
			'recipient_email'                       => 'jerry@example.com',
			'error_message'                         => 'The server could not respond. Your API key might be invalid.',
			'created_at'                            => time(),
		);
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data1 );
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data2 );

		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results();
		$this->assertCount( 2, $results );

		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( "user_id = 1 AND recipient_name = 'Tom Test'" );
		$this->assertCount( 1, $results );
	}

	/**
	 * Test if it creates a new record.
	 */
	public function test_insert() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuance_logs';
		$results    = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 0, $results );

		$data   = array(
			'accredible_learndash_auto_issuance_id' => 1,
			'user_id'                               => 1,
			'accredible_group_id'                   => 1,
			'accredible_group_name'                 => 'My Course 1',
			'recipient_name'                        => 'Tom Test',
			'recipient_email'                       => 'tom@example.com',
			'credential_url'                        => 'https://www.credential.net/10000000',
			'created_at'                            => time(),
		);
		$result = Accredible_Learndash_Model_Auto_Issuance_Log::insert( $data );
		$this->assertEquals( 1, $result );
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );
	}
}
