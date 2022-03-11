<?php
/**
 * Accredible LearnDash Add-on admin issuance list page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-issuance-list.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-table-helper.php';

$accredible_learndash_current_page = isset( $_GET['page_num'] ) ? esc_attr( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page_size    = 20;

$accredible_learndash_table_helper = new Accredible_Learndash_Admin_Table_Helper(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size
);

$accredible_learndash_issuances = Accredible_Learndash_Admin_Issuance_List::$issuances; // To be eplaced with value from DB.
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Issuance List' ); ?></h1>

		<button class="button accredible-primary"><?php esc_html_e( 'New Configuration' ); ?></button>
	</div>
	<div class="accredible-content">
		<table class="accredible-table">
			<thead>
				<tr class="accredible-header-row">
					<th></th>
					<th>Course Name</th>
					<th>Accredilbe Group</th>
					<th>Issuance Trigger</th>
					<th>Date Created</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				echo $accredible_learndash_table_helper->build_table_rows( $accredible_learndash_issuances ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</tbody>
		</table>
	</div>
</div>
