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

		$this->assertEquals( 'Invalid nonce.', $caught_exception );
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM %1s;', $table_name )
		);
		$this->assertCount( 1, $results );
	}
}
