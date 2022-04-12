<?php
/**
 * Accredible LearnDash Add-on admin auto issuance form page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';

$accredible_learndash_courses = Accredible_Learndash_Model_Auto_Issuance::get_course_options();

$accredible_learndash_issuance_current_page = isset( $_GET['page_num'] ) ? sanitize_key( wp_unslash( $_GET['page_num'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$accredible_learndash_issuance_id           = isset( $_GET['id'] ) ? sanitize_key( wp_unslash( $_GET['id'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$accredible_learndash_group       = array();
$accredible_learndash_form_action = 'add_auto_issuance';
$accredible_learndash_issuance    = (object) array(
	'id'                  => null,
	'post_id'             => null,
	'accredible_group_id' => null,
);

if ( ! is_null( $accredible_learndash_issuance_id ) ) {
	$accredible_learndash_form_action = 'edit_auto_issuance';

	$accredible_learndash_issuance_nonce = isset( $_GET['_mynonce'] ) ? sanitize_key( wp_unslash( $_GET['_mynonce'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$accredible_learndash_issuance = Accredible_Learndash_Model_Auto_Issuance::get_row( "id = $accredible_learndash_issuance_id" );

	if ( ! ( isset( $accredible_learndash_issuance ) ) ) {
		wp_die( 'Auto Issuance not found.' );
	}

	// Fetch saved group by id to fill autocomplete.
	if ( ! is_null( $accredible_learndash_issuance ) && ! empty( $accredible_learndash_issuance->accredible_group_id ) ) {
		$accredible_learndash_client    = new Accredible_Learndash_Api_V1_Client();
		$accredible_learndash_group_res = $accredible_learndash_client->get_group( $accredible_learndash_issuance->accredible_group_id );
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

			<form id="issuance-form">
				<?php if ( 'add_auto_issuance' === $accredible_learndash_form_action ) { ?>
					<?php wp_nonce_field( $accredible_learndash_form_action, '_mynonce' ); ?>
				<?php } else { ?>
					<?php wp_nonce_field( $accredible_learndash_form_action . $accredible_learndash_issuance->id, '_mynonce' ); ?>
					<input type="hidden" name="id" value="<?php echo esc_attr( $accredible_learndash_issuance_id ); ?>">
				<?php } ?>

				<input type="hidden" name="call_action" value="<?php echo esc_attr( $accredible_learndash_form_action ); ?>">
				<input type="hidden" name="page_num" value="<?php echo esc_attr( $accredible_learndash_issuance_current_page ); ?>">

				<div class="accredible-form-field">
					<label><?php esc_html_e( 'Issuance Trigger' ); ?></label>

					<div class="accredible-radio-group">
						<div class="radio-group-item">
							<input type='radio' name='accredible_learndash_object[kind]' value='course_completed' id='issuance_trigger' checked readonly>
							<label class="radio-label" for='issuance_trigger'>Course Completion</label>
						</div>
					</div>
				</div>

				<div class="accredible-form-field">
					<label for="accredible_learndash_course"><?php esc_html_e( 'Select a course' ); ?></label>

					<select id="accredible_learndash_course" name="accredible_learndash_object[post_id]" required>
						<option disabled selected value></option>
						<?php foreach ( $accredible_learndash_courses as $accredible_learndash_key => $accredible_learndash_value ) : ?>
							<option <?php selected( $accredible_learndash_key, $accredible_learndash_issuance->post_id ); ?> value="<?php echo esc_attr( $accredible_learndash_key ); ?>">
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
						placeholder="<?php echo esc_attr( 'Type to search credential group' ); ?>"
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_group, 'name' ); ?>
						required/>

					<span id="accredible-form-field-group-error-msg" class="accredible-form-field-error accredible-form-field-hidden">
						<?php esc_html_e( 'A valid credential group is required.' ); ?>
					</span>

					<input
						type="hidden"
						id="accredible_learndash_group"
						name="accredible_learndash_object[accredible_group_id]"
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_group, 'id' ); ?>
						readonly/>
				</div>

				<button type="submit" id="submit" name="submit" class="button accredible-button-primary accredible-button-large">Save</button>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery( function(){
		const submitBtn = jQuery('#submit');
		submitBtn.on('click', function(){
			jQuery(this).addClass('accredible-button-spinner');
		});
		jQuery('#issuance-form').on('submit', function(event) {
			const formData = {};
			const group_id = jQuery('#accredible_learndash_group').val();
			if ( ! group_id ) {
				submitBtn.removeClass('accredible-button-spinner');
				jQuery('#accredible-form-field-group-error-msg').removeClass('accredible-form-field-hidden'); // show error
				return false; // prevent form submission
			}

			// build formdata object
			jQuery(this).serializeArray().reduce(function(acc, data){
				formData[data.name] = data.value;
				return formData;
			}, formData);

			// call BE
			accredibleAjax.doAutoIssuanceAction(
				formData
			).always(function(res){
				if ((typeof(res) === 'object')) {
					const message = res.data && res.data.message ? res.data.message : res.data;
					if (res.success) {
						accredibleToast.success(message);
					} else {
						accredibleToast.error(message);
					}
				}

				submitBtn.removeClass('accredible-button-spinner');
			});

			return false; // prevent form submission
		});
	});
</script>
