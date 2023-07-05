<?php
/**
 * Woocommerce Total Amount in Cart Condition Handler.
 */
namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager;
use WwtAddons\Includes\Helper_Functions_WWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Total_Price extends Condition_WWT {
	
	public function get_control_options() {

		return array(
			'label'       => __( 'Equal or Higher than', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::NUMBER,
			'description' => __( 'Set the minimum amount in the cart to be checked.', 'wwt-elementor-disp-cond-addon' ),
			'min'         => 0,
			'condition'   => array(
				'pa_condition_key_wwt' => 'woo_total_price',
			),
		);

	}
	
	public function add_value_control() {		

		return array(
			'label'       => __( 'Source', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'subtotal' => __( 'Subtotal Amount', 'wwt-elementor-disp-cond-addon' ),
				'total'    => __( 'Total Amount', 'wwt-elementor-disp-cond-addon' ),
			),
			'default'     => 'subtotal',
			'label_block' => true,
			'condition'   => array(
				'pa_condition_key_wwt' => 'woo_total_price',
			),
		);

	}

	public function compare_value( $settings, $operator, $compare_val, $value, $tz ) {

		$cart = WC()->cart;

		if ( $cart->is_empty() ) {
			return false;
		}

		if ( 'total' === $value ) {
			$cart_total = $cart->total;
		} else {
			$cart_total = $cart->get_displayed_subtotal();
		}

		$condition_result = (int) $compare_val <= $cart_total ? true : false;

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );
	}

}
