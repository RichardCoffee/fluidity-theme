<?php
/*
 *  tcc-fluidity/functions.php
 *
 */

define('FLUIDITY_VERSION','1.1.0');

require_once('includes/theme-support.php');
require_once('includes/colors.php');
#require_once('includes/debugging.php');
require_once('includes/library.php');
#require_once('includes/layout.php');
require_once('includes/menus.php');
require_once('includes/misc.php');
require_once(plugin_dir_path(__FILE__).'includes/options.php'); #  Needs full path, or wp-admin/includes/options.php gets loaded instead
require_once('includes/sidebars.php');
require_once('classes/form-fields.php');
require_once('classes/widgets.php');
if (is_admin()) {
  require_once('classes/typography.php');
  require_once('classes/options.php');
} else {
  require_once('includes/footer.php');
  require_once('includes/header.php');
  require_once('includes/in-the-loop.php');
  require_once('includes/index.php');
  require_once('includes/login.php');
  require_once('includes/pages.php');
  require_once('includes/wp_bootstrap_navwalker.php');
  require_once('includes/third-party.php');
  require_once('classes/layout.php'); // Experiment - see archive.php
  require_once('classes/microdata.php');
}

if (!function_exists('fluidity_enqueue')) {
  function fluidity_enqueue() {
    $base_url = get_template_directory_uri();
    fluidity_register_bootstrap();
    fluidity_register_fontawesome();
    fluidity_register_color_scheme();
    #  Stylesheets
    wp_register_style('autohide',  "$base_url/css/autohide.css",        false, FLUIDITY_VERSION);
    wp_register_style('library',   "$base_url/css/library.css",         false, FLUIDITY_VERSION);
    wp_register_style('fa-social', "$base_url/css/fa-social-hover.css", false, FLUIDITY_VERSION);
    wp_register_style('fluid',     "$base_url/style.css",               false, FLUIDITY_VERSION);
    wp_enqueue_style('tcc-fawe');  #  font-awesome needs to be loaded before bootstrap, due to possible css conflict (sr-only)
    if (tcc_option('active','social')=='yes') { wp_enqueue_style('fa-social'); }
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('library');
    wp_enqueue_style('fluid');
    wp_enqueue_style('fluid-color');
    #  Javascript
    wp_register_script('sprintf',     "$base_url/js/sprintf.js",  null,                     false,true);
    wp_register_script('library',     "$base_url/js/library.js",  array('jquery','sprintf'),false,true);
    wp_register_script('collapse',    "$base_url/js/collapse.js", array('jquery','library'),false,true);
    wp_register_script('autohide.js', "$base_url/js/autohide.js", array('jquery'),          false,true);
    wp_enqueue_script('bootstrap');
log_entry('widget: '.tcc_layout('widget'));
    if (!tcc_layout('widget')==='perm') {
log_entry('loading collapse');
      wp_enqueue_script('collapse'); }
else { log_entry('not loading collapse'); }
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply'); }  #  enable threaded comments
    $hdr_state = tcc_layout('header');
    if ($hdr_state==='fixed') {
      wp_enqueue_style('autohide');
      add_action('wp_footer','fluid_footer_autohide',99);
    } else if ($hdr_state==='hide') {
      wp_enqueue_style('autohide');
      wp_enqueue_script('autohide.js');
      add_action('wp_footer','fluid_footer_autohide',99);
    }
    do_action('fluidity_enqueue');
  }
  add_action('wp_enqueue_scripts','fluidity_enqueue');
}

if (!function_exists('fluidity_admin_enqueue')) {
  function fluidity_admin_enqueue() {
    fluidity_register_fontawesome();
    wp_enqueue_style('tcc-fawe');
    do_action('fluidity_admin_enqueue');
  }
  add_action('admin_enqueue_scripts','fluidity_admin_enqueue');
}

if (!function_exists('fluidity_register_bootstrap')) {
  function fluidity_register_bootstrap() {
    $base_url = get_template_directory_uri();
    wp_register_style('bootstrap', "$base_url/css/bootstrap.min.css",false,'3.3.4');
    wp_register_script('bootstrap',"$base_url/js/bootstrap.min.js",array('jquery'),'3.3.4',true);
  }
}

if (!function_exists('fluidity_register_fontawesome')) {
  function fluidity_register_fontawesome() {
    wp_register_style('tcc-fawe', get_template_directory_uri()."/css/font-awesome.min.css",false,'4.6.3');
  }
}

if (!function_exists('fluidity_register_color_scheme')) {
  function fluidity_register_color_scheme() {
    if ($color_file=fluid_color_scheme()) {
      $base = "/css/colors/$color_file.css";
      $file = get_stylesheet_directory().$base;
      if (is_readable($file)) {
        wp_register_style('fluid-color',  get_stylesheet_directory_uri()."/css/colors/$color_file.css",false,FLUIDITY_VERSION);
      } else {
        $file = get_template_directory().$base;
        if (is_readable($file)) {
          wp_register_style('fluid-color',  get_template_directory_uri()."/css/colors/$color_file.css",false,FLUIDITY_VERSION);
        }
      }
    }
  }
}

##  simple query template
if (!function_exists('fluidity_show_query')) {
  function fluidity_show_query( $args, $template, $slug='' ) {
    $query = new WP_Query($args);
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        get_template_part($template,$slug);
      }
    }
    wp_reset_postdata();
  }
}


/** Temporary Construction functions **/

if (!is_child_theme()) {
  function show_construction_title() {
    $site  = "<a href='".home_url()."' title='Fluidity'>This site</a>";
    $title = __('The Creative Collective');
    $refer = "<a href='http://the-creative-collective.com' target='TCC' title='$title'>$title</a>"; ?>
    <h1 class='text-center'><?php
      echo "$site currently under construction by $refer"; ?>
    </h1><?php
  }
  #if (function_exists('jetpack_the_site_logo') || get_theme_mod('header_logo') || tcc_design('logo')) {
  if (get_theme_mod('header_logo') || tcc_design('logo')) {
    add_action('tcc_left_header_body','fluidity_header_logo');
    add_action('tcc_right_header_body','show_construction_title');
  } else {
    add_action('tcc_main_header_body','show_construction_title');
  }
  add_action('tcc_top_left_menubar','fluidity_social_icons');
/*  function control_construction_header($args) {
    $args['split'] = false;
    return $args;
  }
  add_filter('tcc_header_body','control_construction_header'); //*/
}
