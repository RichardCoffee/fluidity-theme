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

// Can only be used inside the Loop
function fluid_title($length,$echo=true,$after='...',$before='') {
  global $micro;
  $title = $micro->get_the_title();
  return $title;
  if (strlen($title)>0) {
    $new = $title;
    if ($length && is_numeric($length)) {
      while (strlen($new)>$length) {
        $words = explode(' ',$title);
        array_pop($words);
        if ($words) {
          $new = implode(' ',$words);
        } else {
          $new = substr($title,0,$length);
          break;
        }
      }
    }
    $new = $before.$new.$after;
    $new = apply_filters('the_title',$title,get_the_ID());
    if ($echo) { echo $new; } else { return $new; }
  }
}

?>
