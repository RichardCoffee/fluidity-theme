<?php
/*
 *  tcc-fluidity/functions.php
 *
 */

define('FLUIDITY_VERSION','1.0.0');

require_once('includes/theme-support.php');
require_once('includes/library.php');
#require_once('includes/menus.php');
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
  require_once('includes/login.php');
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
    wp_register_style('library', "$base_url/css/library.css",    false, FLUIDITY_VERSION);
    wp_register_style('fluid',    get_bloginfo('stylesheet_url'),false, FLUIDITY_VERSION);
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('tcc-fawe');
    wp_enqueue_style('library');
    wp_enqueue_style('fluid');
    wp_enqueue_style('fluid-color');
    wp_register_script('sprintf', "$base_url/js/sprintf.js", null,                     false,true);
    wp_register_script('library', "$base_url/js/library.js", array('jquery','sprintf'),false,true);
    wp_register_script('collapse',"$base_url/js/collapse.js",array('jquery','library'),false,true);
    wp_enqueue_script('bootstrap');
    wp_enqueue_script('collapse');
    if (is_singular() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply'); } // enable threaded comments
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
    wp_register_style('tcc-fawe', get_template_directory_uri()."/css/font-awesome.min.css",false,'4.3.0');
  }
}

if (!function_exists('fluidity_register_color_scheme')) {
  function fluidity_register_color_scheme() {
    if ($color_file=fluid_color_scheme()) {
      wp_register_style('fluid-color',  get_template_directory_uri()."/css/colors/$color_file.css",false,FLUIDITY_VERSION);
    }
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
