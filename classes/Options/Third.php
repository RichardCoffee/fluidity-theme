<?php

class TCC_Options_Third extends TCC_Options_Options {

	protected $base     = 'third';
	protected $priority = 600;


	protected function form_title() {
		return __( 'Third Party', 'tcc-fluid' );
	}

	protected function form_icon() {
		return '';
	}

	public function describe_options() {
		esc_html_e( 'These are options for plugins that Fluidity has some integration for.', 'tcc-fluid' );
	}

	protected function options_layout() {
		return apply_filters( "tcc_{$this->base}_options_layout", array() );
	}


}
