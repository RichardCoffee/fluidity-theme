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

	public function panel_defaults( $panel ) {
		$defaults = array(
			'priority'        => 160,
			'capability'      => $this->base_cap,
#			'theme_supports'  =>
			'title'           => __( 'Panel Title', 'tcc-fluid' ),
			'description'     => __( 'Panel Description', 'tcc-fluid' ),
#			'type'            => 'default',
#			'active_callback' =>
			'auto_expand_sole_section' => true
		);
		return array_merge( $defaults, $panel );
	}

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
			'label'       => __( 'Control Label', 'tcc-fluid' ),
#			'description' => __( 'Control Description', 'tcc-fluid' ),
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

	public function customizer_panels() {
		$panels = array(
			array(
				'id' => 'fluid_mods',
				'args' => array(
					'priority'    => 10,
					'title'       => __( 'Theme Behavior', 'tcc-fluid' ),
					'description' => __( 'All of these options affect how various aspects of the theme will act at various times.', 'tcc-fluid' ),
				),
			),
		);
		return $panels;
	}

	public function customizer_controls( $options = array() ) {
		$options = $this->screen_width( $options );
		$options = $this->theme_sidebar(  $options );
		return $options;
	}

	public function screen_width( $options ) {
		$section = array(
			'priority'    => 20,
			'panel'       => 'fluid_mods',
			'title'       => __( 'Desktop Screen Width', 'tcc-fluid' ),
			'description' => __( 'This section controls the overall behavior of the theme.', 'tcc-fluid' )
		);
		$controls = array(
			'screen_width' => array(
				'default'     => 'narrow',
				'label'       => __( 'Width', 'tcc-fluid' ),
				'description' => __( 'How much screen real estate do you want the theme to use?', 'tcc-fluid' ),
				'title'       => __( 'This determines the margins for the main body of the website', 'tcc-fluid' ),
				'render'      => 'radio',
				'choices'     => array(
					'full'   => __( 'Full Width (small margins)', 'tcc-fluid' ),
					'narrow' => __( 'Standard Margins (recommended)', 'tcc-fluid' ),
				),
			),
		);
/*		if ( WP_DEBUG ) {
			$controls['menu']    = array(
				'default'     => 'bootstrap',
				'label'       => __( 'Menu', 'tcc-fluid' ),
				'description' => __( 'Which menuing system do you want to use?', 'tcc-fluid' ),
				'title'       => __( '', 'tcc-fluid' ),
				'render'      => 'radio',
				'choices'     => array(
					'underscore' => __( 'Underscores - WordPress starter theme', 'tcc-fluid' ),
					'bootstrap'  => __( 'Bootstrap - web site front-end framework', 'tcc-fluid' ),
				),
			);
		} //*/
		$options[] = array(
			'id'       => 'behavior',
			'section'  => $section,
			'controls' => $controls
		);
		return $options;
	}

	public function theme_sidebar( $options ) {
		$section = array(
			'priority'    => 40,
			'panel'       => 'fluid_mods',
			'title'       => __( 'Sidebar Behavior', 'tcc-fluid' ),
			'description' => __( 'This section controls things dealing with the sidebar.  My, how informative that was...', 'tcc-fluid' )
		);
		$controls = array(
			'position' => array(
				'default'     => 'right',
				'label'       => __( 'Sidebar', 'tcc-fluid' ),
				'description' => __( 'Which side of the screen should the sidebar show up on?', 'tcc-fluid' ),
				'render'      => 'radio',
				'choices'     => array(
					'none'  => __( 'No Sidebar', 'tcc-fluid' ),
					'left'  => __( 'Left side', 'tcc-fluid' ),
					'right' => __( 'Right side', 'tcc-fluid' ),
				),
				
			),
		);

/*
'change'  => 'showhidePosi( this, ".no-sidebar-setting", null, "none" );',
'divcss'  => 'no-sidebar-active',
'showhide' => array(
'origin' => 'no-sidebar-active',
'target' => 'no-sidebar-setting',
'hide'   => 'none',
),
'thememod' => 'sidebar_placement',
);
*/

		$controls['fluidity'] = array(
			'default'     => 'no',
			'label'       => __( 'Fluid Sidebar', 'tcc-fluid' ),
			'description' => __( 'Let content flow around sidebar', 'tcc-fluid' ),
			'render'      => 'radio',
			'choices'     => array(
				'no'  => __( 'Static content', 'tcc-fluid' ),
				'yes' => __( 'Fluid content', 'tcc-fluid' ),
			),
			'active_callback' => function() {
				return ( ! ( get_theme_mod( 'sidebar_position' ) === 'none' ) );
			}
/*
'change'   => 'showhidePosi( this, ".fluid-sidebar-setting", "no" );',
'divcss'   => 'fluid-sidebar-active no-sidebar-setting',
'showhide' => array(
'origin' => 'fluid-sidebar-active',
'target' => 'fluid-sidebar-setting',
'show'   => 'no',
),
'thememod' => 'sidebar_fluid',
*/
		);
		$options[] = array(
			'id'       => 'sidebar',
			'section'  => $section,
			'controls' => $controls
		);
		return $options;
	}


}
