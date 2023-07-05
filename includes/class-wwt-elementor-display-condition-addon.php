<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Wwt_Elementor_Display_Condition_Addon
 * @subpackage Wwt_Elementor_Display_Condition_Addon/includes
 */

class Wwt_Elementor_Display_Condition_Addon {

	
	protected $loader;	
	protected $plugin_name;	
	protected $version;
	
	public function __construct() {
		if ( defined( 'WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_VERSION' ) ) {
			$this->version = WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wwt-elementor-display-condition-addon';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();		

	}

	
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wwt-elementor-display-condition-addon-loader.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wwt-elementor-display-condition-addon-i18n.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wwt-elementor-display-condition-addon-admin.php';	

		$this->loader = new Wwt_Elementor_Display_Condition_Addon_Loader();

	}
	
	private function set_locale() {

		$plugin_i18n = new Wwt_Elementor_Display_Condition_Addon_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	private function define_admin_hooks() {

		$plugin_admin = new Wwt_Elementor_Display_Condition_Addon_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'elementor/element/container/section_layout/after_section_end', $plugin_admin, 'wwt_register_controls_disp_cond',99);
		$this->loader->add_action( 'elementor/element/section/section_advanced/after_section_end', $plugin_admin, 'wwt_register_controls_disp_cond',99);
		$this->loader->add_action( 'elementor/element/column/section_advanced/after_section_end', $plugin_admin, 'wwt_register_controls_disp_cond',99);
		$this->loader->add_action( 'elementor/element/common/_section_style/after_section_end', $plugin_admin, 'wwt_register_controls_disp_cond',99);

	}

	
	public function run() {
		$this->loader->run();
	}

	
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	
	public function get_loader() {
		return $this->loader;
	}

	
	public function get_version() {
		return $this->version;
	}

}
