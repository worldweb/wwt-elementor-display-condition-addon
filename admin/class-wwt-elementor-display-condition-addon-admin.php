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
				'label' => sprintf( '%s', __( 'WWT Display Conditions', 'premium-addons-for-elementor' ) ),
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
				'label'     => __( 'Action', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'show',
				'options'   => array(
					'show' => __( 'Show Element', 'premium-addons-for-elementor' ),
					'hide' => __( 'Hide Element', 'premium-addons-for-elementor' ),
				),
				'condition' => array(
					'pa_display_conditions_switcher_wwt' => 'yes',
				),
			)
		);

		$element->add_control(
			'pa_display_when_wwt',
			array(
				'label'     => __( 'Display When', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'any',
				'options'   => array(
					'all' => __( 'All Conditions Are Met', 'premium-addons-for-elementor' ),
					'any' => __( 'Any Condition is Met', 'premium-addons-for-elementor' ),
				),
				'condition' => array(
					'pa_display_conditions_switcher_wwt' => 'yes',
				),
			)
		);

		$repeater = new Repeater();

		$options = array('url_referer' => __( 'URL Parameters', 'premium-addons-for-elementor' ));

		$repeater->add_control(
			'pa_condition_key_wwt',
			array(
				'label'       => __( 'Type', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'groups'      => $options,
				'default'     => 'url_referer',
				'label_block' => true,
			)
		);

		$options_conditions = apply_filters(
			'pa_pro_display_conditions_wwt',
			array(				
				'url_referer'				
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
					'is'  => __( 'Is', 'premium-addons-for-elementor' ),
					'not' => __( 'Is Not', 'premium-addons-for-elementor' ),
				),
				'condition'   => array(
					'pa_condition_key_wwt!' => $options_conditions,
				),
			)
		);

		$controls_obj->add_repeater_compare_controls( $repeater );

		$should_apply = apply_filters( 'pa_display_conditions_values_wwt', true );

		$values = $repeater->get_controls();

		if ( $should_apply ) {
			$values = array_values( $values );
		}

		$element->add_control(
			'pa_condition_repeater_wwt',
			array(
				'label'         => __( 'Conditions', 'premium-addons-for-elementor' ),
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
