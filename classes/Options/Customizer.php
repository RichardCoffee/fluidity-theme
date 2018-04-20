<?php

require_once( 'Typography.php' );

class TCC_Options_Customizer {

	protected $theme;

	public function __construct( $args = array() ) {
		add_action( 'customize_register', array( $this, 'customize_register' ), 11, 1 );
		$this->theme = new TCC_Theme_Customizer;
	}

	protected function get_controls() {
		return apply_filters( 'fluid_customizer_controls', $this->theme->customizer_controls() );
	}

	public function customize_register( WP_Customize_Manager $customize ) {
		wp_enqueue_style( 'fluid-customizer.css', get_theme_file_uri( 'css/customizer.css' ), null, FLUIDITY_VERSION);
#		wp_enqueue_style( 'fluid-customizer.js',  get_theme_file_uri( 'js/customizer.js' ),   null, FLUIDITY_VERSION);
		$controls = $this->get_controls();
		foreach( $controls as $section ) {
			$priority = 0;
			$section_id = $section['id'];
			$section['section'] = $this->theme->section_defaults( $section['section'] );
			$customize->add_section( $section['id'], $section['section'] );
			$controls = apply_filters( "fluid_customizer_controls_{$section['id']}", $section['controls'] );
			foreach( $controls as $key => $control ) {
				$priority  += 10;
				$setting_id = $section_id . '_' . $key;
				$control    = $this->theme->setting_defaults( $control );
				$customize->add_setting( $setting_id, $control );
				new TCC_Form_Customizer( compact( 'customize', 'section_id', 'setting_id', 'control', 'priority' ) );
			}
		}
	}


}
