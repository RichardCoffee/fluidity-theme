<?php

class TCC_Theme_ColorScheme {


	public static $scheme = '';


	public function __construct() {
		add_action( 'tcc_custom_css', [ $this, 'custom_colors' ] );
		add_filter( 'body_class',     [ $this, 'body_class' ] );
		add_filter( 'fluid_customizer_controls', [ $this, 'color_scheme_controls' ] );
		add_filter( 'fluid_customizer_controls', [ $this, 'custom_background_controls' ] );
#		add_filter( 'fluid_support_custom_background',          [ $this, 'fluid_support_custom_background' ] );
#		add_filter( 'theme_mod_background_image_scheme_custom', [ $this, 'theme_mod_background_image_scheme_custom' ] );
#		add_filter( 'pre_set_theme_mod_background_image_scheme_custom', [ $this, 'pre_set_theme_mod_background_image_scheme_custom' ], 10, 2 );
	}

	public function __toString() {
		return $this->color_scheme();
	}

	/***   Theme Functions   ***/

#	 * @since 20161216
	public function body_class( array $classes ) {
		$color = $this->color_scheme();
		if ( (bool) $color ) {
			$classes[] = "fluid-color-$color";
		}
		return $classes;
	}

#	 * @since 20160807
	public function color_scheme( $ext = false ) {
		if ( empty( static::$scheme ) ) {
			$scheme = '';
			$color  = get_theme_mod( 'colors_scheme', 'random' );
			if ( $color === 'none' ) {
				return '';
			} else if ( $color === 'random' ) {
				$color = $this->random_color_scheme();
			}
			$base = "/css/colors/$color.css";
			if ( is_readable( get_stylesheet_directory() . $base ) ) {
				$scheme = $color;
			} else if ( is_readable( get_template_directory() . $base ) ) {
				$scheme = $color;
			}
			static::$scheme = apply_filters( 'fluid_color_scheme', $scheme );
		}
		return static::$scheme;
	}

#	 * @since 20160807
	private function random_color_scheme() {
		$colors = array(
			'blue',
			'danger',
			'danger-inverse',
			'fire-engine',
			'green',
			'info',
			'info-inverse',
			'leaf-green',
			'orange',
			'primary',
			'red',
			'sea-blue',
			'success',
			'success-inverse',
			'warning',
			'warning-inverse',
#			'yellow',
		);
		$index = rand( 0, ( count( $colors ) - 1 ) );
		return $colors[ $index ];
	}

#	 * @since 20160807
	public function custom_colors() {
		$colors = get_theme_mod( 'custom_colors', array() );
		if ( ! empty( $colors ) ) {
			foreach( $colors as $key => $color ) {
				if ( empty( $color ) || ( $color === 'none' ) ) continue;
				echo "\n$key {\n\tcolor: $color;\n}\n";
			}
		}
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

#	 * TODO:  get child theme color schemes
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
		$options['background_image'] = array(
			'section'  => null,
			'controls' => array(
				'scheme_custom' => array(
					'default'     => false,
					'title'       => __( 'Color Scheme Image', 'tcc-fluid' ),
					'label'       => sprintf( __( 'Assign this background image to current scheme: %s', 'tcc-fluid' ), $this->color_scheme() ),
					'description' => '',
					'render'      => 'checkbox',
				),
			),
		);
		return $options;
	}

#	 * @since 20180713
	public function theme_mod_background_image_scheme_custom( $value ) {
		if ( is_customize_preview() ) {
fluid(1)->log( $value );
		}
		return $value;
	}

#	 * @since 20180713
	public function pre_set_theme_mod_background_image_scheme_custom( $value, $old_value ) {
fluid(1)->log( $_POST );
		return $value;
	}

	public function fluid_support_custom_background( $settings ) {
		$custom = get_theme_mod( 'background_image_scheme_custom', false );
		if ( $custom ) {
			$scheme = 'color_scheme_' . $this->color_scheme();
			$image  = get_theme_mod( $scheme, '' );
			if ( (bool) $image ) {
fluid(1)->log( $image );
#				$settings['default-image'] = $image;
			}
		}
		return $settings;
	}


}
