<?php

// Source? Purpose?
function contextual_static_front_page_section($wp_customize) {
  $wp_customize->get_section('static_front_page')->active_callback = 'is_front_page';
}
add_action( 'customize_register', 'contextual_static_front_page_section', 11 );

// derived from:  http://codex.wordpress.org/Excerpt
function fluid_read_more_link($output) {
 global $post;
 $read = __('Read More...','creatom');
 $perm = get_permalink($post->ID);
 $link = " [<a href='$perm' itemprop='url'>$read</a>]";
 return $link;
}
add_filter('excerpt_more', 'fluid_read_more_link');

function fluidity_social_icons() {
  $icons = get_option('tcc_options_social');
  if ($icons['active']==='yes') {
    if (has_action('fluidity_social_icons')) {
      do_action('fluidity_social_icons');
    } else {
      unset($icons['active']);
      $social = array(); // FIXME: find another way to do this
      foreach($icons as $field=>$value) {
        $pos = strpos($field,'_color');
        if ($pos) {
          $split = explode('_',$field);
          $social[$split[0]]['color'] = $value;
        } else {
          $social[$field]['link'] = $value;
        }
      }
      require_once(plugin_dir_path(__FILE__).'../classes/social.php');
      $insta  = new Theme_Social_Icons();
      $layout = $insta->social_layout();
log_entry($icons,$social,$layout);
 ?>
      <span class='fluidity-social-icons'><?php
        foreach($social as $key=>$set) {
          if (empty($set['link'])) continue;
          $html = " <a class='fa fa-fw fa-$key-square' ";
          $html.= " target='fluidity_$key' href='{$set['link']}'";
          $html.= " title='{$layout[$key]['label']}'";
          $html.= " style='color:{$set['color']};'> </a>";
          #echo " <a class='fa fa-fw fa-$key-square' target='fluidity_$key' href='{$set['link']}' style='color:{$set['color']};'> </a>";
        } ?>
      </span><?php
    }
  }
} //*/

if (!function_exists('fluid_user_profile_link')) {
  function fluid_user_profile_link() {
    global $current_user;
    get_currentuserinfo();
    $html = "<a href='";
    $html = get_option('siteurl');
    $html = "/wp-admin/profile.php'>";
    $html = $current_user->display_name;
    $html = "</a>";
    return apply_filters('tcc_user_profile_link',$html);
  }
}

if (!function_exists('single_search_result')) {
  // http://www.hongkiat.com/blog/wordpress-tweaks-for-post-management/
  function single_search_result() {
    if (is_search() || is_archive()) {
      global $wp_query;
      if ($wp_query->post_count==1) {
        wp_redirect(get_permalink($wp_query->posts['0']->ID));
      }
    }
  }
  add_action('template_redirect','single_search_result');
}

// Can only be used inside the Loop
function fluid_title($length=0,$echo=true,$after='...',$before='') {
  $title = get_the_title(get_post()->ID);
  if (strlen($title)==0) {
    $title = "{No Title}";
  } else if (strlen($title)>0) {
    if ($length && is_numeric($length)) {
      $title = strip_tags($title);
      if (strlen($title)>$length) {
        $title = substr($title,0,$length);
        $title = substr($title,0,strripos($title,' '));
        $title = $before.$title.$after;
      }
    }
    $title = esc_html(apply_filters('the_title',$title,get_post()->ID));
  }
  if ($echo) { echo $title; } else { return $title; }
}

?>
