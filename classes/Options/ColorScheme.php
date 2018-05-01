<?php

class TCC_Options_ColorScheme {

	public function color_scheme_controls( $options ) {
		$controls = array(
			'scheme' => array(
				'default' => 'random',
				'label'   => __( 'Color Scheme', 'tcc-fluid' ),
				'description' => __( 'Choose the color scheme you wish to use', 'tcc-fluid' ),
				'render'  => 'radio',
				'choices' => array_merge(
					array(
						'none'   => __( 'Do not use a color scheme', 'tcc-fluid' ),
						'random' => __( 'Random color scheme on each page load.', 'tcc-fluid' ),
					),
					$this->get_available_color_schemes()
				),
			),
		);
		$options['colors'] = array(
			'section'  => null,
			'controls' => $controls
		); //*/
		return $options;
	}

	public function get_available_color_schemes( $colors = array() ) {
		$path   = FLUIDITY_HOME . 'css/colors';
		$avail  = scandir( $path );
		foreach( $avail as $file ) {
			if ( in_array( $file, array( '.', '..' ), true ) ) {
				continue;
			}
			if ( ! ( pathinfo( $file, PATHINFO_EXTENSION ) === 'css' ) ) {
				continue;
			}
			$data = get_file_data( $path . '/' . $file, array( 'name' => 'Name' ) );
			if ( $data && ! empty( $data['name'] ) ) {
				$index = pathinfo( $file, PATHINFO_FILENAME );
				$colors[ $index ] = $data['name'];
			}
		}
		return $colors; // apply_filters( 'fluid_available_color_schemes', $colors );
	}


}
