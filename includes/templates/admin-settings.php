<?php
/**
 * Accredible LearnDash Add-on admin settings page template.
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __DIR__ ) . '/class-accredible-learndash-admin-issuer.php';

// phpcs:disable WordPress.WhiteSpace
if ( empty( get_option( Accredible_Learndash_Admin_Setting::OPTION_API_KEY ) ) ) {
	$accredible_learndash_issuer = null;
} else {
	$accredible_learndash_issuer = Accredible_Learndash_Admin_Issuer::search_issuer();
}
?>

<div class="accredible-wrapper">
  <div class="accredible-header-tile">
    <h1 class="title"><?php esc_html_e( 'Settings' ); ?></h1>
  </div>
  <div class="accredible-content">
    <form action="options.php" method="post">
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

      <?php submit_button( 'Save', 'accredible-primary', 'submit', false ); ?>
    </form>

    <div class="status-tile">
      <div class="logo">
        <img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/learndash_logo.png' ); ?>" alt="LearnDash logo">
      </div>
      <div class="status">
      <!-- TODO: Replace with value to check if setting exists -->
      <?php if ( $accredible_learndash_issuer ) { ?>
        <img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/check.png' ); ?>">
        <span><?php esc_html_e( 'Integration is up and running' ); ?></span>
      <?php } else { ?>
        <img src="<?php echo esc_url( ACCREDIBLE_LEARNDASH_PLUGIN_URL . 'assets/images/error.png' ); ?>">
        <span><?php esc_html_e( 'Integration is not working' ); ?></span>
      <?php } ?>
      </div>
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
