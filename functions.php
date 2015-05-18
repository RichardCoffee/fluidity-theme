<?php
/*
 *  functions.php
 *
 */

require_once('includes/theme-support.php');
require_once('includes/library.php');
require_once('includes/filters.php');
require_once('includes/footer.php');
require_once('includes/header.php');
require_once('includes/login.php');
require_once('includes/misc.php');
require_once('includes/options.php');
require_once('includes/sidebars.php');
require_once('includes/wp_bootstrap_navwalker.php');
require_once('includes/third-party.php');
require_once('classes/microdata.php');
require_once('classes/widgets.php');

if (!function_exists('fluidity_enqueue')) {
  function fluidity_enqueue() {
    $base_url = get_template_directory_uri();
    fluidity_register_bootstrap();
    fluidity_register_fontawesome();
    wp_register_style('library', "$base_url/css/library.css");
    wp_register_style('fluid',   get_bloginfo('stylesheet_url'));
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('library');
    wp_enqueue_style('fluid');
    wp_enqueue_style('tcc-fawe');
    if ($color_file=fluid_color_scheme()) {
      wp_enqueue_style('fcolor',  "$base_url/css/colors/$color_file.css"); }
    wp_register_script('sprintf', "$base_url/js/sprintf.js", null,                     false,true);
    wp_register_script('library', "$base_url/js/library.js", array('jquery','sprintf'),false,true);
    wp_register_script('collapse',"$base_url/js/collapse.js",array('jquery','library'),false,true);
    wp_enqueue_script('bootstrap');
    wp_enqueue_script('collapse');
    if (is_singular() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply'); } // enable threaded comments
    do_action('tcc_enqueue');
  }
  add_action('wp_enqueue_scripts','fluidity_enqueue');
}

if (!function_exists('fluidity_admin_enqueue')) {
  function fluidity_admin_enqueue() {
    fluidity_register_fontawesome();
    wp_enqueue_style('tcc-fawe');
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
    wp_register_style('tcc-fawe', get_template_directory_uri()."/css/font-awesome.min.css",false,'4.3.0');
  }
}

/** Temporary Construction functions **/

function show_construction_title() {
  $site  = "<a href='".home_url()."'>This site</a>";
  $refer = "<a href='http://the-creative-collective.com' target='TCC'>The Creative Collective</a>"; ?>
  <h1 class='text-center'><?php
    echo "$site currently under construction by $refer"; ?>
  </h1><?php
}
add_action('tcc_header_body_content','show_construction_title');

function control_construction_header($args) {
  $args['split'] = false;
  return $args;
}
add_filter('tcc_header_body','control_construction_header');
