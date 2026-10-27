<?php
/**
 * Class Accredible_Learndash_Ajax_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

/**
 * Unit tests for Accredible_Learndash_Ajax
 */
class Accredible_Learndash_Ajax_Test extends WP_Ajax_UnitTestCase {
	/**
	 * Add custom logic to setUp.
	 */
	public function set_up() {
		parent::set_up();
		// XXX Stop transforming all `CREATE TABLE` to `CREATE TEMPORARY TABLE`.
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		Accredible_Learndash_Admin_Database::setup();
		Accredible_Learndash_Admin::add_ajax_actions();
	}

	/**
	 * Add custom logic to tearDown.
	 */
	public function tear_down() {
		parent::tear_down();
		Accredible_Learndash_Admin_Database::drop_all();
	}

	/**
	 * Return every admin ajax action registered by the plugin.
	 */
	public function ajax_actions() {
		return array(
			array( 'accredible_learndash_ajax_search_groups' ),
			array( 'accredible_learndash_ajax_load_issuer_html' ),
			array( 'accredible_learndash_ajax_load_auto_issuance_list_html' ),
			array( 'accredible_learndash_ajax_handle_auto_issuance_action' ),
			array( 'accredible_learndash_ajax_load_issuance_form_html' ),
			array( 'accredible_learndash_ajax_get_group' ),
			array( 'accredible_learndash_ajax_get_lessons' ),
		);
	}

	/**
	 * Test if a subscriber is rejected even with a valid nonce.
	 *
	 * @dataProvider ajax_actions
	 *
	 * @param string $action Ajax action name.
	 */
	public function test_rejects_subscriber( $action ) {
		$this->_setRole( 'subscriber' );
		$_POST['nonce'] = wp_create_nonce( Accredible_Learndash_Ajax::NONCE_ACTION );

		$this->assert_forbidden( $action );
	}

	/**
	 * Test if an administrator is rejected without a valid nonce.
	 *
	 * @dataProvider ajax_actions
	 *
	 * @param string $action Ajax action name.
	 */
	public function test_rejects_administrator_with_invalid_nonce( $action ) {
		$this->_setRole( 'administrator' );
		$_POST['nonce'] = 'invalid';

		$this->assert_forbidden( $action );
	}

	/**
	 * Test if an administrator with a valid nonce is allowed.
	 */
	public function test_allows_administrator_with_valid_nonce() {
		$this->_setRole( 'administrator' );
		$_POST['nonce'] = wp_create_nonce( Accredible_Learndash_Ajax::NONCE_ACTION );

		try {
			$this->_handleAjax( 'accredible_learndash_ajax_load_auto_issuance_list_html' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
	}

	/**
	 * Test if the issuance form ignores SQL in the id parameter.
	 */
	public function test_load_issuance_form_html_with_sql_in_id() {
		global $wpdb;
		$wpdb->insert(
			$wpdb->prefix . 'accredible_learndash_auto_issuances',
			array(
				'kind'                => 'course_completed',
				'post_id'             => 1,
				'accredible_group_id' => 1,
				'created_at'          => time(),
			)
		);

		$this->_setRole( 'administrator' );
		$_POST['nonce'] = wp_create_nonce( Accredible_Learndash_Ajax::NONCE_ACTION );
		$_POST['id']    = '0 OR 1=1';

		try {
			$this->_handleAjax( 'accredible_learndash_ajax_load_issuance_form_html' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
		$this->assertStringContainsString( 'name="call_action" value="add_auto_issuance"', $response['data'] );
	}

	/**
	 * Test if the auto issuance list falls back to the first page when the page number is not numeric.
	 */
	public function test_load_auto_issuance_list_html_with_non_numeric_page_num() {
		$this->_setRole( 'administrator' );
		$_POST['nonce']    = wp_create_nonce( Accredible_Learndash_Ajax::NONCE_ACTION );
		$_POST['page_num'] = 'abc';

		try {
			$this->_handleAjax( 'accredible_learndash_ajax_load_auto_issuance_list_html' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
		$this->assertStringContainsString( 'Viewing 1 - ', $response['data'] );
	}

	/**
	 * Assert that the ajax action responds with a JSON error and does nothing else.
	 *
	 * @param string $action Ajax action name.
	 */
	private function assert_forbidden( $action ) {
		try {
			$this->_handleAjax( $action );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		$response = json_decode( $this->_last_response, true );
		$this->assertFalse( $response['success'] );
		$this->assertEquals( 'You are not allowed to perform this action.', $response['data'] );
	}
}
