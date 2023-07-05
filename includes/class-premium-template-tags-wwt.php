<?php
/**
 *
 * PA Premium Temlpate Tags.
 */

namespace WwtAddons\Includes;
use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Premium_Template_Tags_WWT {

	protected static $instance;	
	public static $settings;	
	public static $page_limit;
	protected $options;

	public function __construct() {
		
	}
	
	public static function getInstance() {

		if ( ! static::$instance ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	
	public static function get_default_posts_list( $post_type ) {
		$list = get_posts(
			array(
				'post_type'              => $post_type,
				'posts_per_page'         => -1,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'fields'                 => array( 'ids' ),
			)
		);

		$options = array();

		if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
			foreach ( $list as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
		}

		return $options;

	}	

}