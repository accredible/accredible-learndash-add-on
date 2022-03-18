<?php
/**
 * Accredible LearnDash Add-on admin issuance list page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-table-helper.php';
require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance-log.php';

$accredible_learndash_current_page = isset( $_GET['page_num'] ) ? esc_attr( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page_size    = 20;

$accredible_learndash_table_helper = new Accredible_Learndash_Admin_Table_Helper(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size,
	$accredible_learndash_row_actions
);

$accredible_learndash_page = Accredible_Learndash_Model_Auto_Issuance_Log::get_paginated_results(
	$accredible_learndash_current_page,
	$accredible_learndash_page_size
);

$accredible_learndash_issuance_logs = array();
foreach ( $accredible_learndash_page['results'] as $accredible_learndash_issuance_log ) {
	array_push(
		$accredible_learndash_issuance_logs,
		array(
			'auto_issuance_id'      => $accredible_learndash_issuance_log->accredible_learndash_auto_issuance_id,
			'created_at'            => $accredible_learndash_issuance_log->created_at,
			'accredible_group_name' => $accredible_learndash_issuance_log->accredible_group_name,
			'recipient_name'        => $accredible_learndash_issuance_log->recipient_name,
			'recipient_email'       => $accredible_learndash_issuance_log->recipient_email,
			'status'                => $accredible_learndash_issuance_log->error_message,
			'error_message'         => $accredible_learndash_issuance_log->error_message,
		)
	);
}
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Issuance Logs' ); ?></h1>
	</div>
	<div class="accredible-content">
		<div class="accredible-table-wrapper">
			<table class="accredible-table">
				<thead>
					<tr class="accredible-header-row">
						<th></th>
						<th>Auto Issuance ID</th>
						<th>Executed at</th>
						<th>Accredible Group</th>
						<th>User Name</th>
						<th>User Email</th>
						<th>Status</th>
						<th>Error message</th>
					</tr>
				</thead>
				<tbody>
					<?php
					echo $accredible_learndash_table_helper->build_table_rows( $accredible_learndash_issuance_logs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</tbody>
			</table>
			<?php
			if ( ! empty( $accredible_learndash_page ) && ! empty( $accredible_learndash_page['meta'] ) ) :
				Accredible_Learndash_Admin_Table_Helper::build_pagination_tile( $accredible_learndash_page['meta'], 'auto issuances' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endif;
			?>
		</div>
	</div>
</div>
