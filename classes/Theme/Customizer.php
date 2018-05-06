<?php
/**
 * classes/Theme/Customizer.php
 *
 */
require_once( 'Typography.php' );
/**
 * setup for customizer options
 *
 * @since 20180419
 * @link https://w3guy.com/wordpress-customizer-multiple-settings-single-customize-control/
 *
 */
class TCC_Theme_Customizer {

	public $base_cap = 'edit_theme_options';

	public function __construct( $args = array() ) {
		add_action( 'customize_register',                 array( $this, 'customize_register' ), 11, 1 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
		add_action( 'wp_ajax_fluid_postdate_display',     array( $this, 'fluid_postdate_display') );
	}

	public function customize_controls_enqueue_scripts() {
		wp_enqueue_style(  'fluid-customizer.css', get_theme_file_uri( 'css/customizer.css' ), null, FLUIDITY_VERSION);
		wp_enqueue_script( 'fluid-customizer.js',  get_theme_file_uri( 'js/customizer.js' ), [ 'customize-preview', 'customize-selective-refresh' ], FLUIDITY_VERSION, true);
		$options = apply_filters( 'fluid_customize_controls_localization', [ ] );
		if ( $options ) {
			wp_localize_script( 'fluid-customizer.js', 'fluid_customize', $options );
		}
	}

	public function customize_register( WP_Customize_Manager $customize ) {
		$this->register_theme_controls( $customize );
		$this->assign_postmessage( $customize );
	}

	public function register_theme_controls( WP_Customize_Manager $customize ) {
		$panels = apply_filters( 'fluid_customizer_panels', $this->get_customizer_panels( array() ) );
		if ( ! empty( $panels ) ) {
			foreach( $panels as $panel_id => $panel ) {
				$args = $this->get_panel_defaults( $panel );
				$args = apply_filters( "fluid_customizer_panels_$panel_id", $panel );
				$customize->add_panel( $panel_id, $panel );
			}
		}
		$sections = apply_filters( 'fluid_customizer_controls', $this->get_customizer_controls( array() ) );
		foreach( $sections as $section_id => $section ) {
			if ( ! empty( $section['section'] ) ) {
				$args = $this->get_section_defaults( $section['section'] );
				$customize->add_section( $section_id, $args );
			}
			$order    = 0;
			$controls = apply_filters( "fluid_customizer_controls_$section_id", $section['controls'] );
			foreach( $controls as $control_id => $control ) {
				$setting_id = $section_id . '_' . $control_id;
				$args       = $this->get_setting_defaults( $control );
				$customize->add_setting( $setting_id, $args );
				$priority = ( isset( $control['priority'] ) ) ? $control['priority'] : ( $order += 10 );
				new TCC_Form_Control_Customizer( compact( 'customize', 'section_id', 'setting_id', 'control', 'priority' ) );
			}
		}
	}

	public function assign_postmessage( WP_Customize_Manager $customize ) {
		$customize->remove_control('background_color');
		$customize->get_setting( 'blogname' )->transport = 'postMessage';
	}

	public function assign_partials( WP_Customize_Manager $customize ) {
		$customize->selective_refresh->add_partial(
			'content_postdate',
			array(
				'selector' => '#fluid_content_post_dates',
				'render_callback' => function() {
					fluid_show_post_dates();
				},
				'container_inclusive' => false,
			)
		);
	}

	public function get_panel_defaults( $panel ) {
		$defaults = array(
			'priority'        => 160,
			'capability'      => $this->base_cap,
			'theme_supports'  => '',
			'title'           => __( 'Panel Title', 'tcc-fluid' ),
			'description'     => __( 'Panel Description', 'tcc-fluid' ),
			'type'            => 'default',
			'active_callback' => '',
			'auto_expand_sole_section' => true
		);
		return array_merge( $defaults, $panel );
	}

	public function get_section_defaults( $section ) {
		$defaults = array(
			'priority'           => 10,
			'panel'              => '',
			'capability'         => $this->base_cap,
			'theme_supports'     => '',// plugins only - section will not display if unsupported
			'title'              => __( 'Section Title', 'tcc-fluid' ),
			'description'        => __( 'Section description text', 'tcc-fluid' ),
			'type'               => 'default',       // determines js template used
			'active_callback'    => '__return_true', // determines whether the control is initially active
			'description_hidden' => true,            // will display button to show description
		);
		return array_merge( $defaults, $section );
	}

	public function get_setting_defaults( $setting ) {
		$defaults = array(
			'type'                 => 'theme_mod', // 'option',
			'capability'           => $this->base_cap,
			'theme_supports'       => '',// plugins only
			'default'              => '',
			'transport'            => 'refresh', // 'postMessage',
			'validate_callback'    => '',
			'sanitize_callback'    => array( fluid_sanitize(), $setting['render'] ),
			'sanitize_js_callback' => '',
			'dirty'                => array(), // wtf?
		);
		return array_merge( $defaults, array_intersect_key( $setting, $defaults ) );
	}

	public function control_defaults( $control ) {
		$defaults = array(
			'settings'    => array(),
			'setting'     => '',
			'capability'  => $this->base_cap,
			'priority'    => 10,
			'section'     => '',
			'label'       => __( 'Control Label', 'tcc-fluid' ),
			'description' => __( 'Control Description', 'tcc-fluid' ),
			'choices'     => array(), // used only if type = 'radio' or 'select' only
			'type'        => $control['render'],
			'input_attrs' => array(),
			'allow_addition'  => '', // used only if type = 'dropdown-pages'
			'active_callback' => '__return_true',
		);
		return array_merge( $defaults, array_intersect_key( $control, $defaults ) );
	}

	public function get_customizer_panels() {
		$panels = array(
			'fluid_mods' => array(
				'priority'    => 10,
				'title'       => __( 'Theme Behavior', 'tcc-fluid' ),
				'description' => __( 'All of these options affect how various aspects of the theme will act at various times.', 'tcc-fluid' ),
			),
		);
		return $panels;
	}

	public function get_customizer_controls( $options = array() ) {
		$options = $this->screen_width( $options );
		$options = $this->theme_sidebar( $options );
		$options = $this->widget_collapse( $options );
		$options = $this->content_controls( $options );
		$options = fluid_color()->color_scheme_controls( $options );
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
		$options['behavior'] = array(
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
			'fluidity' => array(
				'default'     => 'static',
				'label'       => __( 'Fluid Sidebar', 'tcc-fluid' ),
				'description' => __( 'Let content flow around sidebar', 'tcc-fluid' ),
				'render'      => 'radio',
				'choices'     => array(
					'static' => __( 'Static content', 'tcc-fluid' ),
					'fluid'  => __( 'Fluid content', 'tcc-fluid' ),
				),
				'showhide' => array(
					'control' => 'sidebar_position',
					'action'  => 'hide',
					'setting' => 'none'
				),
			),
			'mobile' => array(
				'default'     => 'bottom',
				'label'       => __( 'Mobile Sidebar', 'tcc-fluid' ),
				'description' => __( 'Where should the sidebar show up on mobile devices?', 'tcc-fluid' ),
				'render'      =>'radio',
				'choices'     => array(
					'none'   => __( 'Do not show sidebar on mobile devices', 'tcc-fluid' ),
					'top'    => __( 'Before post content', 'tcc-fluid' ),
					'bottom' => __( 'After post content', 'tcc-fluid' ),
				),
				'showhide' => array(
					'control' => 'sidebar_position',
					'action'  => 'hide',
					'setting' => 'none'
				),
			),
		);
		$options['sidebar'] = array(
			'section'  => $section,
			'controls' => $controls
		);
		return $options;
	}

	public function widget_collapse( $options ) {
		$section = array(
			'priority'    => 60,
			'panel'       => 'fluid_mods',
			'title'       => __( 'Widget Collapse', 'tcc-fluid' ),
			'description' => __( 'This section controls the use and details concerning collapsible widgets.', 'tcc-fluid' )
		);
		$controls = array(
			'collapse' => array(
				'default'     => 'perm',
				'label'       => __( 'Widgets', 'tcc-fluid' ),
				'description' => __( 'Should the sidebar widgets start open or closed, where applicable', 'tcc-fluid' ),
				'render'      => 'radio',
				'choices'     => array(
					'perm'   => __( 'Do not provide option to users','tcc-fluid' ),
					'open'   => __( 'Open', 'tcc-fluid' ),
					'closed' => __( 'Closed', 'tcc-fluid' ),
				),
			),
			'icons' => array(
				'default'     => 'none',
				'label'       => __( 'Widget Icons', 'tcc-fluid' ),
				'description' => __( 'Choose the icon set used for the widgets', 'tcc-fluid' ),
				'render'      => 'htmlradio',
				'choices'     => $this->widget_icons(),
				'sanitize_callback' => [ fluid_sanitize(), 'radio' ],
				'showhide' => array(
					'control' => 'widgyt_collapse',
					'action'  => 'hide',
					'setting' => 'perm'
				),
			),
		);
		$options['widgyt'] = array(
			'section'  => $section,
			'controls' => $controls
		); //*/
		return $options;
	}

	public function widget_icons() {
		$library = fluid();
		$choices = array(
			'none'    => __( 'Do not use an icon set - let the lusers figure it out for themselves...', 'tcc-fluid' ),
		);
		$icons = $library->get_widget_fawe();
		$fawe_format = _x( 'Open %1$s / Close %2$s', 'display open and close icons for use with the sidebar widgets', 'tcc-fluid' );
		foreach( $icons as $key => $set ) {
			$plus  = $library->get_fawe( $set['plus']  . ' fa-fw' );
			$minus = $library->get_fawe( $set['minus'] . ' fa-fw' );
			$choices[ $key ] = sprintf( $fawe_format, $plus, $minus );
		}
		return $choices;
	}

	public function content_controls( $options ) {
		$section = array(
			'priority'    => 80,
			'panel'       => 'fluid_mods',
			'title'       => __( 'Content', 'tcc-fluid' ),
			'description' => __( 'All settings dealing with the blog content`', 'tcc-fluid' )
		);
		$controls = array(
			'postdate' => array(
				'default'   => 'original',
				'transport' => 'postMessage',
				'label'     => __( 'Displayed Publish/Edit Date', 'tcc-fluid' ),
				'render'    => 'radio',
				'choices'   => array(
					'both'     => __( 'Show both modified and original post date when showing full post content', 'tcc-fluid' ),
					'modified' => __( 'Use modified post date, where applicable.', 'tcc-fluid' ),
					'original' => __( 'Always use published post date.', 'tcc-fluid' ),
					'none'     => __( 'Never show the post date.', 'tcc-fluid' ),
				),
				'active_callback' => function() {
					return is_single();
				}, // PHP Catchable fatal error:  Object of class WP_Customize_Manager could not be converted to string in /home1/zxkrycvs/rtcenterprises.net/wp-includes/class-wp-query.php on line 3827
				'add_partial' => array(
					'id' => 'content_postdate',
					'args' => array(
						'selector' => '#fluid_content_post_dates',
						'render_callback' => function() {
							fluid_show_post_dates();
						},
						'container_inclusive' => false,
					),
				),
			),
		);
		$options['content'] = array(
			'section'  => $section,
			'controls' => $controls
		);
		return $options;
	}


/***   postMessage functions   ***/

	public function fluid_postdate_display() {
		fluid_show_post_dates();
		wp_die();
	}


}
