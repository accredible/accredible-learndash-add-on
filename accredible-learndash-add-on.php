<?php
/**
 * Accredible LearnDash Add-on
 *
 * @package Accredible_Learndash_Add_On
 *
 * Plugin Name: Accredible LearnDash Add-on
 * Plugin URI:  https://github.com/accredible/accredible-learndash-add-on
 * Description: This is a plugin description.
 * Version:     1.0.0
 * Author:      Accredible
 * Author URI:  https://www.accredible.com/
 * License:     GPL v2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || die;

define( 'ACCREDIBLE_LEARNDASH_VERSION', '1.0.0' );
define( 'ACCREDILBE_LEARNDASH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'ACCREDILBE_LEARNDASH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN', ACCREDIBLE_LEARNDASH_VERSION . '-' . time() );

if ( ! defined( 'ACCREDIBLE_LEARNDASH_PLUGIN_URL' ) ) {
	$accredible_learndash_plugin_url = trailingslashit( WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) );
	$accredible_learndash_plugin_url = str_replace( array( 'https://', 'http://' ), array( '//', '//' ), $accredible_learndash_plugin_url );

	/**
	 * Define Accredible - Set the plugin relative URL.
	 *
	 * Will be set based on the WordPress define `WP_PLUGIN_URL`.
	 *
	 * @uses WP_PLUGIN_URL
	 *
	 * @var string URL to plugin install directory.
	 */
	define( 'ACCREDIBLE_LEARNDASH_PLUGIN_URL', $accredible_learndash_plugin_url );
}

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash-admin.php';
	Accredible_Learndash_Admin::init();
}

require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash.php';
add_action( 'plugins_loaded', array( 'Accredible_Learndash', 'init' ), 11 );
