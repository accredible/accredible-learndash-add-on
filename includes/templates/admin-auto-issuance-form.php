<?php
/**
 * Accredible LearnDash Add-on admin auto issuance form page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/models/class-accredible-learndash-model-auto-issuance.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';

$accredible_learndash_courses     = Accredible_Learndash_Model_Auto_Issuance::get_course_options();
$accredible_learndash_group       = array();
$accredible_learndash_form_action = 'add_auto_issuance';
$accredible_learndash_issuance    = (object) array(
	'id'                  => null,
	'post_id'             => null,
	'accredible_group_id' => null,
);

if ( ! is_null( $accredible_learndash_issuance_id ) ) {
	$accredible_learndash_issuance_row = Accredible_Learndash_Model_Auto_Issuance::get_row( "id = $accredible_learndash_issuance_id" );

	if ( isset( $accredible_learndash_issuance_row ) ) {
		$accredible_learndash_form_action = 'edit_auto_issuance';
		$accredible_learndash_issuance    = $accredible_learndash_issuance_row;
	}
}
?>

<div class="accredible-wrapper">
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

				<input type="hidden" id="call_action" name="call_action" value="<?php echo esc_attr( $accredible_learndash_form_action ); ?>">
				<input type="hidden" id="page_num" name="page_num" value="<?php echo esc_attr( $accredible_learndash_issuance_current_page ); ?>">

				<div class="accredible-form-field accredible-fill-width">
					<label><?php esc_html_e( 'Issuance Trigger' ); ?></label>

					<div class="accredible-radio-group">
						<div class="radio-group-item">
							<input type='radio' name='accredible_learndash_object[kind]' value='course_completed' id='issuance_trigger' checked readonly>
							<label class="radio-label" for='issuance_trigger'>Course Completion</label>
						</div>
					</div>
				</div>

				<div class="accredible-form-field accredible-fill-width">
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

				<div class="accredible-form-field accredible-fill-width">
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
						<?php Accredible_Learndash_Admin_Form_Helper::value_attr( $accredible_learndash_issuance, 'accredible_group_id' ); ?>
						readonly/>
				</div>

				<div class="accredible-sidenav-actions">
					<button type="button" id="cancel" class="button accredible-button-flat-natural accredible-button-large">Cancel</button>
					<button type="submit" id="submit" name="submit" class="button accredible-button-primary accredible-button-large">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery( function(){
		function reloadAutoIssuanceList(page_num) {
			accredibleAjax.loadAutoIssuanceListInfo(page_num).always(function(res){
				const issuerHTML = res.data;
				jQuery('.accredible-content').html(issuerHTML);
				// Re-initialise event handlers
				setupEditClickHandler();
				accredibleDialog.init();
			});
		}

		// Initialize groups autocomplete.
		accredibleAutoComplete.init();

		// Fetch saved group by id to fill autocomplete.
		const submitBtn = jQuery('#submit');
		const groupId = jQuery('#accredible_learndash_group').val();
		if (groupId) {
			submitBtn.attr('disabled', true);
			const autocompleteElem = jQuery('#accredible_learndash_group_autocomplete');
			autocompleteElem.addClass('ui-autocomplete-loading');
			accredibleAjax.getGroup(groupId).done(function(res){
				if (res.data) {
					autocompleteElem.removeClass('ui-autocomplete-loading');
					autocompleteElem.val(res.data.name);
					submitBtn.removeAttr('disabled');
				}
			});
		}

		// Add loading spinner on click.
		submitBtn.on('click', function(){
			jQuery(this).addClass('accredible-button-spinner');
		});

		// Close dialog on cancel click.
		const cancelBtn = jQuery('#cancel');
		cancelBtn.on('click', function() {
			accredibleSidenav.close();
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
						if (res.data && res.data.id && res.data.nonce) {
							// update nonce
							jQuery('#_mynonce').val(res.data.nonce);
							// add id input
							jQuery(`<input type="hidden" id="id" name="id" value="${res.data.id}">`).insertAfter('#_mynonce');
							// update action
							jQuery('#call_action').val('edit_auto_issuance');
						}
						reloadAutoIssuanceList(formData.page_num);
						accredibleSidenav.close();
						accredibleToast.success(message, 5000);
					} else {
						accredibleToast.error(message, 5000);
					}
				}

				submitBtn.removeClass('accredible-button-spinner');
			});

			return false; // prevent form submission
		});
	});
</script>
