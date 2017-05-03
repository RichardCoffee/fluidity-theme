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
			'label'   => esc_html__( 'WP Heartbeat', 'tcc-fluid' ),
			'text'    => esc_html__( 'Control the status of the WordPress Heartbeat API', 'tcc-fluid' ),
			'help'    => esc_html__( 'The Heartbeat API will always remain active on these pages: post.php, post-new.php, and admin.php', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'on'   => esc_html__( 'On', 'tcc-fluid' ),
				'off'  => esc_html__( 'Off', 'tcc-fluid' ),
			),
		);
		$layout['rest_api'] = array(
			'default' => 'on',
			'label'   => __( 'REST API', 'tcc-fluid' ),
			'text'    => __( 'Control access to your site REST API', 'tcc-fluid' ),
			'help'    => __( 'Careful with this option.  Any value other than ON runs the risk of breaking your site!', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'on'    => __( 'On - the default value', 'tcc-fluid' ),
				'users' => __( 'Allow access to logged-in users only.', 'tcc-fluid' ),
				'off'   => __( 'Off - this may break things.', 'tcc-fluid' ),
			),
		);
		if ( WP_DEBUG ) {
			$layout['where'] = array(
				'default' => 'off',
				'label'   => esc_html__( 'Where Am I?', 'tcc-fluid' ),
				'text'    => esc_html__( 'Display template file names on site front end - for development only', 'tcc-fluid' ),
				'help'    => esc_html__( 'Hi!', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'on'   => esc_html__( 'On', 'tcc-fluid' ),
					'off'  => esc_html__( 'Off', 'tcc-fluid' ),
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



}
