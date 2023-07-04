<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Wwt_Elementor_Display_Condition_Addon
 * @subpackage Wwt_Elementor_Display_Condition_Addon/includes
 */

class Wwt_Elementor_Display_Condition_Addon_i18n {
	
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wwt-elementor-disp-cond-addon',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
