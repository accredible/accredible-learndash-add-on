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
		$data1_id = $wpdb->insert_id;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data2 );
		$data2_id = $wpdb->insert_id;

		$results = Accredible_Learndash_Model_Auto_Issuance::get_results();
		$this->assertCount( 2, $results );

		// With $where_sql.
		$results = Accredible_Learndash_Model_Auto_Issuance::get_results( "post_id = 1 AND kind = 'course_completed'" );
		$this->assertCount( 1, $results );

		// With $limit.
		$limit   = 1;
		$results = Accredible_Learndash_Model_Auto_Issuance::get_results( '', $limit );
		$this->assertCount( 1, $results );
		$this->assertEquals( $data1_id, $results[0]->id );

		// With $limit & $offset.
		$offset  = 1;
		$results = Accredible_Learndash_Model_Auto_Issuance::get_results( '', $limit, $offset );
		$this->assertCount( 1, $results );
		$this->assertEquals( $data2_id, $results[0]->id );

		// With $limit & $offset & order_by.
		$offset  = 1;
		$options = array( 'order_by' => 'id DESC' );
		$results = Accredible_Learndash_Model_Auto_Issuance::get_results( '', $limit, $offset, $options );
		$this->assertCount( 1, $results );
		$this->assertEquals( $data1_id, $results[0]->id );
	}

	/**
	 * Test if it returns an auto issuance with a WHERE clause.
	 */
	public function test_get_row() {
		$result = Accredible_Learndash_Model_Auto_Issuance::get_row();
		$this->assertEquals( null, $result );

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
		$data1_id = $wpdb->insert_id;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data2 );
		$data2_id = $wpdb->insert_id;

		$result = Accredible_Learndash_Model_Auto_Issuance::get_row();
		$this->assertEquals( $data1_id, $result->id );

		// With $where_sql.
		$result = Accredible_Learndash_Model_Auto_Issuance::get_row( 'post_id = 2' );
		$this->assertEquals( $data2_id, $result->id );

		// With no results.
		$result = Accredible_Learndash_Model_Auto_Issuance::get_row( 'post_id = 10' );
		$this->assertEquals( null, $result );
	}

	/**
	 * Test if it returns the total number of found records.
	 */
	public function test_get_total_count() {
		$results = Accredible_Learndash_Model_Auto_Issuance::get_total_count();
		$this->assertEquals( 0, $results );

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

		$results = Accredible_Learndash_Model_Auto_Issuance::get_total_count();
		$this->assertEquals( 2, $results );

		$results = Accredible_Learndash_Model_Auto_Issuance::get_total_count( "post_id = 1 AND kind = 'course_completed'" );
		$this->assertEquals( 1, $results );
	}

	/**
	 * Test if it returns paginated results.
	 */
	public function test_get_paginated_results() {
		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 1, null );
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
		$data3 = array(
			'kind'                => 'course_completed',
			'post_id'             => 3,
			'accredible_group_id' => 3,
			'created_at'          => time(),
		);
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data1 );
		$data1_id = $wpdb->insert_id;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data2 );
		$data2_id = $wpdb->insert_id;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data3 );
		$data3_id = $wpdb->insert_id;

		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 1, null );
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
		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 1, $page_size );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( 2, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 3, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 1, $page_meta['page_size'] );

		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 2, $page_size );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 2, $page_meta['current_page'] );
		$this->assertEquals( 3, $page_meta['next_page'] );
		$this->assertEquals( 1, $page_meta['prev_page'] );
		$this->assertEquals( 3, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 1, $page_meta['page_size'] );

		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 3, $page_size );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 3, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( 2, $page_meta['prev_page'] );
		$this->assertEquals( 3, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 1, $page_meta['page_size'] );

		// With $where_sql.
		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 1, null, "post_id = 1 AND kind = 'course_completed'" );
		$page_meta = $page['meta'];
		$this->assertCount( 1, $page['results'] );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 1, $page_meta['total_pages'] );
		$this->assertEquals( 1, $page_meta['total_count'] );
		$this->assertEquals( 50, $page_meta['page_size'] );

		// With $options.
		$options   = array( 'order_by' => 'id DESC' );
		$page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( 1, null, '', $options );
		$page_meta = $page['meta'];
		$this->assertCount( 3, $page['results'] );
		$this->assertEquals( $data3_id, $page['results'][0]->id );
		$this->assertEquals( 1, $page_meta['current_page'] );
		$this->assertEquals( null, $page_meta['next_page'] );
		$this->assertEquals( null, $page_meta['prev_page'] );
		$this->assertEquals( 1, $page_meta['total_pages'] );
		$this->assertEquals( 3, $page_meta['total_count'] );
		$this->assertEquals( 50, $page_meta['page_size'] );
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

	/**
	 * Test if it updates a record.
	 */
	public function test_update() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';

		$initial_data = array(
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'kind'                => 'example_kind',
			'created_at'          => time(),
		);
		$new_data     = array(
			'post_id'             => 2,
			'accredible_group_id' => 4,
			'kind'                => 'course_completed',
		);
		$wpdb->insert( $table_name, $initial_data );
		$record_id = $wpdb->insert_id;

		Accredible_Learndash_Model_Auto_Issuance::update( $record_id, $new_data );

		$result = $wpdb->get_row(
			$wpdb->prepare( 'SELECT * FROM %1s WHERE id = %d;', $table_name, $record_id )
		);

		$this->assertEquals( $new_data['post_id'], $result->post_id );
		$this->assertEquals( $new_data['accredible_group_id'], $result->accredible_group_id );
		$this->assertEquals( $new_data['kind'], $result->kind );
	}

	/**
	 * Test if it deletes a record.
	 */
	public function test_delete() {
		global $wpdb;
		$data       = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$wpdb->insert( $table_name, $data );
		$id      = $wpdb->insert_id;
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );

		Accredible_Learndash_Model_Auto_Issuance::delete( $id );

		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 0, $results );
	}

	/**
	 * Test if it get the course options.
	 */
	public function test_get_course_options() {
		$data1 = array(
			'post_title' => 'Test Course Title 1',
			'post_type'  => 'sfwd-courses',
		);
		$data2 = array(
			'post_title' => 'Test Course Title 2',
			'post_type'  => 'sfwd-courses',
		);
		$data3 = array(
			'post_title' => 'Default post',
		);
		$id1   = self::factory()->post->create( $data1 );
		$id2   = self::factory()->post->create( $data2 );
		self::factory()->post->create( $data3 );

		$expected_result = array(
			$id1 => $data1['post_title'],
			$id2 => $data2['post_title'],
		);

		$result = Accredible_Learndash_Model_Auto_Issuance::get_course_options();

		$this->assertEquals( $expected_result, $result );
	}

	/**
	 * Test if it return empty array when no courses available.
	 */
	public function test_get_course_options_when_not_found() {
		$result = Accredible_Learndash_Model_Auto_Issuance::get_course_options();

		$this->assertEquals( array(), $result );
	}

	/**
	 * Test if it passes with valid data when creating.
	 */
	public function test_validate_when_creating() {
		$data = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data );
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertNull( $caught_exception );
	}

	/**
	 * Test if it passes with valid data when updating.
	 */
	public function test_validate_when_updating() {
		$data1 = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);

		global $wpdb;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data1 );
		$id = $wpdb->insert_id;

		$data = array( 'post_id' => 2 );

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data, $id );
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertNull( $caught_exception );
	}

	/**
	 * Test if it raises an error with an empty kind.
	 */
	public function test_validate_when_kind_is_empty() {
		$data = array(
			'kind'                => '',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data );
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: kind is a required field.', $caught_exception );
	}

	/**
	 * Test if it raises an error with an empty post_id.
	 */
	public function test_validate_when_post_id_is_empty() {
		$data = array(
			'kind'                => 'course_completed',
			'post_id'             => '',
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data );
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: post_id is a required field.', $caught_exception );
	}

	/**
	 * Test if it raises an error with an empty accredible_group_id.
	 */
	public function test_validate_when_accredible_group_id_is_empty() {
		$data = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => '',
			'created_at'          => time(),
		);

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data );
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: accredible_group_id is a required field.', $caught_exception );
	}

	/**
	 * Test if it raises an error with an invalid kind.
	 */
	public function test_validate_when_kind_is_invalid() {
		$data = array(
			'kind'                => 'completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data );
			$caught_exception = null;
		} catch ( \Exception $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: completed is an invalid kind.', $caught_exception );
	}

	/**
	 * Test if it raises an error when creating with duplicate data.
	 */
	public function test_validate_when_creating_with_duplicate_data() {
		$data = array(
			'kind'                => 'course_completed',
			'post_id'             => 1,
			'accredible_group_id' => 1,
			'created_at'          => time(),
		);
		global $wpdb;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data );

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data );
			$caught_exception = null;
		} catch ( \Exception $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: Post ID 1 already has the same kind of auto issuance.', $caught_exception );
	}

	/**
	 * Test if it raises an error when updating with duplicate data.
	 */
	public function test_validate_when_updating_with_duplicate_data() {
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

		global $wpdb;
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data1 );
		$wpdb->insert( $wpdb->prefix . 'accredible_learndash_auto_issuances', $data2 );
		$id = $wpdb->insert_id;

		$data = array( 'post_id' => 1 );

		try {
			Accredible_Learndash_Model_Auto_Issuance::validate( $data, $id );
			$caught_exception = null;
		} catch ( \Exception $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: Post ID 1 already has the same kind of auto issuance.', $caught_exception );
	}
}
