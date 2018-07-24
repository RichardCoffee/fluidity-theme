<?php

class TCC_Options_Settings extends TCC_Options_Options {


	protected $base     = 'admin';
	protected $priority = 500;


	protected function form_title() {
		return __( 'Settings', 'tcc-fluid' );
	}

	public function describe_options() {
		esc_html_e( 'Theme Behavior Options', 'tcc-fluid' );
	}

	protected function options_layout() {
		$layout = array( 'default' => true );
		$layout['heart'] = array(
			'default' => 'on',
			'label'   => __( 'WP Heartbeat', 'tcc-fluid' ),
			'text'    => __( 'Control the status of the WordPress Heartbeat API', 'tcc-fluid' ),
			'help'    => __( 'The Heartbeat API will always remain active on these pages: post.php, post-new.php, and admin.php', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'on'   => __( 'On', 'tcc-fluid' ),
				'off'  => __( 'Off', 'tcc-fluid' ),
			),
		);
/*		$layout['rest_api'] = array(
			'default' => 'on',
			'label'   => __( 'REST API', 'tcc-fluid' ),
			'text'    => __( 'Control access to your site REST API', 'tcc-fluid' ),
			'help'    => __( 'Be very careful with this option.  Any value other than ON runs the risk of breaking your site!', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'on'    => __( 'On - the default value', 'tcc-fluid' ),
				'users' => __( 'Allow access to logged-in users only.', 'tcc-fluid' ),
				'off'   => __( 'Off - this may break things.', 'tcc-fluid' ),
			),
		); //*/
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
