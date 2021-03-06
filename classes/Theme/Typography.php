<?php

/* sources: http://wptheming.com/2012/06/loading-google-fonts-from-theme-options/
 *          http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
 *          http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */


class TCC_Theme_Typography {

	private static $option = 'tcc_options_design';
	private static $loaded = array();

	public static function os_fonts() {
		// OS Font Defaults
		// TODO: load browser fonts (via js(?))
		$os_faces = array(
			'Andale Mono'      => '"Andale Mono", sans-serif',
			'Arial'            => 'Arial, sans-serif',
#			'Avant Garde'      => '"Avant Garde", sans-serif',
			'Cambria'          => 'Cambria, Georgia, serif',
			'Comic Sans MS'    => '"Comic Sans", sans-serif',
#			'Copse'            => 'Copse, sans-serif', // duplicate in google_fonts()
			'Courier New'      => '"Courier New", serif',
			'DejaVu Sans Mono' => '"DejaVu Sans Mono", sans-serif',
#			'Garamond'         => 'Garamond, "Hoefler Text", Times New Roman, Times, serif',
			'Georgia'          => 'Georgia, serif',
#			'Helvitica Neue'   => '"Helvetica Neue", Helvetica, sans-serif',
			'Liberation Sans'  => '"Liberation Sans",sans-serif',
			'Liberation Serif' => 'Liberation Serif, serif',
			'Tahoma'           => 'Tahoma, Geneva, sans-serif',
			'Times New Roman'  => '"Times New Roman", serif',
		);
		return apply_filters( 'fluid_os_fonts', $os_faces );
	}

	public static function os_fonts_filter( $fonts ) {
		$check = array(
			'Andale Mono'      => _x( 'on', "Andale Mono font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Arial'            => _x( 'on', "Arial font: translate as 'on' or 'off'", 'tcc-fluid' ),
#			'Avant Garde'      => _x( 'on', "Avant Garde font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Cambria'          => _x( 'on', "Cambria font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Comic Sans MS'    => _x( 'on', "Comic Sans MS font: translate as 'on' or 'off'", 'tcc-fluid' ),
#			'Copse'            => _x( 'on', "Copse font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Courier New'      => _x( 'on', "Courier New font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'DejaVu Sans Mono' => _x( 'on', "Arial font: translate as 'on' or 'off'", 'tcc-fluid' ),
#			'Garamond'         => _x( 'on', "Garamond font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Georgia'          => _x( 'on', "Georgia font: translate as 'on' or 'off'", 'tcc-fluid' ),
#			'Helvitica Neue'   => _x( 'on', "Helvitica Neue font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Liberation Sans'  => _x( 'on', "Liberation Sans font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Liberation Serif' => _x( 'on', "Liberation Serif font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Tahoma'           => _x( 'on', "Tahoma font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Times New Roman'  => _x( 'on', "Times New Roman font: translate as 'on' or 'off'", 'tcc-fluid' ),
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
			'Caveat'       => 'Caveat, cursive',
			'Cookie'       => 'Cookie, cursive',
			'Copse'        => 'Copse, sans-serif', // duplicate in os_fonts()
			'Dancing Script' => 'Dancing Script, cursive',
			'Emilys Candy' => 'Emilys Candy',
			'Gamja Flower' => 'Gamja Flower, cursive',
			'Gloria Hallelujah' => 'Gloria Hallelujah, cursive',
			'Great Vibes'  => 'Great Vibes, cursive',
			'Indie Flower' => 'Indie Flower, cursive',
			'Kaushan Script' => 'Kaishan Script, cursive',
			'Lato'         => 'Lato, sans-serif',
			'Lobster'      => 'Lobster, cursive',
			'Lustria'      => 'Lustria, Open Sans',
			'Merienda'     => 'Merienda, cursive',
			'Nobile'       => 'Nobile, sans-serif',
			'Open Sans'    => 'Open Sans, sans-serif',
			'Oswald'       => 'Oswald, sans-serif',
			'Pacifico'     => 'Pacifico, cursive',
			'PT Sans'      => 'PT Sans, sans-serif',
			'Quattrocento' => 'Quattrocento, serif',
			'Raleway'      => 'Raleway, sans-serif',
			'Roboto'       => 'Roboto, sans serif',
			'Roboto Mono'  => 'Roboto Mono',
			'Rokkit'       => 'Rokkitt, serif',
			'Satisfy'      => 'Satisfy, cursive',
			'Shadows Into Light' => 'Shadows Into Light, cursive',
			'Ubuntu'       => 'Ubuntu, sans-serif',
			'Yanone Kaffeesatz'  => 'Yanone Kaffeesatz, sans-serif'
		);
		return apply_filters( 'fluid_google_fonts', $google_faces );
	}

	public static function google_fonts_filter( $fonts ) {
		$check = array(
			'Arvo'         => _x( 'on', "Arvo font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Caveat'       => _x( 'on', "Caveat font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Cookie'       => _x( 'on', "Cookie font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Copse'        => _x( 'on', "Copse font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Dancing Script' => _x( 'on', "Dancing Script font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Emilys Candy' => _x( 'on', "Emilys Candy font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Gamja Flower' => _x( 'on', "Gamja Flower font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Gloria Hallelujah' => _x( 'on', "Gloria Hallelujah font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Great Vibes'  => _x( 'on', "Great Vibes font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Indie Flower' => _x( 'on', "Indie Flower font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Kaushan Script' => _x( 'on', "Kaushan Script font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Lato'         => _x( 'on', "Lato font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Lobster'      => _x( 'on', "Lobster font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Lustria'      => _x( 'on', "Lustria font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Merienda'     => _x( 'on', "Merienda font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Nobile'       => _x( 'on', "Nobile font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Open Sans'    => _x( 'on', "Open Sans font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Oswald'       => _x( 'on', "Oswald font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Pacifico'     => _x( 'on', "Pacifico font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'PT Sans'      => _x( 'on', "PT Sans font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Quattrocento' => _x( 'on', "Quattrocento font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Raleway'      => _x( 'on', "Raleway font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Roboto'       => _x( 'on', "Roboto font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Roboto Mono'  => _x( 'on', "Roboto Mono font: translate as 'on' or 'off'.  Availability: Latin, Cyrillic, Greek", 'tcc-fluid' ),
			'Rokkit'       => _x( 'on', "Rokkit font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Satisfy'      => _x( 'on', "Satisfy font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Shadows Into Light' => _x( 'on', "Shadows Into Light font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Ubuntu'       => _x( 'on', "Ubuntu font: translate as 'on' or 'off'", 'tcc-fluid' ),
			'Yanone Kaffeesatz' => _x( 'on', "Yanone Kaffeesatz font: translate as 'on' or 'off'.  Availability: Latin, Cyrillic", 'tcc-fluid' )
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

	public static function load_fonts() {
		$locs = array( 'typography', 'head_typog', 'side_typog', 'foot_typog' );
		foreach( $locs as $typog ) {
			$font = get_theme_mod( "font_$typog", 'Arial' );
			self::load_google_font( $font );
		}
	}

	public static function load_google_font( $font = 'Arial' ) {
		if ( ( in_array( $font, static::google_fonts() ) ) && ( ! in_array( $font, static::$loaded ) ) ) {
			if ( empty( static::$loaded ) ) {
				add_action( 'fluid_header_links', [ 'TCC_Theme_Typography', 'create_font_links' ] );
			}
			static::$loaded[] = $font;
		}
	}

#	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/link
#	 * @link https://developers.google.com/fonts/docs/getting_started
	public static function create_font_links() {
		if ( ! empty( static::$loaded ) ) {
			$fonts = array_unique( static::$loaded );
			$fonts = array_map( [ 'TCC_Theme_Typography', 'map_font' ], $fonts );
			$args = array(
				'id'   => 'theme_font',
				'rel'  => 'preload',
				'href' => 'https://fonts.googleapis.com/css',
				'as'   => 'font',
#				'type' => 'font/woff',
				'crossorigin' => 'anonymous',
#				'media' => 'all',
			);
			foreach( $fonts as $font ) {
				$args['id']   = 'theme_font_' . sanitize_key( $font );
				$args['href'] = add_query_arg( [ 'family' => urlencode( $font ) ], 'https://fonts.googleapis.com/css' );
				fluid()->element( 'link', $args );
			}
		}
	}

#	 * @link https://rwt.io/typography-tips/variable-fonts-new-google-fonts-api
	public static function map_font( $font ) {
		switch( $font ) {
			case 'Arvo':
			case 'PT Sans':
				$font .= ':i,b,bi';
				break;
			case 'Caveat':
			case 'Merienda':
			case 'Quattrocento':
				$font .= ':b';
				break;
			case 'Dancing Script':
				$font .= ':400,500,600,700';
				break;
			case 'Lato':
			case 'Roboto':
				$font .= ':100,100i,300,300i,400,400i,700,700i,900,900i';
				break;
			case 'Nobile':
				$font .= ':400,400i,500,500i,700,700i';
				break;
			case 'Open Sans':
				$font .= ':300,300i,400,400i,600,600i,700,700i,800,800i';
				break;
			case 'Oswald':
			case 'Yanone Kaffeesatz':
				$font .= ':200,300,400,500,600,700';
				break;
			case 'Raleway':
				$font .= ':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i';
				break;
			case 'Roboto Mono':
				$font .= ':100,100i,300,300i,400,400i,700,700i';
				break;
			case 'Rokkit':
				$font .= ':100,200,300,400,500,600,700,800,900';
				break;
			case 'Ubuntu':
				$font .= ':300,300i,400,400i,500,500i,700,700i';
				break;
#			case 'Cookie':
#			case 'Copse':
#			case 'Emilys Candy':
#			case 'Gamja Flower':
#			case 'Gloria Hallelujah':
#			case 'Great Vibes':
#			case 'Indie Flower':
#			case 'Kaushan Script':
#			case 'Lobster';
#			case 'Lustria':
#			case 'Pacifico':
#			case 'Satisfy':
#			case 'Shadows Into Light':
			default:
		}
		return $font;
	}

	public static function typography_styles() {

		// font family
		$font = self::mixed_fonts( get_theme_mod( 'font_typography', 'Arial' ) );
		echo "\nbody {\n\tfont-family: $font;\n";
		// font size
		$size = intval( get_theme_mod( 'font_size', 18 ), 10 );
		echo "\tfont-size: {$size}px;\n}\n";

		// font family for header
		$head = self::mixed_fonts( get_theme_mod( 'font_head_typog', 'Open Sans' ) );
		echo "\nheader#fluid-header {\n\tfont-family: $head;\n";
		// font size for header
		$size = intval( get_theme_mod( 'font_head_size', 18 ), 10 );
		echo "\tfont-size: {$size}px;\n}\n";
		// widget area
		$side = self::mixed_fonts( get_theme_mod( 'font_side_typog', 'Open Sans' ) );
		echo "\ndiv.widget-area {\n\tfont-family: $side;\n";
		echo "\tfont-size: {$size}px;\n}\n";
		// widget panel title
		$panel = max( 1, $size - 2 );
		echo "\npanel-title {\n\tfont-size: {$panel}px;\n}\n";
		// footer
		$foot = self::mixed_fonts( get_theme_mod( 'font_foot_typog', 'Open Sans' ) );
		echo "\ndiv#fluid-footer {\n\tfont-family: $foot;\n";
		echo "\tfont-size: {$size}px;\n}\n";
	}

	public static function customizer_controls( $options ) {
		$section = array(
			'priority'    => 30,
			'title'       => __( 'Typography', 'tcc-fluid' ),
			'description' => __( 'We suggest you visit fonts.google.com to see what the fonts look like.', 'tcc-fluid' ),
		);
		$controls = array(
			'typography_text' => array(
				'label'       => __( 'Typography', 'tcc-fluid' ),
				'description' => __( 'IMPORTANT: Customizer preview does not work properly on the fonts.  Go to https://fonts.google.com to see what each font will appear as.  Some fonts may require a larger font size to make them easily readable.', 'tcc-fluid' ),
				'render'      => 'content',
				'sanitize_callback' => '__return_true',
			),
			'typography' => array(
				'default' => 'Helvitica Neue',
				'label'   => __( 'Site Main Font', 'tcc-fluid' ),
				'render'  => 'font',
				'choices' => TCC_Theme_Typography::mixed_fonts(),
			),
			'size' => array(
				'default' => 18,
				'label'   => __('Site Font Size','tcc-fluid'),
				'render'  => 'spinner',
				'input_attrs'   => array(
					'class' => 'text_3em_wide',
					'min'   => '1',
					'step'  => '1',
				),
			),
			'head_typog' => array(
				'default' => 'Open Sans',
				'label'   => __( 'Header Font', 'tcc-fluid' ),
				'render'  => 'font',
				'choices' => TCC_Theme_Typography::mixed_fonts(),
			),
			'head_size' => array(
				'default' => 18,
				'label'   => __('Header Font Size','tcc-fluid'),
				'render'  => 'spinner',
				'input_attrs'   => array(
					'class' => 'text_3em_wide',
					'min'   => '1',
					'step'  => '1',
				),
			),
			'side_typog' => array(
				'default' => 'Open Sans',
				'label'   => __( 'Sidebar Font', 'tcc-fluid' ),
				'render'  => 'font',
				'choices' => TCC_Theme_Typography::mixed_fonts(),
			),
			'foot_typog' => array(
				'default' => 'Open Sans',
				'label'   => __( 'Footer Font', 'tcc-fluid' ),
				'render'  => 'font',
				'choices' => TCC_Theme_Typography::mixed_fonts(),
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
