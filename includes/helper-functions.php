<?php
/**
 * PA Helper Functions.
 */

namespace WwtAddons\Includes;

// Elementor Classes.
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Plugin;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Helper_Functions.
 */
class Helper_Functions_WWT {	
	
	public static function get_final_result( $condition_result, $operator ) {

		if ( 'is' === $operator ) {
			return true === $condition_result;
		} else {
			return true !== $condition_result;
		}
	}	

}
