<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Wwt_Elementor_Display_Condition_Addon
 * @subpackage Wwt_Elementor_Display_Condition_Addon/admin
 */

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Repeater;

class Wwt_Elementor_Display_Condition_Addon_Admin {

	
	private $plugin_name;	
	private $version;
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	public function enqueue_styles() {		

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wwt-elementor-disp-cond-addon-admin.css', array(), $this->version, 'all' );

	}
	
	public function enqueue_scripts() {		

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wwt-elementor-disp-cond-addon-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wwt_register_controls_disp_cond($element){		

		$element->start_controls_section(
			'section_pa_display_conditions_wwt',
			array(
				'label' => sprintf( '%s', __( 'WWT Display Conditions', 'wwt-elementor-disp-cond-addon' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$controls_obj = new EA_Controls_Handler_WWT();

		$options = $controls_obj::$conditions;	


		$element->add_control(
			'pa_display_conditions_switcher_wwt',
			array(
				'label'              => __( 'Enable Display Conditions', 'premium-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'render_type'        => 'template',
				'prefix_class'       => 'pa-display-conditions-wwt',
				'frontend_available' => true,
			)
		);


		$element->add_control(
			'pa_display_action_wwt',
			array(
				'label'     => __( 'Action', 'wwt-elementor-disp-cond-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'show',
				'options'   => array(
					'show' => __( 'Show Element', 'wwt-elementor-disp-cond-addon' ),
					'hide' => __( 'Hide Element', 'wwt-elementor-disp-cond-addon' ),
				),
				'condition' => array(
					'pa_display_conditions_switcher_wwt' => 'yes',
				),
			)
		);

		$element->add_control(
			'pa_display_when_wwt',
			array(
				'label'     => __( 'Display When', 'wwt-elementor-disp-cond-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'any',
				'options'   => array(
					'all' => __( 'All Conditions Are Met', 'wwt-elementor-disp-cond-addon' ),
					'any' => __( 'Any Condition is Met', 'wwt-elementor-disp-cond-addon' ),
				),
				'condition' => array(
					'pa_display_conditions_switcher_wwt' => 'yes',
				),
			)
		);

		$repeater = new Repeater();

		if ( class_exists( 'woocommerce' ) ) {
			$options = array_merge(
				$options,
				array(
					'woocommerce' => array(
						'label'   => __( 'WooCommerce', 'wwt-elementor-disp-cond-addon' ),
						'options' => array(							
							'woo_orders'        => __( 'Purchased/In Cart Orders', 'wwt-elementor-disp-cond-addon' ),
							'woo_category'      => __( 'Purchased/In Cart Categories', 'wwt-elementor-disp-cond-addon' ),
							'woo_last_purchase' => __( 'Last Purchase In Cart', 'wwt-elementor-disp-cond-addon' ),
							'woo_total_price'   => __( 'Amount In Cart', 'wwt-elementor-disp-cond-addon' ),
							'woo_cart_products' => __( 'Products In Cart', 'wwt-elementor-disp-cond-addon' ),
						),
					),
				)
			);
		}

		$repeater->add_control(
			'pa_condition_key_wwt',
			array(
				'label'       => __( 'Type', 'wwt-elementor-disp-cond-addon' ),
				'type'        => Controls_Manager::SELECT,
				'groups'      => $options,
				'default'     => 'url_referer',
				'label_block' => true,
			)
		);

		$options_conditions = apply_filters(
			'pa_pro_display_conditions_wwt',
			array(				
				'url_referer',
				'woo_total_price',
				'woo_cart_products',
				'woo_orders',
				'url_string',
				'shortcode'		
			)
		);
		

		$controls_obj->add_repeater_source_controls( $repeater );

		$repeater->add_control(
			'pa_condition_operator_wwt',
			array(
				'type'        => Controls_Manager::SELECT,
				'default'     => 'is',
				'label_block' => true,
				'options'     => array(
					'is'  => __( 'Is', 'wwt-elementor-disp-cond-addon' ),
					'not' => __( 'Is Not', 'wwt-elementor-disp-cond-addon' ),
				),
				'condition'   => array(
					'pa_condition_key_wwt!' => $options_conditions,
				),
			)
		);

		$controls_obj->add_repeater_compare_controls( $repeater );

		$should_apply = apply_filters( 'pa_display_conditions_values_wwt', true );

		$values = $repeater->get_controls();
		
		$element->add_control(
			'pa_condition_repeater_wwt',
			array(
				'label'         => __( 'Conditions', 'wwt-elementor-disp-cond-addon' ),
				'type'          => Controls_Manager::REPEATER,
				'label_block'   => true,
				'fields'        => $values,
				'title_field'   => '<# print( pa_condition_key_wwt.replace(/_/g, " ").split(" ").map((s) => s.charAt(0).toUpperCase() + s.substring(1)).join(" ")) #>',
				'prevent_empty' => false,
				'condition'     => array(
					'pa_display_conditions_switcher_wwt' => 'yes',
				),
			)
		);		

		$element->end_controls_section();	
	}

}