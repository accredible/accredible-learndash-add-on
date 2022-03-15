<?php
/**
 * Accredible LearnDash Add-on admin issuance list page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-table-helper.php';
require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';

$accredible_learndash_current_page = isset( $_GET['page_num'] ) ? esc_attr( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page_size    = 20;
$accredible_learndash_row_actions  = array(
	array(
		'action' => 'edit_auto_issuance',
		'label'  => 'Edit',
	),
	array(
		'action' => 'delete_auto_issuance',
		'label'  => 'Delete',
	),
);

$accredible_learndash_table_helper = new Accredible_Learndash_Admin_Table_Helper(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size,
	$accredible_learndash_row_actions
);

$accredible_learndash_page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size
);
$accredible_learndash_issuances = array();
foreach ( $accredible_learndash_page['results'] as $accredible_learndash_issuance ) {
	array_push(
		$accredible_learndash_issuances,
		array(
			'post_id'             => $accredible_learndash_issuance->post_id,
			'accredible_group_id' => $accredible_learndash_issuance->accredible_group_id,
			'kind'                => $accredible_learndash_issuance->kind,
			'created_at'          => $accredible_learndash_issuance->created_at,
		)
	);
}
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Issuance List' ); ?></h1>

		<button class="button accredible-button-primary accredible-button-large"><?php esc_html_e( 'New Configuration' ); ?></button>
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
