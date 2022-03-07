<?php
/**
 * Accredible LearnDash Add-on admin issuance list page template.
 *
 * @package Accredible_Learndash_Add_On
 */
defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-issuance-list.php';
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Issuance List' ); ?></h1>

		<button class="button accredible-primary"><?php esc_html_e( 'New Configuration' ); ?></button>
	</div>
	<div class="accredible-content">
		<table class="accredible-table">
			<tr></tr>
		</table>
    </div>
</div>