<?php
/**
 * classes/Theme/Customizer.php
 *
 */
/**
 * setup for customizer options
 *
 * @since 20180419
 * @link https://w3guy.com/wordpress-customizer-multiple-settings-single-customize-control/
 *
 */
class TCC_Theme_Customizer {

	public $base_cap = 'edit_theme_options';

	public function __construct() { }

	public function section_defaults( $section ) {
		$defaults = array(
			'priority'           => 2,
#			'panel'              =>
			'capability'         => $this->base_cap,
#			'theme_supports'     => // plugins only - section will not display if unsupported
			'title'              => __( 'Section Title', 'tcc-fluid' ),
#			'description'        => __( 'Section description text', 'tcc-fluid' ),
#			'type'               => 'default', // determines js template used
#			'active_callback'    => '',        // determines whether the control is initially active
			'description_hidden' => true,      // will display button to show description
		);
		return array_merge( $defaults, $section );
	}

	public function setting_defaults( $setting ) {
		$defaults = array(
			'type'                 => 'theme_mod', // 'option',
			'capability'           => $this->base_cap,
#			'theme_supports'       => // plugins only
			'default'              => '',
			'transport'            => 'refresh', // 'postMessage',
#			'validate_callback'    => '', // when is this called?
			'sanitize_callback'    => array( fluid_sanitize(), $setting['render'] ),
#			'sanitize_js_callback' => '',
#			'dirty'                => array(), // wtf?
		);
		return array_merge( $defaults, $setting );
	}

	public function control_defaults( $control ) {
		$defaults = array(
#			'settings'    => array( $this->setting_id ),
#			'setting'
			'capability'  => $this->base_cap,
			'priority'    => 2,
#			'section'     => $this->section_id,
#			'label'       => $this->control['label'],
#			'description' => $this->control['description'],
#			'choices'     => array(), // used only if type = 'radio' or 'select' only
#			'type'        => $control['render'],
/*			'input_attrs' => array(
				'class' => 'my-custom-class-for-js',
				'style' => 'border: 1px solid #900',
				'placeholder' => __( 'mm/dd/yyyy' ),
			), //*/
#			'allow_addition'  => '', // used only if type = 'dropdown-pages'
#			'active_callback' => '', // 'is_front_page',
		);
		$control = array_merge( $defaults, $control );
		if ( empty( $control['description'] ) && isset( $control['text'] ) ) {
			$control['description'] = $control['text'];
		}
		if ( empty( $control['type'] ) ) {
			$control['type'] = $control['render'];
		}
		return $control;
	}

	public function customizer_controls() {
	}


}
