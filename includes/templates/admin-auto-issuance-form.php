<?php
/**
 * Accredible LearnDash Add-on admin auto issuance form page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-auto-issuance.php';

$accredible_learndash_form_action = 'action=add_auto_issuance'; // TODO - or 'action=edit_auto_issuance&id=$issuance_id' if auto_issuance is available.
$accredible_learndash_courses     = apply_filters( 'accredible_learndash_get_course_options', '' );
$accredible_learndash_groups      = apply_filters( 'accredible_learndash_get_group_options', array() );
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<div class="accredible-flex-center">
			<a	href="admin.php?page=accredible_learndash_issuance_list"
				class="accredible-flex-center accredible-image-icon">
				<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/chevron-left.png' ); ?>">
			</a>

			<h1 class="title"><?php esc_html_e( 'Configure Auto Issuance' ); ?></h1>
		</div>

		<a	href="admin.php?page=accredible_learndash_issuance_list" 
			class="button accredible-button-outline-natural accredible-button-large"><?php esc_html_e( 'Cancel' ); ?></a>
	</div>
	<div class="accredible-content">
		<form
			action="admin.php?page=accredible_learndash_admin_action&<?php echo esc_attr( $accredible_learndash_form_action ); ?>"
			method="post">
				<div class="accredible-form-field">
					<label for="accredible_learndash_course"><?php esc_html_e( 'Select a course' ); ?></label>

					<select id="accredible_learndash_course" name="accredible_learndash_course">
						<?php foreach ( $accredible_learndash_courses as $accredible_learndash_key => $accredible_learndash_value ) : ?>
							<option value="<?php echo esc_attr( $accredible_learndash_key ); ?>">
								<?php echo esc_html( $accredible_learndash_value ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="accredible-form-field">
					<label for="accredible_learndash_group"><?php esc_html_e( 'Select the credential group' ); ?></label>

					<select id="accredible_learndash_group" name="accredible_learndash_group" <?php disabled( empty( $accredible_learndash_groups ) ); ?>>
						<?php foreach ( $accredible_learndash_groups as $accredible_learndash_key => $accredible_learndash_value ) : ?>
							<option value="<?php echo esc_attr( $accredible_learndash_key ); ?>">
								<?php echo esc_html( $accredible_learndash_value ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<?php submit_button( 'Save', 'accredible-button-primary accredible-button-large', 'submit', false ); ?>
			</form>
	</div>
</div>
