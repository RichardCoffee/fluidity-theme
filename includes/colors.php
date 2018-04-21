<?php

if ( ! function_exists( 'fluid_color_body_class' ) ) {
	function fluid_color_body_class( array $classes ) {
		$color = fluid_color_scheme();
		if ( $color ) {
			$classes[] = "fluid-color-$color";
		}
		return $classes;
	}
	add_filter( 'body_class', 'fluid_color_body_class' );
}

if ( ! function_exists( 'fluid_color_scheme' ) ) {
	function fluid_color_scheme() {
		static $scheme = null;
		if ( ! $scheme ) {
			$color = get_theme_mod( 'color_scheme', null );
			$color = ( $color ) ? $color : tcc_design( 'color_scheme', null );
			if ( ! $color || ( $color === 'random-color' ) ) {
				$color = tcc_color_scheme(); # generates random color scheme
			}
			$base   = "/css/colors/$color.css";
			if ( is_readable( get_stylesheet_directory() . $base ) ) {
				$scheme = $color;
			} else if ( is_readable( get_template_directory() . $base ) ) {
				$scheme = $color;
			}
			$scheme = apply_filters( 'fluid_color_scheme', $scheme );
		}
		return $scheme;
	}
}

if ( ! function_exists( 'tcc_color_scheme' ) ) {
	function tcc_color_scheme() {
		static $color; // = 'danger-inverse';
		if ( ! $color ) {
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
#				'yellow',
			);
			$index  = rand( 0, count( $colors ) - 1 );
			$color  = $colors[ $index ];
		}
		return apply_filters( 'tcc_color_scheme', $color );
	}
}

#  function assumes calling function is wrapping with css script tags
function tcc_custom_colors() {
  $colors = get_option('tcc_options_colors');
  if ($colors) {
    foreach($colors as $key=>$color) {
      if ((empty($color)) || ($color=='none')) continue;
      echo "$key { color: $color; }";
    }
  }
}
