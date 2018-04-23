<?php
/**
 * classes/Options/Customizer.php
 *
 */
require_once( 'Typography.php' );
/**
 * handles hooks and initializing the theme controls
 *
 * @since 3.0.0
 */
class TCC_Options_Customizer {

	/**
	 * instance of helper class, TCC_Theme_Customizer
	 */
	protected $theme;


	public function __construct( $args = array() ) {
		add_action( 'customize_register', array( $this, 'customize_register' ), 11, 1 );
		$this->theme = new TCC_Theme_Customizer;
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
	}

	public function customize_controls_enqueue_scripts() {
		wp_enqueue_style(  'fluid-customizer.css', get_theme_file_uri( 'css/customizer.css' ), null, FLUIDITY_VERSION);
		wp_enqueue_script( 'fluid-customizer.js',  get_theme_file_uri( 'js/customizer.js' ),   null, FLUIDITY_VERSION, true);
	}

	public function customize_register( WP_Customize_Manager $customize ) {
		$panels = apply_filters( 'fluid_customizer_panels', $this->theme->customizer_panels( array() ) );
		if ( ! empty( $panels ) ) {
			foreach( $panels as $panel ) {
				$args = $this->theme->panel_defaults( $panel['args'] );
				$args = apply_filters( "fluid_customizer_panels_{$panel['id']}", $args );
				$customize->add_panel( $panel['id'], $args );
			}
		}
		$controls = apply_filters( 'fluid_customizer_controls', $this->theme->customizer_controls( array() ) );
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
				$mypriority = ( isset( $control['priority'] ) ) ? $control['priority'] : $priority;
				new TCC_Form_Customizer( compact( 'customize', 'section_id', 'setting_id', 'control', 'mypriority' ) );
			}
		}
	}


}
