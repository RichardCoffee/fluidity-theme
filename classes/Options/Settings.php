<?php

class TCC_Options_Settings extends TCC_Options_Options {


	protected $base     = 'admin';
	protected $priority = 500;


	protected function form_title() {
		return __( 'Settings', 'tcc-fluid' );
	}

	protected function form_icon() {
		return 'dashicons-admin-settings';
	}

	public function describe_options() {
		esc_html_e( 'Theme Behavior Options', 'tcc-fluid' );
	}

	protected function options_layout() {
		$layout = array( 'default' => true );
		$layout['login'] = array( );
		$layout['autocomplete'] = array(
			'default' => 'internal',
			'label'   => __( 'Autocomplete', 'tcc-fluid' ),
			'text'    => __( 'Use a plugin to handle autocompletion on the search field, or let the theme handle it.', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'internal' => __( 'Use theme code.', 'tcc-fluid' ),
				'external' => __( "Use a plugin. CSS selector is 'searchform-input'.", 'tcc-fluid' ),
			),
		);
		if ( WP_DEBUG ) {
			$layout['where'] = array(
				'default' => 'off',
				'label'   => __( 'Where Am I?', 'tcc-fluid' ),
				'text'    => __( 'Display template file names on site front end - for development only', 'tcc-fluid' ),
				'help'    => __( 'Hi!', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'on'   => __( 'On', 'tcc-fluid' ),
					'off'  => __( 'Off', 'tcc-fluid' ),
				),
			);
		}
		$wpd_status = ( WP_DEBUG ) ? __( 'ON', 'tcc-fluid' ) : __( 'OFF', 'tcc-fluid' );
		$layout['wp_debug'] = array(
			'label'  => __( 'WP Debug status', 'tcc-fluid' ),
			'text'   => sprintf( __( 'WP Debug is %s', 'tcc-fluid' ), $wpd_status ),
			'render' => 'display',
		);
		return apply_filters( "tcc_{$this->base}_options_layout", $layout );
	}

	protected function customizer_data() {
		$data = array(
			array(
			),
		);
		return apply_filters( "fluid_{$this->base}_customizer_data", $data );
	}


}
