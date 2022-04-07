<?php
/**
 * Accredible LearnDash Add-on admin settings page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __DIR__ ) . '/helpers/class-accredible-learndash-admin-form-helper.php';

if ( empty( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ) ) {
	$accredible_learndash_issuer = null;
} else {
	$accredible_learndash_client   = new Accredible_Learndash_Api_V1_Client();
	$accredible_learndash_response = $accredible_learndash_client->organization_search();

	if ( ! isset( $accredible_learndash_response['errors'] ) ) {
		$accredible_learndash_issuer = $accredible_learndash_response['issuer'];
	}
}
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title"><?php esc_html_e( 'Settings' ); ?></h1>
	</div>
	<div class="accredible-content">
		<form id="settings-form">
			<?php settings_fields( Accredible_Learndash_Admin_Setting::OPTION_GROUP ); ?>
			<div class="accredible-form-field">
				<label for="api_key"><?php esc_html_e( 'API Key' ); ?></label>
				<input
					type='text'
					name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ); ?>'
					value='<?php echo esc_html( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ); ?>' 
					id='api_key'/>
			</div>


			<div class="accredible-form-field">
				<label><?php esc_html_e( 'Server Region' ); ?></label>
				<div class="accredible-radio-group">
					<div class="radio-group-item">
						<input
								type='radio'
								name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ); ?>'
								value='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'
								id='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'
								<?php
								checked(
									get_option( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ),
									Accredible_Learndash_Admin_Setting::SERVER_REGION_US
								);
								?>
								/>
						<label class="radio-label" for='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'>US</label>
					</div>
					<div class="radio-group-item">
						<input
								type='radio'
								name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ); ?>'
								value='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'
								id='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'
								<?php
								checked(
									get_option( Accredible_Learndash_Admin_Setting::OPTION_SERVER_REGION ),
									Accredible_Learndash_Admin_Setting::SERVER_REGION_EU
								);
								?>
								/>
						<label class="radio-label" for='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'>EU</label>
					</div>
				</div>
			</div>

			<?php submit_button( 'Save', 'accredible-button-primary accredible-button-large', 'submit', false ); ?>
		</form>

		<div class="status-tile">
			<div class="logo">
				<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/accredible_logo.png' ); ?>" alt="Accredible logo">
			</div>
			<div class="status">
			<?php if ( ! empty( $accredible_learndash_issuer ) && ! is_null( $accredible_learndash_issuer ) ) { ?>
				<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/check.png' ); ?>">
				<span><?php esc_html_e( 'Integration is up and running' ); ?></span>
			<?php } else { ?>
				<img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/error.png' ); ?>">
				<span><?php esc_html_e( 'Integration is not working' ); ?></span>
			<?php } ?>
			</div>
			<?php if ( ! empty( $accredible_learndash_issuer ) && ! is_null( $accredible_learndash_issuer ) ) : ?>
			<div class="status-info">
				<div class="left">
					<div class="status-info-item">
						<span class="label"><?php esc_html_e( 'Issuer' ); ?></span>
						<span class="label-value">
							<?php Accredible_Learndash_Admin_Form_Helper::html( $accredible_learndash_issuer['name'] ); ?>
						</span>
					</div>
					<div class="status-info-item">
						<span class="label"><?php esc_html_e( 'Email' ); ?></span>
						<span class="label-value">
							<?php Accredible_Learndash_Admin_Form_Helper::html( $accredible_learndash_issuer['email'] ); ?>
						</span>
					</div>
					<div class="status-info-item">
						<span class="label"><?php esc_html_e( 'URL' ); ?></span>
						<span class="label-value">
							<?php Accredible_Learndash_Admin_Form_Helper::html( $accredible_learndash_issuer['url'] ); ?>
						</span>
					</div>
				</div>
				<div class="right">
					<div class="accredible-credits-tile">
						<img class="credits-icon" src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/credits.png' ); ?>">
						<div class="status-info-item">
							<span class="label"><?php esc_html_e( 'Credits Left' ); ?></span>
							<span class="label-value">
								<?php Accredible_Learndash_Admin_Form_Helper::html( $accredible_learndash_issuer['certificate_left'] ); ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="help-links">
				<div class="link-title"><?php esc_html_e( 'Need Help?' ); ?></div>
				<ul>
					<li><a href="<?php echo esc_url( 'https://help.accredible.com/integrate-with-accredible' ); ?>" target="_blank"><?php esc_html_e( 'Check our Help Center' ); ?></a></li>
					<li><a href="<?php echo esc_url( 'https://help.accredible.com/kb-tickets/new' ); ?>" target="_blank"><?php esc_html_e( 'Customer Support' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(function(){
		jQuery('#settings-form').on('submit', function(){
			event.preventDefault();
			const settings = jQuery(this).serialize();
			jQuery.post('options.php', settings, function(res, status){
				accredibleToast.success('Settings saved successfully', 3000);
			}).fail(function(err){
				accredibleToast.error('Failed to save settings, please try again later', 3000);
			});
		});
	});
</script>
