<?php
/**
 * Accredible LearnDash Add-on admin auto issuance form page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-auto-issuance.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';

$accredible_learndash_courses = Accredible_Learndash_Admin_Auto_Issuance::get_course_options();

$accredible_learndash_issuance_current_page = isset( $_GET['page_num'] ) ? esc_attr( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
$accredible_learndash_issuance_id           = isset( $_GET['id'] ) ? esc_attr( wp_unslash( $_GET['id'] ) ) : null; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended

$accredible_learndash_group       = array();
$accredible_learndash_form_action = 'add_auto_issuance';
$accredible_learndash_issuance    = array(
	'id'                  => null,
	'post_id'             => null,
	'accredible_group_id' => null,
);
if ( ! is_null( $accredible_learndash_issuance_id ) ) {
	$accredible_learndash_form_action = 'edit_auto_issuance&id=' . $accredible_learndash_issuance_id;
	/** TODO NTGR-519: get issuance by issuance id.
	$accredible_learndash_issuance = Accredible_Learndash_Model_Auto_Issuance::get_results();
	*/

	// Fetch saved group by id to fill autocomplete.
	if ( ! empty( $accredible_learndash_issuance['accredible_group_id'] ) ) {
		$accredible_learndash_client    = new Accredible_Learndash_Api_V1_Client();
		$accredible_learndash_group_res = $accredible_learndash_client->get_group( 323330 );
		if ( ! isset( $accredible_learndash_group_res['errors'] ) ) {
			$accredible_learndash_group = array(
				'id'   => $accredible_learndash_group_res['group']['id'],
				'name' => $accredible_learndash_group_res['group']['name'],
			);
		}
	}
}


?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<div class="accredible-flex-center">
			<a	href="admin.php?page=accredible_learndash_issuance_list&page_num=<?php echo esc_attr( $accredible_learndash_issuance_current_page ); ?>"
				class="accredible-flex-center accredible-image-icon"
				aria-label="<?php echo esc_attr( 'Go to issuance list' ); ?>">
				<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/chevron-left.png' ); ?>">
			</a>

			<h1 class="title"><?php esc_html_e( 'Configure Auto Issuance' ); ?></h1>
		</div>

		<a	href="admin.php?page=accredible_learndash_issuance_list&page_num=<?php echo esc_attr( $accredible_learndash_issuance_current_page ); ?>" 
			class="button accredible-button-outline-natural accredible-button-large"><?php esc_html_e( 'Cancel' ); ?></a>
	</div>
	<div class="accredible-content">
		<div class="accredible-form-wrapper">
			<div class="accredible-info-tile">
				<?php esc_html_e( 'Credential groups need to have been created before configuring auto issuance. If none appear, check your API key to make sure your integration is set up properly.' ); ?>
			</div>

			<form action="admin.php?page=accredible_learndash_admin_action&action=<?php echo esc_attr( $accredible_learndash_form_action ); ?>" method="post">
				<?php wp_nonce_field( $accredible_learndash_form_action . $accredible_learndash_issuance['id'], '_mynonce' ); ?>

				<div class="accredible-form-field">
					<label for="accredible_learndash_course"><?php esc_html_e( 'Select a course' ); ?></label>

					<select id="accredible_learndash_course" name="accredible_learndash_course">
						<option disabled selected value></option>
						<?php foreach ( $accredible_learndash_courses as $accredible_learndash_key => $accredible_learndash_value ) : ?>
							<option <?php selected( $accredible_learndash_key, $accredible_learndash_issuance['post_id'] ); ?> value="<?php echo esc_attr( $accredible_learndash_key ); ?>">
								<?php echo esc_html( $accredible_learndash_value ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php if ( empty( $accredible_learndash_courses ) ) : ?>
						<span class="accredible-form-field-error">
							<?php esc_html_e( 'No courses available. Add courses in LearnDash to continue.' ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div class="accredible-form-field">
					<label for="accredible_learndash_group"><?php esc_html_e( 'Select the credential group' ); ?></label>

					<input
						type='text'
						id='accredible_learndash_group_autocomplete' 
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_group, 'name' ); ?> 
						required/>

					<input 
						type="hidden"
						id="accredible_learndash_group"
						name="accredible_learndash_group"
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_group, 'id' ); ?>
						readonly/>
				</div>

				<?php submit_button( 'Save', 'accredible-button-primary accredible-button-large', 'submit', false ); ?>
			</form>
		</div>
	</div>
</div>
