<?php

/**
 * The plugin bootstrap file
 * @link              https://worldwebtechnology.com
 * @since             1.0.0
 * @package           Wwt_Elementor_Display_Condition_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       WWT Elementor Display Condition
 * Plugin URI:        https://worldwebtechnology.com
 * Description:       WWT Elementor Display Condition allows to display section based on query parameter in URL.
 * Version:           1.0.0
 * Author:            world web technology
 * Author URI:        https://worldwebtechnology.com
 * Text Domain:       wwt-elementor-disp-cond-addon
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}


require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (!is_plugin_active( 'elementor/elementor.php' )) {
       deactivate_plugins( plugin_basename( __FILE__ ) );
       wp_die( __( 'To Activate WWT Elementor Display Condition Plugin, Please Activate Elementor.', 'wwt-elementor-disp-cond-addon' ), 'Plugin dependency check', array( 'back_link' => true ) );
}


define( 'WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_VERSION', '1.0.0' );
define( 'WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_PATH',plugin_dir_path( __FILE__ ));


function activate_wwt_elementor_disp_cond_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wwt-elementor-display-condition-addon-activator.php';
	Wwt_Elementor_Display_Condition_Addon_Activator::activate();
}

function deactivate_wwt_elementor_disp_cond_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wwt-elementor-display-condition-addon-deactivator.php';
	Wwt_Elementor_Display_Condition_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wwt_elementor_disp_cond_addon' );
register_deactivation_hook( __FILE__, 'deactivate_wwt_elementor_disp_cond_addon' );

require plugin_dir_path( __FILE__ ) . 'includes/class-wwt-elementor-display-condition-addon.php';


require_once plugin_dir_path( __FILE__ ) . 'includes/ea-display-conditions/ea-controls-handler-wwt.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php';


function run_wwt_elementor_disp_cond_addon() {

	$plugin = new Wwt_Elementor_Display_Condition_Addon();
	$plugin->run();

}
run_wwt_elementor_disp_cond_addon();