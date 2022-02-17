<?php
/**
 * Class Accredible_Learndash_Admin_Database_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-database.php';

/**
 * Unit tests for Accredible_Learndash_Admin_Database
 */
class Accredible_Learndash_Admin_Database_Test extends WP_UnitTestCase {
	/**
	 * Test if it creates plugin custom DB tables.
	 */
	public function test_setup() {
		// Make sure if tables have not been created.
		foreach ( Accredible_Learndash_Admin_Database::TABLE_NAMES as $table_name ) {
			$this->assertFalse( $this->table_exists( $table_name ) );
		}
		delete_option( 'accredible_learndash_db_version' );

		// XXX Stop transforming all `CREATE TABLE` to `CREATE TEMPORARY TABLE`.
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		Accredible_Learndash_Admin_Database::setup();
		foreach ( Accredible_Learndash_Admin_Database::TABLE_NAMES as $table_name ) {
			$this->assertTrue( $this->table_exists( $table_name ) );
		}

		$this->assertEquals( '1.0', get_option( 'accredible_learndash_db_version' ) );
	}

	/**
	 * Test if it drops all plugin custom DB tables.
	 */
	public function test_drop_all() {
		// XXX Stop transforming all `CREATE TABLE` to `CREATE TEMPORARY TABLE`.
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		Accredible_Learndash_Admin_Database::setup();

		// XXX Stop transforming all `DROP TABLE` to `DROP TEMPORARY TABLE`.
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );
		Accredible_Learndash_Admin_Database::drop_all();
		foreach ( Accredible_Learndash_Admin_Database::TABLE_NAMES as $table_name ) {
			$this->assertFalse( $this->table_exists( $table_name ) );
		}

		$this->assertFalse( get_option( 'accredible_learndash_db_version' ) );
	}

	/**
	 * Check if the provided table name exists in the DB.
	 *
	 * @param string $table_name Table name.
	 */
	private function table_exists( $table_name ) {
		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		// Disable `PreparedSQL` since there are not inputs from users.
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$sql = "SHOW TABLES LIKE '$table_name'";
		return $wpdb->get_var( $sql ) === $table_name;
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
	}
}
