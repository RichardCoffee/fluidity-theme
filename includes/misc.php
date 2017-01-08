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

if (!function_exists('fluidity_social_icons')) {
  function fluidity_social_icons() {
    $icons = get_option('tcc_options_social');
    if ($icons['active']==='yes') {
      if (has_action('fluidity_social_icons')) {
        do_action('fluidity_social_icons');
      } else {
        $target = (isset($icons['target'])) ? $icons['target'] : 'target';
        unset($icons['active'],$icons['target']);
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
        $insta  = new TCC_Options_Social;
        $layout = $insta->social_layout();
        #log_entry($icons,$social,$layout); ?>
        <span class='fluidity-social-icons'><?php
          foreach($social as $key=>$set) {
            if (empty($set['link'])) continue;
            $html = " <a class='fa fa-fw fa-$key-square'";
            $html.= ($target==='target') ? " target='fluidity_$key'" : "";
            $html.= " href='{$set['link']}'";
            $tool = sprintf(esc_html_x('See us on %s','website name','tcc-fluid'),$layout[$key]['label']);
            $tool = ($key==='RSS') ? esc_html__('Subscribe to our RSS feed') : $tool;
            $html.= " title='$tool'";
            $html.= " style='color:{$set['color']};'> </a>";
            echo $html;
          } ?>
        </span><?php
      }
    }
  } //*/
}

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
