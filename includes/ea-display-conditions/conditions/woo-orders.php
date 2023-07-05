<?php
/**
 * Woocommerce Orders Condition Handler.
 */
namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager;
use WwtAddons\Includes\Helper_Functions_WWT;

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly.
}

class Woo_Orders extends Condition_WWT {
	
	public function get_control_options() {

		return array(
			'label'       => __( 'Number of Items', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'description' => __( 'Enter 0 to check if empty. Any other value will be the minimum number of items to check.', 'wwt-elementor-disp-cond-addon' ),
			'condition'   => array(
				'pa_condition_key_wwt' => 'woo_orders',
			),
		);

	}
	
	public function add_value_control() {

		return array(
			'label'       => __( 'Status', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'in-cart',
			'label_block' => true,
			'options'     => array(
				'in-cart'   => __( 'In Cart', 'wwt-elementor-disp-cond-addon' ),
				'purchased' => __( 'Purchased', 'wwt-elementor-disp-cond-addon' ),
			),
			'condition'   => array(
				'pa_condition_key_wwt' => 'woo_orders',
			),
		);

	}
		
	public function compare_value( $settings, $operator, $compare_val, $value, $tz ) {

		if ( '' === $compare_val ) {
			return true;
		}

		if ( 'in-cart' === $value ) {
			$item_count = WC()->cart->get_cart_contents_count();
		} else {

			$args = array(
				'customer_id' => get_current_user_id(),
				'status'      => array( 'wc-completed' ),
			);

			$item_count = count( wc_get_orders( $args ) );
		}

		if ( 0 === (int) $compare_val ) {
			$condition_result = (int) $compare_val === $item_count ? true : false;

		} else {
			$condition_result = (int) $compare_val <= $item_count ? true : false;
		}

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );
	}

}
