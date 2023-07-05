<?php
/**
 * Woocommerce Products in Cart Condition Handler.
 */
namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager;
use WwtAddons\Includes\Helper_Functions_WWT;
use WwtAddons\Includes\Premium_Template_Tags_WWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Cart_Products extends Condition_WWT {
	
	public function get_control_options() {		

		$products = Premium_Template_Tags_WWT::get_default_posts_list( 'product' );

		return array(
			'label'       => __( 'Value', 'wwt-elementor-disp-cond-addon' ),
			'type'        => Controls_Manager::SELECT2,
			'default'     => array(),
			'label_block' => true,
			'options'     => $products,
			'multiple'    => true,
			'condition'   => array(
				'pa_condition_key_wwt' => 'woo_cart_products',
			),
		);

	}
	
	public function compare_value( $settings, $operator, $compare_val, $value, $tz ) {

		$cart = WC()->cart;

		$products_ids = array();

		if ( $cart->is_empty() ) {
			return false;
		}

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

			$product = $cart_item['data'];

			if ( $product->is_type( 'variation' ) ) {
				$product = wc_get_product( $product->get_parent_id() );
			}

			array_push( $products_ids, $product->get_id() );
		}

		$condition_result = ! empty( array_intersect( (array) $compare_val, $products_ids ) ) ? true : false;

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );
	}

}
