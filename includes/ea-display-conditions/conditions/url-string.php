<?php
/**
 * Url_String Condition Handler.
 */

namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager; 
use WwtAddons\Includes\Helper_Functions_WWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Url_String extends Condition_WWT {
	
	public function get_control_options() {

		return array(
			'label'       => __( 'Value', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'description' => __( 'Enter the string you want to check if exists in the page URL.', 'wwt-elementor-disp-cond-addon' ),
			'condition'   => array(
				'pa_condition_key_wwt' => 'url_string',
			),
		);
	}	
	public function compare_value( $settings, $operator, $value, $compare_val, $tz ) {

		if ( ! isset( $_SERVER['REQUEST_URI'] ) || empty( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}

		$url = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		if ( ! $url ) {
			return false;
		}

		$condition_result = false !== strpos( $url, $value ) ? true : false;

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );

	}

}
