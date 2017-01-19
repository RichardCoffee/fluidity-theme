<?php

/*
 *  includes/header.php
 *
 *  IMPORTANT:  this file is in flux, nothing here is guaranteed to remain the same
 *
 */

if (!function_exists('fluid_browser_body_class')) {
  // http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
  function fluid_browser_body_class( array $classes ) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
log_entry('fluid_browser_body_class');
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
  add_filter('body_class','fluid_browser_body_class');
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

if (!function_exists('fluidity_header_logo')) {
  function fluidity_header_logo() {
#    if (is_404()) { return; } // FIXME
?>
    <span itemprop="logo" <?php microdata()->ImageObject(); ?>><?php
      if (function_exists('jetpack_the_site_logo')) {
        jetpack_the_site_logo();
      } else { ?>
        <a class='logo' href='<?php echo home_url(); ?>/' itemprop='relatedLink'><?php
          $logo_id = get_theme_mod('custom-logo');
          if ($logo_id) {
            $class = apply_filters('tcc_header_logo_class',array('img-responsive',"attachment-$size",'hidden-xs'));
            $size = 'medium';
            $attr = array( 'class'     => implode(' ',$class),
                           'data-size' => $size,
                           'itemprop'  => "image" );
            echo wp_get_attachment_image( $logo_id, $size, false, $attr );
          } else if ($logo=tcc_design('logo')) {
            $class = apply_filters('tcc_header_logo_class',array('img-responsive','hidden-xs')); ?>
            <img class='<?php echo implode(' ',$class); ?>' src='<?php echo $logo; ?>' alt='<?php bloginfo('name'); ?>' itemprop='image'><?php
          } else { ?>
            <h1>
              <?php bloginfo('name'); ?>
            </h1><?php
          } ?>
        </a><?php
      } ?>
    </span><?php
  }
}

if (!function_exists('fluidity_menubar_print')) {
  function fluidity_menubar_print_button() {
    if (is_single() || is_page()) { ?>
      <span class="hidden fluid-print-button">
        <button class="btn btn-fluidity" onclick="print();">
          <i class="fa fa-print"></i>
          <span class="hidden-xs"> <?php
            esc_html_e('Print','tcc-fluidity'); ?>
          </span>
        </button>
      </span><?php
    }
  }
}
