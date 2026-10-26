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
