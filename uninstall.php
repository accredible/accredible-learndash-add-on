<?php
/**
 * Functions for uninstall Accredible_Learndash_Add_On
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

// TODO: NTGR-522 Destroy custom database tables.

require_once plugin_dir_path( __FILE__ ) . '/includes/class-accredible-learndash-admin-setting.php';
Accredible_Learndash_Admin_Setting::delete_options();

