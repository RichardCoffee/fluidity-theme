<?php

require_once( 'Typography.php' );

class TCC_Options_Customizer {


	public function __construct( $args = array() ) {
		add_action( 'customize_register', array( $this, 'customize_register' ), 11, 1 );
	}

	protected function get_controls() {
		return apply_filters( 'fluid_customizer_controls', array() );
	}

	public function customize_register( WP_Customize_Manager $customize ) {
		wp_enqueue_style( 'fluid-customizer.css', get_theme_file_uri( 'css/customizer.css' ), null, FLUIDITY_VERSION);
#		wp_enqueue_style( 'fluid-customizer.js',  get_theme_file_uri( 'js/customizer.js' ),   null, FLUIDITY_VERSION);
		$controls = $this->get_controls();
		foreach( $controls as $section ) {
			$priority = 0;
			$section_id = $section['id'];
			$section['section'] = $this->customizer_section( $section['section'] );
			$customize->add_section( $section['id'], $section['section'] );
			$controls = apply_filters( "fluid_customizer_controls_{$section['id']}", $section['controls'] );
			foreach( $controls as $key => $control ) {
				$priority  += 10;
				$setting_id = $section_id . '_' . $key;
				$control    = $this->customizer_control( $control );
				$customize->add_setting( $setting_id, $control );
				new TCC_Form_Customizer( compact( 'customize', 'section_id', 'setting_id', 'control', 'priority' ) );
			}
		}
	}

	protected function customizer_section( $section ) {
		$defaults = array(
			'priority'           => 2,
#			'panel'              =>
			'capability'         => 'edit_theme_options',
#			'theme_supports'     => // plugins only
			'title'              => __( 'Section Title', 'tcc-fluid' ),
			'description'        => __( 'Section description text', 'tcc-fluid' ),
#			'type'               =>
#			'active_callback'    => // determines whether the control is initially active
			'description_hidden' => true,
		);
		return array_merge( $defaults, $section );
	}

	protected function customizer_control( $control ) {
		$defaults = array(
			'type'                 => 'theme_mod', // 'option',
			'capability'           => 'edit_theme_options',
#			'theme_supports'       => // plugins only
			'default'              => '',
			'transport'            => 'refresh', // 'postMessage',
#			'validate_callback'    => '', // when is this called?
			'sanitize_callback'    => array( fluid_sanitize(), $control['render'] ),
#			'sanitize_js_callback' => '',
#			'dirty'                => array(), // wtf?
		);
		return array_merge( $defaults, $control );
	}


}
