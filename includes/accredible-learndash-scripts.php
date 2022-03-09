<?php
/**
 * Scripts & Styles
 *
 * @package Accredible_Learndash_Add_On\Scripts
 */

/**
 * Enqueues styles for front-end.
 */
function accredible_learndash_load_resources() {
	wp_enqueue_style(
		'accredible-learndash-admin-theme',
		ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/css/accredible-admin-theme.css',
		array(),
		ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN
	);

	wp_enqueue_style(
		'accredible-learndash-admin-settings',
		ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/css/accredible-admin-settings.css',
		array(),
		ACCREDIBLE_LEARNDASH_SCRIPT_VERSION_TOKEN
	);
}
add_action( 'admin_enqueue_scripts', 'accredible_learndash_load_resources' );

/**
 * Admin body class Filter.
 *
 * @param string $classes Optional. The admin body CSS classes. Default empty.
 *
 * @return string Admin body CSS classes.
 */
function accredible_learndash_admin_body_class( $classes = '' ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( is_admin() && isset( $_GET['page'] ) && stripos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 'accredible_learndash' ) !== false ) {
		$classes .= ' accredible-learndash-admin ';
	}

	return $classes;
}
add_filter( 'admin_body_class', 'accredible_learndash_admin_body_class' );
