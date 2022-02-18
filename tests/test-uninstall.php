<?php
/**
 * Class Accredible_Learndash_Unintall_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

/**
 * Unit tests for the plugin main file
 */
class Accredible_Learndash_Uninstall_Test extends WP_UnitTestCase {
	/**
	 * Test uninstallation
	 */
	public function test_uninstall() {
		// Set plugin options.
		$api_key_option       = 'accredible_learndash_api_key';
		$server_region_option = 'accredible_learndash_server_region';
		update_option( $api_key_option, 'my_api_key' );
		update_option( $server_region_option, 'us' );

		// Create plugin custom DB tables.
		// XXX Stop transforming all `CREATE TABLE` to `CREATE TEMPORARY TABLE`.
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		Accredible_Learndash_Admin_Database::setup();

		// XXX Stop transforming all `DROP TABLE` to `DROP TEMPORARY TABLE`.
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );
		// Run the uninstall script.
		define( 'WP_UNINSTALL_PLUGIN', true ); // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		include plugin_dir_path( __DIR__ ) . '/uninstall.php';

		// Plugin options to be empty.
		$this->assertFalse( get_option( $api_key_option ) );
		$this->assertFalse( get_option( $server_region_option ) );

		// Plugin custom DB tables to be dropped.
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
		// Disable `PreparedSQL` since there are no inputs from users.
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$sql = "SHOW TABLES LIKE '$table_name'";
		return $wpdb->get_var( $sql ) === $table_name;
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
	}
}
