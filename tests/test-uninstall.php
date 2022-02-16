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

		// Run the uninstall script.
		define( 'WP_UNINSTALL_PLUGIN', true );
		include plugin_dir_path( __DIR__ ) . '/uninstall.php';

		// Plugin options to be empty.
		$this->assertFalse( get_option( $api_key_option ) );
		$this->assertFalse( get_option( $server_region_option ) );
	}
}
