<?php

/* sources: http://wptheming.com/2012/06/loading-google-fonts-from-theme-options/
 *          http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
 *          http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */


class TCC_Theme_Typography {

	private static $option = 'tcc_options_design';

	public static function os_fonts() {
		// OS Font Defaults
		// TODO: load browser fonts (via js(?))
		$os_faces = array(
			'Arial'          => 'Arial, sans-serif',
			'Avant Garde'    => '"Avant Garde", sans-serif',
			'Cambria'        => 'Cambria, Georgia, serif',
			'Copse'          => 'Copse, sans-serif', // duplicate in google_fonts()
			'Garamond'       => 'Garamond, "Hoefler Text", Times New Roman, Times, serif',
			'Georgia'        => 'Georgia, serif',
			'Helvitica Neue' => '"Helvetica Neue", Helvetica, sans-serif',
			'Tahoma'         => 'Tahoma, Geneva, sans-serif'
		);
		return apply_filters( 'fluid_os_fonts', $os_faces );
	}

	public static function os_fonts_filter( $fonts ) {
		$check = array(
			'Arial'          => _x( 'on', "Arial font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Avant Garde'    => _x( 'on', "Avant Garde font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Cambria'        => _x( 'on', "Cambria font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Copse'          => _x( 'on', "Copse font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Garamond'       => _x( 'on', "Garamond font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Georgia'        => _x( 'on', "Georgia font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Helvitica Neue' => _x( 'on', "Helvitica Neue font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Tahoma'         => _x( 'on', "Tahoma font: translate as 'on' or 'off'", 'tcc-fluid' )
		);
		foreach( $check as $font => $state ) {
			if ( ( isset( $fonts[ $font ] ) ) && ( $state === 'off' ) ) {
				unset( $fonts[ $font ] );
			}
		}
		return $fonts; // apply_filters( 'fluid_os_fonts_filter', $fonts );
	}

	# TODO: https://github.com/paulund/wordpress-theme-customizer-custom-controls/blob/master/select/google-font-dropdown-custom-control.php
	public static function google_fonts() {
		// Google Font Defaults
		$google_faces = array(
			'Arvo'         => 'Arvo, serif',
			'Copse'        => 'Copse, sans-serif', // duplicate in os_fonts()
			'Droid Sans'   => 'Droid Sans, sans-serif',
			'Droid Serif'  => 'Droid Serif, serif',
			'Lato'         => 'Lato, sans-serif',
			'Lobster'      => 'Lobster, cursive',
			'Nobile'       => 'Nobile, sans-serif',
			'Open Sans'    => 'Open Sans, sans-serif',
			'Oswald'       => 'Oswald, sans-serif',
			'Pacifico'     => 'Pacifico, cursive',
			'Rokkit'       => 'Rokkitt, serif',
			'PT Sans'      => 'PT Sans, sans-serif',
			'Quattrocento' => 'Quattrocento, serif',
			'Raleway'      => 'Raleway, cursive',
			'Ubuntu'       => 'Ubuntu, sans-serif',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz, sans-serif'
		);
		return apply_filters( 'fluid_google_fonts', $google_faces );
	}

	public static function google_fonts_filter( $fonts ) {
		$check = array(
			'Arvo'         => _x( 'on', "Arvo font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Copse'        => _x( 'on', "Copse font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Droid Sans'   => _x( 'on', "Droid Sans font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Droid Serif'  => _x( 'on', "Droid Serif font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Lato'         => _x( 'on', "Lato font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Lobster'      => _x( 'on', "Lobster font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Nobile'       => _x( 'on', "Nobile font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Open Sans'    => _x( 'on', "Open Sans font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Oswald'       => _x( 'on', "Oswald font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Pacifico'     => _x( 'on', "Pacifico font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Rokkit'       => _x( 'on', "Rokkit font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'PT Sans'      => _x( 'on', "PT Sans font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Quattrocento' => _x( 'on', "Quattrocento font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Raleway'      => _x( 'on', "Raleway font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Ubuntu'       => _x( 'on', "Ubuntu font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Yanone Kaffeesatz' => _x( 'on', "Yanone Kaffeesatz font: translate as 'on' or 'off'", 'tcc-fluid' )
		);
		foreach( $check as $font => $state ) {
			if ( ( isset( $fonts[ $font ] ) ) && ( $state === 'off' ) ) {
				unset( $fonts[ $font ] );
			}
		}
		return $fonts; // apply_filters( 'fluid_google_fonts_filter', $fonts );
	}


	public static function mixed_fonts( $font = 'no-font' ) {
		static $mixed_fonts;
		if ( empty( $mixed_fonts ) ) {
			$mixed_fonts = array_unique( array_merge( self::os_fonts(), self::google_fonts() ) );
			asort( $mixed_fonts );
		}
		if ( func_num_args() > 0 ) {
			return ( isset( $mixed_fonts [ $font ] ) ) ? $mixed_fonts [ $font ] : false;
		}
		return $mixed_fonts; // apply_filters( 'fluid_mixed_fonts', $mixed_fonts );
	}

	public static function load_google_font() {
#		$google_fonts = array_keys( self::google_fonts() );
		$current  = get_theme_mod( 'font_typography', 'Helvitica Neue' );
		if ( ! in_array( $current, self::os_fonts() ) ) { // Really?  Are we sure about this?
			$google = self::google_fonts();
			if ( in_array( $current, $google ) ) {
				$font = explode( ',', $google[ $current ] );
				if ( $font[0] === 'Raleway' ) {
					$font[0] = 'Raleway:100'; // FIXME: special case, should this be more generic?  what does this do anyway?
				}
				$args = array(
					'family' => urlencode( implode( '|', $font ) ),
					'subset' => urlencode( 'latin,latin-ext' ) // FIXME: when would subset be something different?
				);
				$url = add_query_arg( $args, 'https://fonts.googleapis.com/css' );
fluid()->log($url);
				wp_enqueue_style( 'font_typography', $url, null, null, 'all' );
			}
		}
	}

	public static function typography_styles() {
		// font family
		$font = self::mixed_fonts( get_theme_mod( 'font_typography', 'Helvitica Neue' ) );
		echo "\nbody {\n\tfont-family: $font;\n}\n";
		// font size
		$size = intval( get_theme_mod( 'font_size', 18 ), 10 );
		echo "body { font-size: {$size}px; }";
		// font family for header
		$header = self::mixed_fonts( get_theme_mod( 'font_head_typog', 'Open Sans' ) );
		echo "header#fluid-header { font-family: $header; }";
		// widget panel title
		$panel = max( 1, $size - 2 );
		echo "panel-title { font-size: {$panel}px; }";
	}

	public static function customizer_controls( $options ) {
		$section = array(
			'priority'    => 30,
			'title'       => __( 'Typography', 'tcc-fluid' ),
			'description' => __( 'Site typography options', 'tcc-fluid' ),
		);
		$controls = array(
			'head_typog' => array(
				'default' => 'Open Sans',
				'label'   => __( 'Header Font Type', 'tcc-fluid' ),
				'render'  => 'font',
				'choices' => TCC_Theme_Typography::mixed_fonts(),
			),
			'typography' => array(
				'default' => 'Helvitica Neue',
				'label'   => __( 'Content Font Type', 'tcc-fluid' ),
				'render'  => 'font',
				'choices' => TCC_Theme_Typography::mixed_fonts(),
			),
			'size' => array(
				'default' => 18,
				'label'   => __('Content Font Size','tcc-fluid'),
				//'stext'   => _x( 'px', "abbreviation for 'pixel' - not sure this even needs translating...", 'tcc-fluid' ),
				'render'  => 'spinner',
				'input_attrs'   => array(
					'class' => 'text_3em_wide',
					'min'   => '1',
					'step'  => '1',
#					'value' => intval( get_theme_mod( 'font_size', 18 ), 10 ),
				),
			),
		);
		$options['font'] = array(
			'section'  => $section,
			'controls' => $controls
		);
		return $options;
	}


}

add_filter( 'fluid_os_fonts',            array( 'TCC_Theme_Typography', 'os_fonts_filter' ) );
add_filter( 'fluid_google_fonts',        array( 'TCC_Theme_Typography', 'google_fonts_filter' ) );
add_filter( 'fluid_customizer_controls', array( 'TCC_Theme_Typography', 'customizer_controls' ) );
