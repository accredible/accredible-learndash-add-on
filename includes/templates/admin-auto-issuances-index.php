<?php
/**
 * Accredible LearnDash Add-on admin AutoIssuance index page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';

$accredible_learndash_page = Accredible_Learndash_Model_Auto_Issuance::get_paginated_results( $_GET['page_num'] );

// phpcs:disable WordPress.WhiteSpace
// TODO: NTGR-516 & 517 to finalize the page.
?>

<h1>Auto Issuances</h1>
<p>Prev page: <?php echo $accredible_learndash_page['prev_page']; ?></p>
<p>Current page: <?php echo $accredible_learndash_page['current_page']; ?></p>
<p>Next page: <?php echo $accredible_learndash_page['next_page']; ?></p>
<ul>
	<?php foreach ( $accredible_learndash_page['results'] as $accredible_learndash_auto_issuance ) : ?>
		<li><?php echo $accredible_learndash_auto_issuance->id . ': ' . $accredible_learndash_auto_issuance->kind; ?></li>
	<?php endforeach; ?>
</ul>

