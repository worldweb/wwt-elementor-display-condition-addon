<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class EA_Controls_Handler_WWT {

	
	public static $conditions_classes = array();
	
	public static $conditions_keys = array();
	
	public static $conditions = array();
	
	protected $conditions_results_holder = array();
	
	public function __construct() {

		$this->init_conditions();
		$this->init_conditions_classes();

		$is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		
		if ( ! $is_edit_mode ) {
			$this->init_actions();
		}

	}
	
	public function init_conditions() {

		static::$conditions = array(

			'urlparams' => array(
				'label'   => __( 'URL', 'wwt-elementor-disp-cond-addon' ),
				'options' => array(					
					'url_referer' => __( 'URL Parameters', 'wwt-elementor-disp-cond-addon' ),
				),
			)			

		);

	}
	
	public function init_conditions_classes() {

		self::$conditions_keys = apply_filters(
			'pa_display_conditions_keys',
			array(
				'url_referer'
			)
		);		

		include_once WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_PATH. 'includes/ea-display-conditions/conditions/condition.php';	


		foreach ( self::$conditions_keys as $condition_key ) {

			$file_name = str_replace( '_', '-', strtolower( $condition_key ) );

			if ( file_exists( WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_PATH . 'includes/ea-display-conditions/conditions/' . $file_name . '.php' ) ) {

				include_once WWT_ELEMENTOR_DISPLAY_CONDITION_ADDON_PATH . 'includes/ea-display-conditions/conditions/' . $file_name . '.php';
			}	
			

			$class_name = __NAMESPACE__ . 'WwtAddons\Includes\EA_Display_Conditions\Conditions\\'.'Url_Referer';	

			if ( class_exists( $class_name ) ) {
				
				static::$conditions_classes[ $condition_key ] = new $class_name();
			}
			
		}
	}

	
	public function init_actions() {

		add_filter( 'elementor/frontend/widget/should_render', array( $this, 'should_render' ), 99, 2 );
		add_filter( 'elementor/frontend/column/should_render', array( $this, 'should_render' ), 99, 2 );
		add_filter( 'elementor/frontend/section/should_render', array( $this, 'should_render' ), 99, 2 );

		add_filter( 'elementor/frontend/container/should_render', array( $this, 'should_render' ), 99, 2 );
	}

	
	public function add_repeater_source_controls( $repeater ) {

		$additional_ids = array( 'pa_condition_shortcode', 'pa_condition_acf_text', 'pa_condition_acf_boolean', 'pa_condition_acf_choice', 'pa_condition_woo_orders', 'pa_condition_woo_category', 'pa_condition_woo_total_price', 'pa_condition_time_range', 'pa_condition_url_referer');

		foreach ( static::$conditions_classes as $condition_class_name => $condition_obj ) {

			$control_id = 'pa_condition_' . $condition_class_name;

			if ( in_array( $control_id, $additional_ids, true ) ) {
				$repeater->add_control(
					'pa_condition_val' . $condition_class_name,
					$condition_obj->add_value_control()
				);
			}
		}
	}
	
	public function add_repeater_compare_controls( $repeater ) {

		foreach ( static::$conditions_classes as $condition_class_name => $condition_obj ) {

			$control_id = 'pa_condition_' . $condition_class_name;

			$repeater->add_control(
				$control_id,
				$condition_obj->get_control_options()
			);

		}
	}

	
	public function should_render( $should_render, $element ) {

		$settings = $element->get_settings();

		if ( 'yes' === $settings['pa_display_conditions_switcher_wwt'] ) {	

			$element_id      = $element->get_id();
			$conditions_list = $settings['pa_condition_repeater_wwt'];
			$action          = $settings['pa_display_action_wwt'];


			$this->store_condition_results( $settings, $element_id, $conditions_list );

			return $this->check_visiblity( $element_id, $settings['pa_display_when_wwt'], $action );			

		}

		return $should_render;

	}
	
	protected function store_condition_results( $settings, $element_id, $lists = array() ) {

		if ( ! $lists ) {
			return;
		}
		

		foreach ( $lists as $key => $list ) {

			if ( ! in_array( $list['pa_condition_key_wwt'], self::$conditions_keys, true ) ) {				
				continue;
			}			

			$class = static::$conditions_classes[ $list['pa_condition_key_wwt'] ];
			$operator = $list['pa_condition_operator_wwt'];
			$item_key = 'pa_condition_' . $list['pa_condition_key_wwt'];
			$value    = isset( $list[ $item_key ] ) ? $list[ $item_key ] : '';


			$compare_val = isset( $list[ 'pa_condition_val' . $list['pa_condition_key_wwt'] ] ) ? $list[ 'pa_condition_val' . $list['pa_condition_key_wwt'] ] : '';

			if ( 'shortcode' !== $list['pa_condition_key_wwt'] ) {
				$compare_val = esc_html( $compare_val );
			}

			$id        = $item_key . '_' . $list['_id'];
			$time_zone = in_array( $list['pa_condition_key_wwt'], array( 'date_range', 'time_range', 'date', 'day' ), true ) ? $list['pa_condition_timezone'] : false;

			if ( 'ip_location' !== $list['pa_condition_key_wwt'] ) {

				// If ACF Text or Time Range, comparison must triggered.
				$check = ( in_array( $list['pa_condition_key_wwt'], array( 'time_range', 'acf_text' ) ) || '' !== $value ) ? $class->compare_value( $settings, $operator, $value, $compare_val, $time_zone ) : true;
			} else {

				$detect_method = $list['pa_condition_loc_method'];

				$check = $class->compare_location( $settings, $operator, $value, $compare_val, $time_zone, $detect_method );

			}

			$this->conditions_results_holder[ $element_id ][ $id ] = $check;
		}
	}
	
	public function check_visiblity( $element_id, $relation, $action ) {
		$result = true;	

		if ( ! array_key_exists( $element_id, $this->conditions_results_holder ) ) {
			return;
		}
		
		if ( 'all' === $relation ) {		

			$result = in_array( false, $this->conditions_results_holder[ $element_id ], true ) ? false : true;


		} else {			

			$result = in_array( true, $this->conditions_results_holder[ $element_id ], true ) ? true : false;
		}


		if ( ( 'show' === $action && $result ) || ( 'hide' === $action && false === $result ) ) {
			$should_render = true;
		} elseif ( ( 'show' === $action && false === $result ) || ( 'hide' === $action && $result ) ) {

			$should_render = false;
		}

		return $should_render;
	}

}
