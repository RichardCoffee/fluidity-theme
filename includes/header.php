<?php

/*
 *  includes/header.php
 *
 */

if (!function_exists('tcc_browser_body_class')) {
  // http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
  function tcc_browser_body_class( array $classes ) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    if     ($is_lynx)   $classes[] = 'lynx';
    elseif ($is_gecko)  $classes[] = 'gecko';
    elseif ($is_opera)  $classes[] = 'opera';
    elseif ($is_NS4)    $classes[] = 'ns4';
    elseif ($is_safari) $classes[] = 'safari';
    elseif ($is_chrome) $classes[] = 'chrome';
    elseif ($is_IE)     $classes[] = 'ie';
    else                $classes[] = 'unknown';
    if     ($is_iphone) $classes[] = 'iphone';
    return $classes;
  }
  add_filter('body_class','tcc_browser_body_class');
}

// Limit length of title string
if (!function_exists('fluid_browser_title')) {
  function fluid_browser_title( string $title ) {
    if (!is_feed()) {
      $test = get_bloginfo('name');
      if (empty($title)) {
        $title = $test;
      } else {
        $spot = strpos($title,$test);
        if ($spot) {
          $title = substr($title,0,$spot);
        }
        $title.= ($test) ? " $test" : '';
      }
    }
    return $title;
  }
  add_filter('wp_title','fluid_browser_title',10,2); // FIXME:  wp_title to be deprecated
}

if (!function_exists('tcc_custom_css')) {
  function tcc_custom_css() { ?>
    <style id='tcc-custom-css' type='text/css'><?php
      tcc_custom_colors();
      do_action('tcc_custom_css'); ?>
    </style><?php
  }
}

if ( ! function_exists( 'fluidity_header_logo' ) ) {
	function fluidity_header_logo( $html, $blog_id ) {
		if ( ! is_customize_preview() ) {
			$html = '';
			ob_start(); ?>
			<div class="pointer" itemprop="logo" <?php microdata()->ImageObject(); ?>><?php
#				if ( function_exists( 'jetpack_the_site_logo' ) ) {
#					jetpack_the_site_logo();
#				} else {
					$attrs = array(
						'class'    => 'custom-logo-link',
						'href'     => home_url( '/' ),
						'rel'      => 'home',
						'itemprop' => 'url', // 'relatedLink',
					); ?>
					<a <?php apply_attrs( $attrs ); ?>><?php
						$logo_id = get_theme_mod( 'custom_logo' );
						if ( $logo_id ) {
							$size  = apply_filters( 'tcc_header_logo_size', 'medium' );
							$class = apply_filters( 'tcc_header_logo_class', array( 'centered', 'img-responsive', "attachment-$size", 'hidden-xs' ) );
							$attrs = array(
								'class'     => implode( ' ', $class ),
								'data-size' => $size,
								'itemprop'  => 'image',
							);
							echo wp_get_attachment_image( $logo_id, $size, false, $attrs );
						} else if ( $logo = tcc_design( 'logo' ) ) {  #  get logo url
							$class = apply_filters( 'tcc_header_logo_class', array( 'img-responsive', 'hidden-xs' ) );
							$attrs = array(
								'class'    => implode( ' ', $class ),
								'src'      => $logo,
								'alt'      => get_bloginfo( 'name' ),
								'itemprop' => 'image',
							); ?>
							<img <?php apply_attrs( $attrs ); ?>><?php
						} else {
							bloginfo( 'name' );
						} ?>
					</a><?php
#				} ?>
			</div><?php
			$html = ob_get_clean();
		}
		return $html;
	}
	add_filter( 'get_custom_logo', 'fluidity_header_logo', 20, 2 );
}

if (!function_exists('fluidity_menubar_print')) {
  function fluidity_menubar_print_button() {
    if (is_single() || is_page()) { ?>
      <span class="hidden fluid-print-button">
        <button class="btn btn-fluidity" onclick="print();">
          <i class="fa fa-print" aria-hidden="true"></i>
          <span class="hidden-xs"> <?php
            esc_html_e('Print','tcc-fluid'); ?>
          </span>
        </button>
      </span><?php
    }
  }
}
