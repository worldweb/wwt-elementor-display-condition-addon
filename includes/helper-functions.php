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

	public static function get_woo_categories( $id = 'slug' ) {

		$product_cat = array();

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$product_categories = get_terms( 'product_cat', $cat_args );

		if ( ! empty( $product_categories ) ) {

			foreach ( $product_categories as $key => $category ) {

				$cat_id                 = 'slug' === $id ? $category->slug : $category->term_id;
				$product_cat[ $cat_id ] = $category->name;

			}
		}

		return $product_cat;
	}


}
