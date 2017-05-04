<?php

class TCC_Options_APIControl extends TCC_Options_Options {


	protected $base     = 'apicontrol';
	protected $priority = 570;


	protected function form_title() {
		return __( 'API Control', 'tcc-fluid' );
	}

	public function describe_options() {
		esc_html_e( 'REST API Control', 'tcc-fluid' );
	}

	protected function options_layout( $all = false ) {
		$endpoints = $this->get_endpoints();
		$layout = array( 'default' => true );
		$layout['status'] = array(
			'default' => 'on',
			'label'   => __( 'REST API', 'tcc-fluid' ),
			'text'    => __( 'Control access to your site REST API', 'tcc-fluid' ),
			'help'    => __( 'Be very careful with this option.  Any value other than ON runs the risk of breaking your site!', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'on'     => __( 'On - the default value.', 'tcc-fluid' ),
				'users'  => __( 'Allow access to logged-in users only.', 'tcc-fluid' ),
				'filter' => __( 'Filter public access.', 'tcc-fluid' ),
				'off'    => __( 'Off - this may break things.', 'tcc-fluid' ),
			),
		);
		return apply_filters( "tcc_{$this->base}_options_layout", $layout );
	}

	private function get_endpoints() {
		$request = new WP_REST_Request( 'GET', '/wp/v2/posts' );
		log_entry( $request );
	}



}
