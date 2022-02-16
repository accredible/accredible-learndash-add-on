<?php
/**
 * Class Accredible_Learndash_Admin_Setting_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/class-accredible-learndash-admin-setting.php';

/**
 * Unit tests for Accredible_Learndash_Admin_Setting
 */
class Accredible_Learndash_Admin_Setting_Test extends WP_UnitTestCase {

	/**
	 * Test if it registers the plugin setting group
	 */
	public function test_register() {
		$settings_before = get_registered_settings( 'accredible_learndash_settings_group' );
		$this->assertCount( 0, $settings_before );

		Accredible_Learndash_Admin_Setting::register();

		$settings = get_registered_settings( 'accredible_learndash_settings_group' );
		$this->assertCount( 2, $settings );
		$this->assertIsArray( $settings['accredible_learndash_api_key'] );
		$this->assertIsArray( $settings['accredible_learndash_server_region'] );
	}

	/**
	 * Test if it deletes the plugin options
	 */
	public function test_delete_options() {
		// Set plugin options.
		$api_key_option       = 'accredible_learndash_api_key';
		$server_region_option = 'accredible_learndash_server_region';
		update_option( $api_key_option, 'my_api_key' );
		update_option( $server_region_option, 'us' );

		Accredible_Learndash_Admin_Setting::delete_options();

		// Plugin options to be empty.
		$this->assertFalse( get_option( $api_key_option ) );
		$this->assertFalse( get_option( $server_region_option ) );
	}

	/**
	 * Test if it sets the default option values
	 */
	public function test_set_default() {
		// Delete plugin options.
		$api_key_option       = 'accredible_learndash_api_key';
		$server_region_option = 'accredible_learndash_server_region';
		delete_option( $api_key_option );
		delete_option( $server_region_option );

		Accredible_Learndash_Admin_Setting::set_default();

		// Plugin options to be empty.
		$this->assertFalse( get_option( $api_key_option ) );
		$this->assertEquals( 'us', get_option( $server_region_option ) );
	}

	/**
	 * Test if it does not update the existing option values.
	 */
	public function test_set_default_when_options_already_exist() {
		// Set plugin options.
		$api_key_option       = 'accredible_learndash_api_key';
		$server_region_option = 'accredible_learndash_server_region';
		update_option( $api_key_option, 'my_api_key' );
		update_option( $server_region_option, 'eu' );

		Accredible_Learndash_Admin_Setting::set_default();

		// Plugin options to not be updated.
		$this->assertEquals( 'my_api_key', get_option( $api_key_option ) );
		$this->assertEquals( 'eu', get_option( $server_region_option ) );
	}
}
