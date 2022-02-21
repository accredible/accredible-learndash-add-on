<?php
/**
 * Accredible LearnDash Add-on admin settings page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-setting.php';

// phpcs:disable WordPress.WhiteSpace
// TODO: NTGR-508, 512 & 513 to finalize the settings page.
?>

<div class="accredible-wrapper">
	<div class="accredible-header-tile">
		<h1 class="title">Settings</h1>
	</div>
	<form action="options.php" method="post">
		<?php settings_fields( Accredible_Learndash_Admin_Setting::OPTION_GROUP ); ?>
		<div class="accredible-form-field">
			<label for="api_key">API Key</label>
			<input
				type='text'
				name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ); ?>'
				value='<?php echo esc_html( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ); ?>' 
				id='api_key'/>
		</div>


		<div class="accredible-form-field">
			<div class="label">Server Region</div>
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
					<label for='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_US ); ?>'>US</label>
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
					<label for='<?php echo esc_html( Accredible_Learndash_Admin_Setting::SERVER_REGION_EU ); ?>'>EU</label>
				</div>
			</div>
		</div>

		<?php submit_button( 'Save', 'primary', 'submit', false ); ?>
	</form>
</div>
