<?php

class TCC_Options_ColorScheme {


	public function __construct() {
		add_filter( 'fluid_support_custom_background',          [ $this, 'fluid_support_custom_background' ] );
		add_filter( 'theme_mod_background_image_scheme_custom', [ $this, 'theme_mod_background_image_scheme_custom' ] );
		add_filter( 'pre_set_theme_mod_background_image_scheme_custom', [ $this, 'pre_set_theme_mod_background_image_scheme_custom' ], 10, 2 );
	}


/***   Color Schemes   ***/

	public function color_scheme_controls( $options ) {
		$options['colors'] = array(
			'section'  => null,
			'controls' => array(
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
			),
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
			if ( $data && ( ! empty( $data['name'] ) ) ) {
				$index = pathinfo( $file, PATHINFO_FILENAME );
				$colors[ $index ] = $data['name'];
			}
		}
		return $colors; // apply_filters( 'fluid_available_color_schemes', $colors );
	}


/***   Background Images for Color Schemes   ***/

#	 * @since 20180713
	public function custom_background_controls( $options ) {
		$scheme = fluid_color_scheme();
		$description = sprintf( __( 'Assign this background image to current scheme: %s', 'tcc-fluid' ), $scheme );
		$options['background_image'] = array(
			'section'  => null,
			'controls' => array(
				'scheme_text' => array(
					'label'  => __( 'Color Scheme', 'tcc-fluid' ),
					'render' => 'content',
					'sanitize_callback' => '__return_true',
				),
				'scheme_custom' => array(
					'default' => false,
					'label'   => $description,
					'render'  => 'checkbox',
				),
			),
		);
		return $options;
	}

#	 * @since 20180713
	public function theme_mod_background_image_scheme_custom( $value ) {
		if ( is_customize_preview() ) {
fluid()->log( $value );
		}
		return $value;
	}

#	 * @since 20180713
	public function pre_set_theme_mod_background_image_scheme_custom( $value, $old_value ) {
fluid()->log( $_POST );
		return $value;
	}

	public function fluid_support_custom_background( $settings ) {
		$custom = get_theme_mod( 'background_image_scheme_custom', false );
		if ( $custom ) {
			$scheme = 'color_scheme_' . fluid_color_scheme();
			$image  = get_theme_mod( $scheme, '' );
			if ( (bool) $image ) {
fluid()->log( $image );
#				$settings['default-image'] = $image;
			}
		}
		return $settings;
	}


}
