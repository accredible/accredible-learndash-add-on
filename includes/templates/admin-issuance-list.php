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
				<tr class="accredible-row">
					<td>1</td>
					<td>Course 110</td>
					<td>Course 100 Series</td>
					<td>Course Completion</td>
					<td>
						<?php
							$date = date_create('2022-02-17 13:09:00');
							echo sprintf(
								'<span> %1s </span> <span class="accredible-cell-time"> %2s </span>',
								date_format($date, 'd M Y'),
								date_format($date, 'G:i A')
							);
						?>
					</td>
					<td></td>
				</tr>
				<tr class="accredible-row">
					<td>2</td>
					<td>Course 105</td>
					<td>Course 100 Series</td>
					<td>Course Completion</td>
					<td>
						<span>10 Jan 2022</span> <span class="accredible-cell-time">11:45 AM</span>
					</td>
					<td></td>
				</tr>
				<tr class="accredible-row">
					<td>3</td>
					<td>Course 104</td>
					<td>Course 100 Series</td>
					<td>Course Completion</td>
					<td>
						<span>08 Jan 2022</span> <span class="accredible-cell-time">10:07 AM</span>
					</td>
					<td></td>
				</tr>
				<tr class="accredible-row">
					<td>4</td>
					<td>Course 213</td>
					<td>Course 200 Series</td>
					<td>Course Completion</td>
					<td>
						<span>01 Dec 2021</span> <span class="accredible-cell-time">09:31 AM</span>
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
