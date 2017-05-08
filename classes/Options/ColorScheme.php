<?php

class TCC_Options_ColorScheme {


	protected $base     = 'colorscheme';
	protected $priority = 85;


	protected function form_title() {
		return __( 'Color Scheme', 'tcc-fluid' );
	}

	public function describe_options() {
		_e( 'Control the theme\'s color scheme.', 'tcc-fluid' );
	}

	protected function options_layout() {
		$layout = array( 'default' => true );
		$schemes = $this->get_available_color_schemes();
		$active  = tcc_options( 'active', $this->base );
		foreach( $schemes as $scheme => $data ) {
			
		}
	}

	private function get_available_color_schemes() {
	}


}
