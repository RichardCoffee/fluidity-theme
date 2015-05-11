<?php

if (!function_exists('browser_body_class')) {
  // http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
  function browser_body_class($classes) {
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
  add_filter('body_class','browser_body_class');
}

// Limit length of title string
if (!function_exists('browser_title')) {
  function browser_title($title,$sep) {
    if (is_feed()) return $title;
    $test = get_bloginfo('name');
    $spot = strpos($title,$test);
    if ($spot) {
      $new = substr($title,0,$spot);
      $title = $new.' > '.$test;
    }
    return $title;
  }
  add_filter('wp_title','browser_title',10,2);
}

?>
