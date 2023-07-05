<?php
/**
 * Url_Referer Condition Handler.
 */
namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager;
use WwtAddons\Includes\Helper_Functions_WWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Url_Referer extends Condition_WWT {
	
	public function get_control_options() {

		return array(
			'label'       => __( 'Value', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => 'Value',
			'description' => __( 'Enter Value', 'wwt-elementor-disp-cond-addon' ),
			'condition'   => array(
				'pa_condition_key_wwt' => 'url_referer',
			),
		);
	}

	public function add_value_control() {

		return array(
			'label'          => __( 'Parameter', 'wwt-elementor-disp-cond-addon' ),
			'type'           => Controls_Manager::TEXT,
			'label_block'    => true,
			'placeholder' => 'Parameter',
			'description' => __( 'Enter Parameter', 'wwt-elementor-disp-cond-addon' ),
			'condition'   => array(
				'pa_condition_key_wwt' => 'url_referer',
			),
		);
	}

	public function compare_value( $settings, $operator, $value, $compare_val, $tz ) {

		if ( ! isset( $_SERVER['REQUEST_URI'] ) || empty( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}


		$url = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );

		if ( ! $url || ! isset( $url['query'] ) || empty( $url['query'] ) ) {
			return false;
		}

		 $query_params = explode( '&', $url['query'] );

		 $param = sanitize_text_field( $compare_val ); 
		 $value = sanitize_text_field( $value );	

		
		$values[] = $param .'='. $value ;		


		foreach ( $values as $index => $param ) {			

			$is_strict = strpos( $param, '=' );
			if ( ! $is_strict ) {				

				$ref = isset( $_GET[ $param ] ) ? sanitize_text_field( wp_unslash( $_GET[ $param ] ) ) : '';

				$values[ $index ] = $values[ $index ] . '=' . rawurlencode( $ref );				

			}
		}	


		$condition_result = ! empty( array_intersect( $values, $query_params ) ) ? true : false;

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );

	}

}
