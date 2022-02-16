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

<h1>Settings</h1>
<form action="options.php" method="post">
	<?php settings_fields( Accredible_Learndash_Admin_Setting::OPTION_GROUP ); ?>
	<h3>API Key</h3>
	<div>
		<input
			type='text'
			name='<?php echo esc_html( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ); ?>'
			value='<?php echo esc_html( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ); ?>' />
  </div>
  <br />
	<h3>Server Region</h3>
  <div>
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
  <div>
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
  <?php submit_button( 'Save' ); ?>
</form>
