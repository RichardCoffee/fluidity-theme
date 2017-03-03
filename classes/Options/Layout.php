<?php



class TCC_Options_Layout {

	private $base     = 'layout';
	private $priority = 35; # customizer priority

	public function __construct() {
		add_filter( 'fluidity_options_form_layout', array( $this, 'form_layout' ), $this->priority );
		add_action( 'fluid-customizer', array( $this, 'options_customize_register' ), $this->priority, 2 );
	}

	private function form_title() {
		return __( 'Layout', 'tcc-fluid' );
	}

	public function form_layout( $form ) {
		$form[ $this->base ] = array(
			'describe' => array( $this, 'describe_options' ),
			'title'    => $this->form_title(),
			'option'   => 'tcc_options_'.$this->base,
			'layout'   => $this->options_layout()
		);
		return $form;
	}

	public function describe_options() {
		_e( "Utilize these options to change the theme's style and layout.  These options may one day show up in the WordPress Customizer.", 'tcc-fluid' );
	}

	protected function options_layout() {
		$layout = array( 'default' => true );
		$layout['width'] = array(
			'default' => 'full',
			'label'   => __( 'Width', 'tcc-fluid' ),
			'text'    => __( 'How much screen real estate do you want the theme to use?', 'tcc-fluid' ),
			'help'    => __( 'This determines the margins for the main body of the website', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'fluid'  => __( 'Full Width (small margins)', 'tcc-fluid' ),
				'narrow' => __( 'Standard Margins', 'tcc-fluid' ),
			),
		);
		$layout['header'] = array(
			'default' => 'static',
			'label'   => __( 'Header', 'tcc-fluid' ),
			'text'    => __( 'How do you want the header to behave?', 'tcc-fluid' ),
			'render'  =>'radio',
			'source'  => array(
				'static' => __( 'Static - Simple standard layout', 'tcc-fluid' ),
				'fixed'  => __( 'Fixed - Stays at top of screen when scrolling', 'tcc-fluid' ),
				'reduce' => __( 'Reducing - Gets smaller when scrolling down', 'tcc-fluid' ),
				'hide'   => __( 'Hiding - Hidden when scrolling, show on hover', 'tcc-fluid' ),
			),
		);
		$layout['menu']    = array(
			'default' => 'underscore',
			'label'   => __( 'Menu', 'tcc-fluid' ),
			'text'    => __( 'Which menuing system do you want to use?', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'underscore' => __( 'Underscores - WordPress starter theme', 'tcc-fluid' ),
				'bootstrap'  => __( 'Bootstrap - web site front-end framework', 'tcc-fluid' ),
			),
		);
		if ( WP_DEBUG ) {
			$layout['main_css'] = array(
				'default' => 'col-lg-9 col-md-9 col-sm-12 col-xs-12',
				'label'   => __( 'Main CSS', 'tcc-fluid' ),
				'render'  => 'text',
			);
			$layout['sidebar_css'] = array(
				'default' => 'col-lg-3 col-md-3 col-sm-12 col-xs-12',
				'label'   => __( 'Sidebar CSS', 'tcc-fluid' ),
				'render'  => 'text',
			);
		}
		$layout['sidebar'] = array(
			'default' => 'left',
			'label'   => __( 'Sidebar', 'tcc-fluid' ),
			'text'    => __( 'Which side of the screen should the sidebar show up on?', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'none'  => __( 'No Sidebar', 'tcc-fluid' ),
				'left'  => __( 'Left side', 'tcc-fluid' ),
				'right' => __( 'Right side', 'tcc-fluid' ),
			),
			'change'  => 'showhidePosi( this, ".mobile-sidebar-setting", null, "none" );',
			'divcss'  => 'mobile-sidebar-active',
		);
		$layout['mobile_sidebar'] = array(
			'default' => 'bottom',
			'label'   => __( 'Mobile Sidebar', 'tcc-fluid' ),
			'text'    => __( 'Where should the sidebar show up on mobile devices?', 'tcc-fluid' ),
			'render'  =>'radio',
			'source'  => array(
				'none'   => __( 'Do not show sidebar on mobile devices', 'tcc-fluid' ),
				'top'    => __( 'Before post content', 'tcc-fluid' ),
				'bottom' => __( 'After post content', 'tcc-fluid' ),
			),
			'divcss'   => 'mobile-sidebar-setting',
		);
		$layout['widget'] = array(
			'default' => 'perm',
			'label'   => __( 'Widgets', 'tcc-fluid' ),
			'text'    => __( 'Should the sidebar widgets start open or closed, where applicable', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'perm'   => __( 'Do not provide option to users','tcc-fluid' ),
				'open'   => __( 'Open', 'tcc-fluid' ),
				'closed' => __( 'Closed', 'tcc-fluid' ),
			),
		);
		$layout['content'] = array(
			'default' => 'excerpt',
			'label'   => __( 'Blog/News/Search', 'tcc-fluid' ),
			'text'    => __( 'Show full post content or just an excerpt on archive/category/search pages', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'content' => __( 'Content', 'tcc-fluid' ),
				'excerpt' => __( 'Excerpt', 'tcc-fluid' ),
			),
		);
		$layout['exdate'] = array(
			'default' => 'show',
			'label'   => __( 'Excerpt Date', 'tcc-fluid' ),
			'text'    => __( 'Should the post date be displayed with excerpt?', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'none' => __( 'No Date', 'tcc-fluid' ),
				'show' => __( 'Show Date', 'tcc-fluid' ),
			),
		);
		$layout['exlength'] = array(
			'default' => apply_filters( 'excerpt_length', 55 ),
			'label'   => __( 'Excerpt Length', 'tcc-fluid' ),
			'render'  => 'spinner',
			'stext'   => __( 'number of words', 'tcc-fluid' ),
		);
		$layout = apply_filters( "tcc_{$this->base}_options_layout", $layout );
		return $layout;
	}

	public function options_customize_register($wp_customize, TCC_Options_Fluidity $form) {
		$wp_customize->add_section( 'fluid_'.$this->base, array('title' => $this->form_title(), 'priority' => $this->priority));
		$form->customizer_settings($wp_customize,$this->base);
	}


}
