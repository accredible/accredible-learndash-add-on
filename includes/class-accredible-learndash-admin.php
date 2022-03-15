<?php
/**
 * Accredible LearnDash Add-on admin main class
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-database.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-setting.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-auto-issuance.php';
require_once plugin_dir_path( __FILE__ ) . '/class-accredible-learndash-admin-scripts.php';

if ( ! class_exists( 'Accredible_Learndash_Admin' ) ) :
	/**
	 * Accredible LearnDash Add-on admin main class.
	 */
	final class Accredible_Learndash_Admin {
		/**
		 * Initialize plugin admin hooks.
		 */
		public static function init() {
			return new self();
		}

		/**
		 * Accredible_Learndash_Admin constructor.
		 */
		public function __construct() {
			$this->set_activation_hooks();
			$this->set_admin_hooks();
		}

		/**
		 * Initialize WP activation hooks.
		 */
		private function set_activation_hooks() {
			register_activation_hook(
				ACCREDILBE_LEARNDASH_PLUGIN_BASENAME,
				array( 'Accredible_Learndash_Admin_Setting', 'set_default' )
			);
			register_activation_hook(
				ACCREDILBE_LEARNDASH_PLUGIN_BASENAME,
				array( 'Accredible_Learndash_Admin_Database', 'setup' )
			);
		}

		/**
		 * Initialize WP admin hooks.
		 */
		private function set_admin_hooks() {
			$this->add_settings();
			$this->add_menus();
			$this->add_scripts();
			$this->add_style_classes();
		}

		/**
		 * Add plugin settings to WP admin.
		 */
		private function add_settings() {
			add_action( 'admin_init', array( 'Accredible_Learndash_Admin_Setting', 'register' ) );
		}

		/**
		 * Add plugin menus to WP admin.
		 */
		private function add_menus() {
			add_action( 'admin_menu', array( 'Accredible_Learndash_Admin_Menu', 'add' ) );
		}

		/**
		 * Add scripts to WP admin.
		 */
		private function add_scripts() {
			add_action( 'admin_enqueue_scripts', array( 'Accredible_Learndash_Admin_Scripts', 'load_resources' ) );
		}

		/**
		 * Add style classes to WP admin.
		 */
		private function add_style_classes() {
			add_filter( 'admin_body_class', array( 'Accredible_Learndash_Admin_Scripts', 'add_admin_body_class' ) );
		}
	}
endif;
