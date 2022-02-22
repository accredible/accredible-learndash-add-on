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

define( 'ACCREDILBE_LEARNDASH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'ACCREDILBE_LEARNDASH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash-admin.php';
	add_action( 'plugins_loaded', array( 'Accredible_Learndash_Admin', 'init' ), 11 );
}

require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash.php';
add_action( 'plugins_loaded', array( 'Accredible_Learndash', 'init' ), 11 );
