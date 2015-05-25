<?php

// Source? Purpose?
function contextual_static_front_page_section( $wp_customize ) {
    $wp_customize->get_section('static_front_page')->active_callback = 'is_front_page';
}
add_action( 'customize_register', 'contextual_static_front_page_section', 11 );

function tcc_custom_colors() { ?>
  <style id='custom-color-css' type='text/css'><?php
    do_action('tcc_custom_css');
    $colors = get_option('tcc_options_colors');
    if ($colors) {
      foreach($colors as $key=>$color) {
        if ((empty($color)) || ($color=='none')) continue;
        echo "$key { color: $color; }";
      }
    } ?>
  </style><?php
}

// derived from:  http://codex.wordpress.org/Excerpt
function fluid_read_more_link($output) {
 global $post;
 $read = __('Read More...','creatom');
 $perm = get_permalink($post->ID);
 $link = " [<a href='$perm' itemprop='url'>$read</a>]";
 return $link;
}
add_filter('excerpt_more', 'fluid_read_more_link');

if (!function_exists('single_search_result')) {
  // http://www.hongkiat.com/blog/wordpress-tweaks-for-post-management/
  function single_search_result() {
    if (is_search() || is_archive() || is_category()) {
      global $wp_query;
      if ($wp_query->post_count==1) {
        wp_redirect(get_permalink($wp_query->posts['0']->ID));
      }
    } else {
#      global $wp_query;
#      log_entry('wp_query',$wp_query);
    }
  }
  add_action('template_redirect','single_search_result');
}

// Can only be used inside the Loop
function fluid_title($length=0,$echo=true,$after='...',$before='') {
  $title = get_the_title(get_post()->ID);
  if (strlen($title)>0) {
    if ($length && is_numeric($length)) {
      if (strlen($title)>$length) {
        $title = strip_tags($title);
        $title = substr($title,0,$length);
        $title = substr($title,0,strripos($title,' '));
        $title = $before.$title.$after;
      }
    }
    $title = apply_filters('the_title',$title,get_post()->ID);
    if ($echo) { echo $title; } else { return $title; }
  }
}

?>
