<?php

/*
 *  includes/header.php
 *
 *  IMPORTANT:  this file is in flux, nothing here is guaranteed to remain the same
 *
 */

if (!function_exists('fluidity_custom_css')) {
  function fluidity_custom_css() { ?>
    <style id='fluidity-custom-css' type='text/css'><?php
      tcc_custom_colors();
      do_action('tcc_custom_css'); ?>
    </style><?php
  }
}

if (!function_exists('fluidity_browser_body_class')) {
  // http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
  function fluidity_browser_body_class($classes) {
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
  add_filter('body_class','fluidity_browser_body_class');
}

// Limit length of title string
if (!function_exists('fluidity_browser_title')) {
  function fluidity_browser_title($title,$sep) {
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
  add_filter('wp_title','fluidity_browser_title',10,2);
}

if (!function_exists('fluidity_top_menubar')) {
  function fluidity_top_menubar() { ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><?php
      do_action('tcc_top_left_menubar'); ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><?php
      do_action('tcc_top_right_menubar'); ?>
    </div><?php
  }
  add_action('tcc_header_top_menubar','fluidity_top_menubar');
}

if (!function_exists('fluidity_header_bar_login')) {
  function fluidity_header_bar_login() { ?>
    <div class="header-login"><?php
      tcc_login_form(true,true); ?>
    </div><?php
  }
  add_action('tcc_top_right_menubar','fluidity_header_bar_login');
}

if (!function_exists('fluidity_header_body')) {
  function fluidity_header_body() {
    $defaults = array('left'  => 'col-lg-4  col-md-4  col-sm-12 col-xs-12',
                      'body'  => 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
                      'right' => 'col-lg-8  col-md-8  col-sm-12 col-xs-12');
    $classes = apply_filters('tcc_header_body_class',$defaults);
    extract($classes);  #  $defaults array
    if (has_action('tcc_left_header_body') || has_action('tcc_right_header_body')) { ?>
      <div class="<?php echo $left; ?>"><?php
        do_action('tcc_left_header_body'); ?>
      </div>
      <div class="<?php echo $right; ?>"><?php
        do_action('tcc_right_header_body'); ?>
      </div><?php
    } else { ?>
      <div class="<?php echo $body; ?>"><?php
        do_action('tcc_main_header_body'); ?>
      </div><?php
    }
  }
  add_action('tcc_header_body_content','fluidity_header_body');
}

if (!function_exists('fluidity_header_logo')) {
  function fluidity_header_logo() { ?>
    <div itemprop="logo" <?php microdata()->ImageObject(); ?>><?php
      if (function_exists('jetpack_the_site_logo')) {
        jetpack_the_site_logo();
      } else { ?>
        <a class='logo' href='<?php echo home_url(); ?>/' itemprop='relatedLink'><?php
          $logo_id = get_theme_mod( 'header_logo' );
          if ($logo_id) {
            $size = 'medium';
            $attr = array( 'class'     => "img-responsive attachment-$size",
                           'data-size' => $size,
                           'itemprop'  => "image" );
            echo wp_get_attachment_image( $logo_id, $size, false, $attr );
          } else { ?>
            <img class='img-responsive' src='<?php echo tcc_design('logo'); ?>' alt='<?php bloginfo('name'); ?>' itemprop='image'><?php
          } ?>
        </a><?php
      } ?>
    </div><?php
  }
}

if (!function_exists('fluidity_jetpack_site_logo_to_bootstrap')) {
  function fluidity_jetpack_site_logo_to_bootstrap($html) {
    // FIXME:  replace first instance of 'site-logo' with 'logo site-logo'.  Actual result should be 'logo site-logo-link'
    // FIXME:  replace second occurance of 'site-logo' with 'img-responsive site-logo'
    return $html;
  }
  add_filter('jetpack_the_site_logo','fluidity_jetpack_site_logo_to_bootstrap');
}

if (!function_exists('fluidity_main_menubar')) {
  function fluidity_main_menubar() {
#echo "Color Scheme: ".tcc_color_scheme();
//    if ($menu=tcc_layout('menu')) {
#log_entry("layout menu: $menu");
#assert( "locate_template( array('template-parts/menu-$menu.php', 'template-parts/menu.php'), false, false )" );
//      get_template_part('template-parts/menu',tcc_layout('menu'));
      get_template_part('template-parts/menu');
//    }
  }
  add_action('tcc_header_menubar','fluidity_main_menubar');
}
