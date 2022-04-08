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
		$data1_id = $wpdb->insert_id;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data2 );
		$data2_id = $wpdb->insert_id;

		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results();
		$this->assertCount( 2, $results );

		// With $where_sql.
		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( "user_id = 1 AND recipient_name = 'Tom Test'" );
		$this->assertCount( 1, $results );

		// With $limit.
		$limit   = 1;
		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( '', $limit );
		$this->assertCount( 1, $results );
		$this->assertEquals( $data1_id, $results[0]->id );

		// With $limit & $offset.
		$offset  = 1;
		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_results( '', $limit, $offset );
		$this->assertCount( 1, $results );
		$this->assertEquals( $data2_id, $results[0]->id );
	}

	/**
	 * Test if it returns an auto issuance log with a WHERE clause.
	 */
	public function test_get_row() {
		$result = Accredible_Learndash_Model_Auto_Issuance_Log::get_row();
		$this->assertEquals( null, $result );

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
		$data1_id = $wpdb->insert_id;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data2 );
		$data2_id = $wpdb->insert_id;

		$result = Accredible_Learndash_Model_Auto_Issuance_Log::get_row();
		$this->assertEquals( $data1_id, $result->id );

		// With $where_sql.
		$result = Accredible_Learndash_Model_Auto_Issuance_Log::get_row( "user_id = 2 AND recipient_name = 'Jerry Test'" );
		$this->assertEquals( $data2_id, $result->id );

		// With no results.
		$result = Accredible_Learndash_Model_Auto_Issuance_Log::get_row( 'user_id = 3' );
		$this->assertEquals( null, $result );
	}

	/**
	 * Test if it returns the total number of found records.
	 */
	public function test_get_total_count() {
		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_total_count();
		$this->assertEquals( 0, $results );

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

		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_total_count();
		$this->assertEquals( 2, $results );

		$results = Accredible_Learndash_Model_Auto_Issuance_Log::get_total_count( "user_id = 1 AND recipient_name = 'Tom Test'" );
		$this->assertEquals( 1, $results );
	}

	/**
	 * Test if it returns paginated results.
	 */
	public function test_get_paginated_results() {
		$page      = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results( 1, null );
		$page_meta = $page['meta'];
		$this->assertCount( 0, $page['results'] );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 0, $page_meta['total_pages'] );
		$this->assertEquals( 0, $page_meta['total_count'] );
		$this->assertEquals( 50, $page_meta['page_size'] );

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
		$data3 = array(
			'accredible_learndash_auto_issuance_id' => 2,
			'user_id'                               => 2,
			'accredible_group_id'                   => 2,
			'accredible_group_name'                 => 'My Course 1',
			'recipient_name'                        => 'Jerry Test',
			'recipient_email'                       => 'jerry@example.com',
			'credential_url'                        => 'https://www.credential.net/10000000',
			'created_at'                            => time(),
		);
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data1 );
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data2 );
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuance_logs', $data3 );

		$page      = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results( 1, null );
		$page_meta = $page['meta'];
		$this->assertCount( 3, $page['results'] );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 1, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 50, $page_meta['page_size'] );

		// With $page_size.
		$page_size = 1;
		$page      = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results( 1, $page_size );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( 2, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 3, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 1, $page_meta['page_size'] );

		$page      = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results( 2, $page_size );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 2, $page_meta['current_page'] );
		$this->assertEquals( 3, $page_meta['next_page'] );
		$this->assertEquals( 1, $page_meta['prev_page'] );
		$this->assertEquals( 3, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 1, $page_meta['page_size'] );

		$page      = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results( 3, $page_size );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 3, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( 2, $page_meta['prev_page'] );
		$this->assertEquals( 3, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 1, $page_meta['page_size'] );

		// With $where_sql.
		$page      = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results( 1, null, "user_id = 1 AND recipient_name = 'Tom Test'" );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 1, $page_meta['total_pages'] );
		$this->assertEquals( 1, $page_meta['total_count'] );
		$this->assertEquals( 50, $page_meta['page_size'] );
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

		$before_time = time();
		$data        = array(
			'accredible_learndash_auto_issuance_id' => 1,
			'user_id'                               => 1,
			'accredible_group_id'                   => 1,
			'accredible_group_name'                 => 'My Course 1',
			'recipient_name'                        => 'Tom Test',
			'recipient_email'                       => 'tom@example.com',
			'credential_url'                        => 'https://www.credential.net/10000000',
		);

		$result = Accredible_Learndash_Model_Auto_Issuance_Log::insert( $data );
		$this->assertEquals( 1, $result );

		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );

		$this->assertGreaterThanOrEqual( $before_time, $results[0]->created_at );
	}

	/**
	 * Test if it updates a record.
	 */
	public function test_update() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuance_logs';

		$initial_data = array(
			'accredible_learndash_auto_issuance_id' => 1,
			'user_id'                               => 1,
			'accredible_group_id'                   => 1,
			'accredible_group_name'                 => 'My Course 1',
			'recipient_name'                        => 'Tom Test',
			'recipient_email'                       => 'tom@example.com',
			'credential_url'                        => 'https://www.credential.net/10000000',
			'created_at'                            => time(),
		);
		$new_data     = array(
			'accredible_learndash_auto_issuance_id' => 2,
			'recipient_name'                        => 'Jerry Test',
			'recipient_email'                       => 'jerry@example.com',
		);
		$wpdb->insert( $table_name, $initial_data );
		$record_id = $wpdb->insert_id;

		Accredible_Learndash_Model_Auto_Issuance_Log::update( $record_id, $new_data );

		$result = $wpdb->get_row(
			$wpdb->prepare( 'SELECT * FROM %1s WHERE id = %d;', $table_name, $record_id )
		);

		$this->assertEquals( $new_data['accredible_learndash_auto_issuance_id'], $result->accredible_learndash_auto_issuance_id );
		$this->assertEquals( $new_data['recipient_name'], $result->recipient_name );
		$this->assertEquals( $new_data['recipient_email'], $result->recipient_email );
	}

	/**
	 * Test if it deletes a record.
	 */
	public function test_delete() {
		global $wpdb;
		$data       = array(
			'accredible_learndash_auto_issuance_id' => 1,
			'user_id'                               => 1,
			'accredible_group_id'                   => 1,
			'accredible_group_name'                 => 'My Course 1',
			'recipient_name'                        => 'Tom Test',
			'recipient_email'                       => 'tom@example.com',
			'credential_url'                        => 'https://www.credential.net/10000000',
			'created_at'                            => time(),
		);
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuance_logs';
		$wpdb->insert( $table_name, $data );
		$id      = $wpdb->insert_id;
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );

		Accredible_Learndash_Model_Auto_Issuance_Log::delete( $id );

		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 0, $results );
	}
}
