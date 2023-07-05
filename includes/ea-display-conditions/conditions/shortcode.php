<?php
/**
 * Shortcode Condition Handler.
 */

namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager;
use WwtAddons\Includes\Helper_Functions_WWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Shortcode extends Condition_WWT {
	
	public function get_control_options() {

		return array(
			'label'       => __( 'Value', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::TEXTAREA,
			'options'     => array(),
			'label_block' => true,
			'condition'   => array(
				'pa_condition_key_wwt' => 'shortcode',
			),
		);

	}
	
	public function add_value_control() {

		return array(
			'label'       => __( 'Shortcode', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'description' => __( 'Insert the shortcode you want to check its result value.', 'wwt-elementor-disp-cond-addon' ),
			'condition'   => array(
				'pa_condition_key_wwt' => 'shortcode',
			),
		);

	}

	public function compare_value( $settings, $operator, $compare_val, $shortcode, $tz ) {

		if ( empty( $shortcode ) ) {
			return false;
		}

		$return_value = do_shortcode( shortcode_unautop( $shortcode ) );

		$condition_result = $return_value == $compare_val ? true : false;

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );

	}

}
