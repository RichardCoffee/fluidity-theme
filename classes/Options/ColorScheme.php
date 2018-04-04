<?php

class TCC_Options_ColorScheme {


	protected $base     = 'colorscheme';
	protected $priority = 85;


	protected function form_title() {
		return __( 'Color Scheme', 'tcc-fluid' );
	}

	public function describe_options() {
		_e( "Control the theme's color scheme.", 'tcc-fluid' );
	}

	protected function options_layout() {
		$layout  = array( 'default' => true );
		$schemes = $this->get_available_color_schemes( array( 'none' => __( 'Do not use internal color scheme', 'tcc-fluid' ) ) );
#		$active  = tcc_options( 'active',  $this->base, 'none' );
#		$data    = tcc_options( 'schemes', $this->base, array() );
		$layout['active'] = array(
			'default' => 'none',
			'label'   => __( 'Color Scheme', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => $schemes,
		);
		foreach( $schemes as $file => $name ) {

		}
	}

	public function get_available_color_schemes( $colors = array() ) {
#	private function get_available_color_schemes( $colors = array() ) {
		$path   = FLUIDITY_HOME . 'css/colors';
		$avail  = scandir( $path );
fluid()->log($colors,$avail);
		foreach( $avail as $file ) {
			if ( in_array( $file, array( '.', '..' ), true ) ) {
				continue;
			}
			if ( strpos( $file, '.css' ) === false ) {
				continue;
			}
			$data = get_file_data( $path . '/' . $file, array( 'name' => 'Name' ) );
			if ( $data && ! empty( $data['name'] ) ) {
				$colors[ $file ] = $data['name'];
			}
		}
		return $colors;
	}

	protected function customizer_data() {
		$data = array(
			array(
			),
		);
		return apply_filters( "fluid_{$this->base}_customizer_data", $data );
	}


}
