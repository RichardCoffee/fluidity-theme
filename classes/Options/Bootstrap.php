<?php

class TCC_Options_Bootstrap {

	private $base     = 'bootstrap';
	private $priority = 34; # customizer priority

	public function __construct() {
		add_filter( 'fluidity_options_form_layout', array( $this, 'form_layout' ), 10 );
		add_action( 'fluid-customizer', array( $this, 'options_customize_register' ), $this->priority, 2 );
	}

	private function form_title() {
		return __( 'Bootstrap', 'tcc-fluid' );
	}

	public function form_layout( $form ) {
		$form[ $this->base ] = array(
			'describe' => array( $this, 'describe_options' ),
			'title'    => $this->form_title(),
			'option'   => 'tcc_options_' . $this->base,
			'layout'   => $this->options_layout()
		);
		return $form;
	}

	public function describe_options() { ?>
		<span>
			<?php esc_html_e( 'Bootstrap Components - specify which bootstrap components to load.', 'tcc-fluid' ); ?>
		</span><?php
	}

	protected function options_layout() {
		$layout = array('default'=>true);
		$layout['reset-deps'] = array(
			'default' => $this->get_bootstrap_defaults('reset'),
			'label'   => __( 'Reset/Dependencies', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list('reset'),
		); //*/
		$layout['core'] = array(
			'default' => $this->get_bootstrap_defaults('core'),
			'label'   => __( 'Core CSS', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list('core'),
		); //*/
		$layout['components'] = array(
			'default' => $this->get_bootstrap_defaults('components'),
			'label'   => __( 'Components', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list('components'),
		); //*/
		$layout['javascript'] = array(
			'default' => $this->get_bootstrap_defaults('javascript'),
			'label'   => __( 'Javascript', 'tcc-fluid' ),
			'text'    => __( 'Components with Javascript', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list('javascript'),
		); //*/
		$layout['utilities'] = array(
			'default' => $this->get_bootstrap_defaults('utilities'),
			'text'    => __( 'Utilities', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list('utilities'),
		); //*/
		$layout = apply_filters( "tcc_{$this->base}_options_layout", $layout );
		return $layout;
	}

	protected function get_bootstrap_defaults( $section ) {
		$list   = $this->get_bootstrap_list( $section );
		$return = tcc_bootstrap( $section );
		if ( empty( $return ) ) {
			$return = array();
		}
		if ( $list ) {
			foreach( $list as $key => $text ) {
				if ( ! isset( $return [ $key ] ) ) {
					$return[ $key ] = 'no';
				}
			}
		}
		return $return;
	}

	protected function get_bootstrap_list( $section ) {
		$return = array();
			switch( $section ) {
				case 'reset':
					$return = array(
						'normalize'  => __( 'Normalize', 'tcc-fluid' ),
						'print'      => __( 'Print', 'tcc-fluid' ),
						'glyphicons' => __( 'Glyphicons', 'tcc-fluid' ),
					);
					break;
				case 'core':
					$return = array(
						'scaffolding' => __( 'Scaffolding', 'tcc-fluid' ),
						'type'        => __( 'Type', 'tcc-fluid' ),
						'code'        => __( 'Code', 'tcc-fluid' ),
						'grid'        => __( 'Grid', 'tcc-fluid' ),
						'tables'      => __( 'Tables', 'tcc-fluid' ),
						'forms'       => __( 'Forms', 'tcc-fluid' ),
						'buttons'     => __( 'Buttons', 'tcc-fluid' ),
					);
					break;
				case 'components':
					$return = array(
						'component-animations' => __( 'Component Animations', 'tcc-fluid' ),
						'dropdowns'            => __( 'Dropdowns', 'tcc-fluid' ),
						'button-groups'        => __( 'Button Groups', 'tcc-fluid' ),
						'input-groups'         => __( 'Input Groups', 'tcc-fluid' ),
						'navs'                 => __( 'Navs', 'tcc-fluid' ),
						'navbar'               => __( 'Navbar', 'tcc-fluid' ),
						'breadcrumbs'          => __( 'Breadcrumbs', 'tcc-fluid' ),
						'pagination'           => __( 'Pagination', 'tcc-fluid' ),
						'pager'                => __( 'Pager', 'tcc-fluid' ),
						'labels'               => __( 'Labels', 'tcc-fluid' ),
						'badges'               => __( 'Badges', 'tcc-fluid' ),
						'jumbotron'            => __( 'Jumbotron', 'tcc-fluid' ),
						'thumbnails'           => __( 'Thumbnails', 'tcc-fluid' ),
						'alerts'               => __( 'Alerts', 'tcc-fluid' ),
						'progress-bars'        => __( 'Progress Bars', 'tcc-fluid' ),
						'media'                => __( 'Media', 'tcc-fluid' ),
						'list-group'           => __( 'List Group', 'tcc-fluid' ),
						'panels'               => __( 'Panels', 'tcc-fluid' ),
						'responsive-embed'     => __( 'Responsive Embed', 'tcc-fluid' ),
						'wells'                => __( 'Wells', 'tcc-fluid' ),
						'close'                => __( 'Close', 'tcc-fluid' ),
					);
					break;
				case 'javascript':
					$return = array(
						'modals'   => __( 'Modals', 'tcc-fluid' ),
						'tooltip'  => __( 'Tooltip', 'tcc-fluid' ),
						'popovers' => __( 'Popovers', 'tcc-fluid' ),
						'carousel' => __( 'Carousel', 'tcc-fluid' ),
					);
					break;
				case 'utilities':
					$return = array(
						'utilities'            => __( 'Utilities', 'tcc-fluid' ),
						'responsive-utilities' => __( 'Responsive Utilities', 'tcc-fluid' ),
					);
					break;
				default:
			}
		return $return;
	}

	public function options_customize_register( $wp_customize, TCC_Options_Fluidity $form ) {
		$wp_customize->add_section( 'fluid_' . $this->base, array( 'title' => $this->form_title(), 'priority' => $this->priority ) );
		$form->customizer_settings( $wp_customize, $this->base );
	}


}

function tcc_bootstrap( $option ) {
	static $data;
	if (empty($data)) { $data = get_option('tcc_options_bootstrap'); }
	if (isset($data[$option])) { return $data[$option]; }
	return '';
}
