<?php
/**
 * Class Accredible_Learndash_Admin_Action_Handler_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-menu.php';

/**
 * Unit tests for Accredible_Learndash_Admin_Action_Handler
 */
class Accredible_Learndash_Admin_Action_Handler_Test extends Accredible_Learndash_Custom_Unit_Test_Case {
	/**
	 * Test if it adds an auto issuance.
	 */
	public function test_add_auto_issuance() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$results    = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);

		$this->assertCount( 0, $results );

		$new_data = array(
			'post_id'             => 2,
			'accredible_group_id' => 4,
			'kind'                => 'course_completed',
		);

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		$nonce         = wp_create_nonce( 'add_auto_issuance' );
		$redirect_url  = admin_url( 'admin.php?page=accredible_learndash_issuance_list&page_num=3' );
		$output_string = wp_json_encode(
			array(
				'success' => true,
				'data'    => array(
					'message'     => 'Saved auto issuance successfully.',
					'redirectUrl' => esc_url( $redirect_url ),
				),
			)
		);

		$this->expectOutputString( $output_string );
		try {
			Accredible_Learndash_Admin_Action_Handler::add_auto_issuance(
				array(
					'nonce'                       => $nonce,
					'redirect_url'                => admin_url( 'post-new.php' ),
					'page_num'                    => 3,
					'accredible_learndash_object' => $new_data,
				)
			);
		} catch ( WPDieException $error ) { // phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// We expected this, do nothing.
		}

		$results       = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$auto_issuance = $results[0];

		$this->assertCount( 1, $results );
		$this->assertEquals( 2, $auto_issuance->post_id );
		$this->assertEquals( 4, $auto_issuance->accredible_group_id );
		$this->assertEquals( 'course_completed', $auto_issuance->kind );
	}

	/**
	 * Test if it does not add an auto issuance.
	 */
	public function test_add_auto_issuance_when_incorrect_data() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$results    = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);

		$this->assertCount( 0, $results );

		$new_data = array(
			'post_id'             => 1,
			'accredible_group_id' => 4,
			'kind'                => '',
		);

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		$nonce        = wp_create_nonce( 'add_auto_issuance' );
		$redirect_url = admin_url( 'admin.php?page=accredible_learndash_issuance_list' );

		try {
			Accredible_Learndash_Admin_Action_Handler::add_auto_issuance(
				array(
					'nonce'                       => $nonce,
					'redirect_url'                => admin_url( 'post-new.php' ),
					'accredible_learndash_object' => $new_data,
				)
			);
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);

		$this->assertCount( 0, $results );
		$this->assertEquals( 'ERROR: kind is a required field.', $caught_exception );
	}

	/**
	 * Test if it updates an auto issuance.
	 */
	public function test_update_auto_issuance() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$wpdb->insert(
			$table_name,
			array(
				'kind'                => 'course_completed',
				'post_id'             => 1,
				'accredible_group_id' => 1,
				'created_at'          => time(),
			)
		);
		$record_id = $wpdb->insert_id;

		$new_data = array(
			'id'                  => $record_id,
			'post_id'             => 2,
			'accredible_group_id' => 4,
		);

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		$nonce         = wp_create_nonce( "edit_auto_issuance$record_id" );
		$redirect_url  = admin_url( 'post-new.php?id=1&page_num=2' );
		$output_string = wp_json_encode(
			array(
				'success' => true,
				'data'    => array(
					'message'     => 'Saved auto issuance successfully.',
					'redirectUrl' => esc_url( $redirect_url ),
				),
			)
		);

		$this->expectOutputString( $output_string );
		try {
			Accredible_Learndash_Admin_Action_Handler::edit_auto_issuance(
				array(
					'id'                          => $record_id,
					'nonce'                       => $nonce,
					'redirect_url'                => $redirect_url,
					'accredible_learndash_object' => $new_data,
				)
			);
		} catch ( WPDieException $error ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// We expected this, do nothing.
		}

		$result = $wpdb->get_row(
			$wpdb->prepare( 'SELECT * FROM %1s WHERE id = %d;', $table_name, $record_id )
		);

		$this->assertEquals( 2, $result->post_id );
		$this->assertEquals( 4, $result->accredible_group_id );
	}

	/**
	 * Test if it does not update an auto issuance.
	 */
	public function test_update_auto_issuance_when_incorrect_data() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$wpdb->insert(
			$table_name,
			array(
				'kind'                => 'course_completed',
				'post_id'             => 1,
				'accredible_group_id' => 1,
				'created_at'          => time(),
			)
		);
		$id = $wpdb->insert_id;

		$new_data = array(
			'id'                  => $id,
			'post_id'             => 2,
			'accredible_group_id' => 2,
			'kind'                => null,
		);

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		$nonce        = wp_create_nonce( "edit_auto_issuance$id" );
		$redirect_url = admin_url( 'admin.php?page=accredible_learndash_issuance_list' );

		try {
			Accredible_Learndash_Admin_Action_Handler::edit_auto_issuance(
				array(
					'id'                          => $id,
					'nonce'                       => $nonce,
					'redirect_url'                => admin_url( 'post-new.php' ),
					'accredible_learndash_object' => $new_data,
				)
			);
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$result = $wpdb->get_row(
			$wpdb->prepare( 'SELECT * FROM %1s WHERE id = %d;', $table_name, $id )
		);

		$this->assertEquals( 'ERROR: kind is a required field.', $caught_exception );
		$this->assertEquals( 1, $result->post_id );
		$this->assertEquals( 1, $result->accredible_group_id );
	}

	/**
	 * Test if it deletes an auto issuance.
	 */
	public function test_delete_auto_issuance() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$wpdb->insert(
			$table_name,
			array(
				'kind'                => 'course_completed',
				'post_id'             => 1,
				'accredible_group_id' => 1,
				'created_at'          => time(),
			)
		);
		$id      = $wpdb->insert_id;
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		$nonce        = wp_create_nonce( "delete_auto_issuance$id" );
		$redirect_url = admin_url( 'post-new.php' );

		$this->expectOutputString( "<p>Processing...</p><script>window.location.href='$redirect_url'</script>" );
		Accredible_Learndash_Admin_Action_Handler::delete_auto_issuance(
			array(
				'id'           => $id,
				'nonce'        => $nonce,
				'redirect_url' => admin_url( 'post-new.php' ),
			)
		);
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 0, $results );
	}

	/**
	 * Test if it raises an exception when nonce is invalid.
	 */
	public function test_delete_auto_issuance_with_invalid_nonce() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accredible_learndash_auto_issuances';
		$wpdb->insert(
			$table_name,
			array(
				'kind'                => 'course_completed',
				'post_id'             => 1,
				'accredible_group_id' => 1,
				'created_at'          => time(),
			)
		);
		$id      = $wpdb->insert_id;
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );

		// Login as an admin.
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_user );
		try {
			Accredible_Learndash_Admin_Action_Handler::delete_auto_issuance(
				array(
					'id'           => $id,
					'nonce'        => 'nonce',
					'redirect_url' => admin_url( 'post-new.php' ),
				)
			);
			$caught_exception = null;
		} catch ( WPDieException $error ) {
			$caught_exception = $error->getMessage();
		}

		$this->assertEquals( 'ERROR: Invalid nonce.', $caught_exception );
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );
	}
}
