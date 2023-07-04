<?php
/**
 * Condition blueprint for all available conditions.
 */

namespace WwtAddons\Includes\EA_Display_Conditions\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Abstract Class Condition.
 */
abstract class Condition_WWT {
	
	public function get_control_options() {}
	
	public function compare_value( $settings, $operator, $value, $compare_val, $tz ) {}
	
	public function compare_location( $settings, $operator, $value, $compare_val, $tz, $method ) {}

}
