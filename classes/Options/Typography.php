<?php

/* sources: http://wptheming.com/2012/06/loading-google-fonts-from-theme-options/
 *          http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
 *          http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */


class TCC_Options_Typography {

	private static $option = 'tcc_options_design';

	public static function os_fonts() {
		// OS Font Defaults
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
		return apply_filters( 'tcc_os_fonts', $os_faces );
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
		return $fonts;
	}

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
		return apply_filters( 'tcc_google_fonts', $google_faces );
	}

	public static function google_fonts_filter($fonts) {
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
		return $fonts;
	}


	public static function mixed_fonts() {
		$mixed_fonts = array_unique( array_merge( self::os_fonts(), self::google_fonts() ) );
		asort( $mixed_fonts );
		return apply_filters( 'tcc_mixed_fonts', $mixed_fonts );
	}

	public static function load_google_font() {
		$google_fonts = array_keys( self::google_fonts() );
		$theme_opts   = get_option( self::$option );
		if ( $theme_opts && isset( $theme_opts['font'] ) ) {
			$current  = $theme_opts['font'];
			$os_fonts = self::os_fonts();
			if ( ! in_array( $current, $os_fonts ) ) { // Really?  Are we sure about this?
				$google_fonts = self::google_fonts();
				if ( in_array( $current, $google_fonts ) ) {
					$myfont = explode( ',', $google_fonts[ $current ] );
					if ( $myfont[0] === 'Raleway' ) {
						$myfont[0] = 'Raleway:100'; // FIXME: special case, should this be more generic?  what does this do anyway?
					}
					$query_args = array(
						'family' => urlencode( implode( '|', $myfont ) ),
						'subset' => urlencode( 'latin,latin-ext' ) // FIXME: when would subset be something different?
					);
					$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
					wp_enqueue_style( 'typography_font', $fonts_url, false, null, 'all' );
				}
			}
		}
	}

	public static function typography_styles() {
		$font_options = get_option( self::$option, array() );
		if ( $font_options && isset( $font_options['font'] ) ) {
			$current = $font_options['font'];
			$mixed   = self::mixed_fonts();
			$font    = $mixed[ $current ];
			$size    = ( isset( $font_options['size'] ) ) ? intval( $font_options['size'] ) : 14;
			$output  = 'html {';
			$output .= " font-family:  $font;";
#			$output .= " font-weight:  $weight;";
			$output .= " font-size:    {$size}px;";
			$output .= '}';
			echo $output;
		}
	}


}

add_action( 'tcc_custom_css',     array( 'TCC_Options_Typography', 'typography_styles' ), 1 );
add_action( 'wp_enqueue_scripts', array( 'TCC_Options_Typography', 'load_google_font' ) );
add_filter( 'tcc_os_fonts',       array( 'TCC_Options_Typography', 'os_fonts_filter' ) );
add_filter( 'tcc_google_fonts',   array( 'TCC_Options_Typography', 'google_fonts_filter' ) );
