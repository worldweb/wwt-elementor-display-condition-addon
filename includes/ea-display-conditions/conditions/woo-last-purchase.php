<?php
/**
 * Woocommerce last purchase Condition Handler.
 */
namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;
use Elementor\Controls_Manager; 
use WwtAddons\Includes\Helper_Functions_WWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Last_Purchase extends Condition_WWT {
	
	public function get_control_options() {

		return array(
			'label'          => __( 'At or Before', 'wwt-elementor-disp-cond-addon' ),
			'type'           => Controls_Manager::DATE_TIME,
			'default'        => gmdate( 'Y/m/d' ),
			'label_block'    => true,
			'picker_options' => array(
				'format'     => 'Y-m-d',
				'enableTime' => false,
			),
			'label_block'    => true,
			'condition'      => array(
				'pa_condition_key_wwt' => 'woo_last_purchase',
			),
		);
	}
	
	public function compare_value( $settings, $operator, $value, $compare_val, $tz ) {

		$args = array(
			'customer_id' => get_current_user_id(),
			'status'      => array( 'wc-completed' ),
			'limit'       => 1,
			'orderby'     => 'date_completed',
			'order'       => 'DESC',
		);

		$order = wc_get_orders( $args );

		$date_completed = $order && $order[0] ? date( 'Y-m-d', strtotime( $order[0]->get_Date_completed() ) ) : false;

		
		$condition_result = $value >= $date_completed ? true : false;

		return Helper_Functions_WWT::get_final_result( $condition_result, $operator );
	}

}
