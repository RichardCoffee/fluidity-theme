<?php

class TCC_Options_Bootstrap extends TCC_Options_Options {

	protected $base     = 'bootstrap';
	protected $priority = 600;

	protected function form_title() {
		return __( 'Bootstrap', 'tcc-fluid' );
	}

	public function describe_options() { ?>
		<span>
			<?php esc_html_e( 'Bootstrap Components - specify which bootstrap components to load.', 'tcc-fluid' ); ?>
		</span><?php
	}

	protected function options_layout() {
		$layout = array( 'default' => true );
		$layout['resets'] = array(
			'default' => $this->get_bootstrap_defaults( 'reset' ),
			'label'   => __( 'Reset/Dependencies', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list( 'reset' ),
		); //*/
		$layout['core'] = array(
			'default' => $this->get_bootstrap_defaults( 'core' ),
			'label'   => __( 'Core CSS', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list( 'core' ),
		); //*/
		$layout['components'] = array(
			'default' => $this->get_bootstrap_defaults( 'components' ),
			'label'   => __( 'Components', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list( 'components' ),
		); //*/
		$layout['javascript'] = array(
			'default' => $this->get_bootstrap_defaults( 'javascript', 'no' ),
			'label'   => __( 'Javascript', 'tcc-fluid' ),
			'text'    => __( 'Components with Javascript', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list( 'javascript' ),
		); //*/
		$layout['utility'] = array(
			'default' => $this->get_bootstrap_defaults( 'utilities' ),
			'label'   => __( 'Utilities', 'tcc-fluid' ),
			'render'  => 'radio_multiple',
			'source'  => $this->get_bootstrap_list( 'utilities' ),
		); //*/
		$layout = apply_filters( "tcc_{$this->base}_options_layout", $layout );
		return $layout;
	}

	protected function get_bootstrap_defaults( $section, $default = 'yes' ) {
		$list   = $this->get_bootstrap_list( $section );
		$return = tcc_bootstrap( $section );
		if ( empty( $return ) ) {
			$return = array();
		}
		if ( $list ) {
			foreach( $list as $key => $text ) {
				if ( ! isset( $return [ $key ] ) ) {
					$return[ $key ] = $default;
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


}
