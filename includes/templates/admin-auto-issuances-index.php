<?php
/**
 * Accredible LearnDash Add-on admin AutoIssuance index page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';

$accredible_learndash_page_name = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : null; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page_num  = isset( $_GET['page_num'] ) ? esc_attr( wp_unslash( $_GET['page_num'] ) ) : null; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
$accredible_learndash_page      = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( $accredible_learndash_page_num, 1 );

// phpcs:disable WordPress.WhiteSpace
// TODO: NTGR-516 & 517 to finalize the page.
?>

<h1>Auto Issuances</h1>
<?php if ( ! empty( $accredible_learndash_page['prev_page'] ) ) : ?>
	<a href='<?php echo esc_html( admin_url( 'admin.php?page=' . $accredible_learndash_page_name . '&page_num=' . $accredible_learndash_page['prev_page'] ) ); ?>'>
	Prev page: <?php echo esc_html( $accredible_learndash_page['prev_page'] ); ?>
	</a>
	<?php endif; ?>
<p>Current page: <?php echo esc_html( $accredible_learndash_page['current_page'] ); ?></p>
<?php if ( ! empty( $accredible_learndash_page['next_page'] ) ) : ?>
	<a href='<?php echo esc_html( admin_url( 'admin.php?page=' . $accredible_learndash_page_name . '&page_num=' . $accredible_learndash_page['next_page'] ) ); ?>'>
	Next page: <?php echo esc_html( $accredible_learndash_page['next_page'] ); ?>
	</a>
	<?php endif; ?>
<p>Total count: <?php echo esc_html( $accredible_learndash_page['total_count'] ); ?></p>
<p>Total pages: <?php echo esc_html( $accredible_learndash_page['total_pages'] ); ?></p>
<ul>
	<?php foreach ( $accredible_learndash_page['results'] as $accredible_learndash_auto_issuance ) : ?>
		<li><?php echo esc_html( $accredible_learndash_auto_issuance->id . ': ' . $accredible_learndash_auto_issuance->kind ); ?></li>
	<?php endforeach; ?>
</ul>

