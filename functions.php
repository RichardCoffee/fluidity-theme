<?php
/*
 *  tcc-fluidity/functions.php
 *
 */

define('FLUIDITY_HOME',trailingslashit(dirname(__FILE__)));  #  get current directory
$data = get_file_data( FLUIDITY_HOME.'style.css', array( 'ver' => 'Version' ) );
define('FLUIDITY_VERSION',$data['ver']);
defined('TCC_THEME_VERSION') or define('TCC_THEME_VERSION',FLUIDITY_VERSION);

require_once('includes/loader.php');
require_once('includes/debugging.php');              #  load logging function as soon as possible
require_once('includes/james.php');
require_once('includes/theme-support.php');
require_once(FLUIDITY_HOME.'includes/options.php');  #  Needs full path, or wp-admin/includes/options.php gets loaded instead
require_once('includes/colors.php');                 #  options.php must be loaded before colors.php
require_once('includes/footer.php');
require_once('includes/library.php');
require_once('includes/login.php');
require_once('includes/menus.php');
require_once('includes/misc.php');
require_once('includes/parallax.php');
require_once('includes/sidebars.php');
require_once(FLUIDITY_HOME.'includes/widgets.php');  #  Needs full path, or wp-admin/includes/widgets.php gets loaded instead

require_once('classes/autocomplete.php');
require_once('classes/form-fields.php');

if (is_admin()) {
	require_once('classes/admin-form.php');
	TCC_Options_Fluidity::get_instance();
} else {
  //require_once('includes/footer.php');
  #require_once('includes/defines.php');
  require_once('includes/header.php');
  require_once('includes/in-the-loop.php');
  require_once('includes/index.php');
  require_once('includes/pages.php');
  require_once('includes/third-party.php');
  require_once('classes/microdata.php');
}

if (!function_exists('tcc_enqueue')) {
  function tcc_enqueue() {

    do_action('tcc_pre_enqueue');

    #  Register
    fluidity_register_bootstrap();
    fluidity_register_fontawesome();
    fluidity_register_color_scheme();
    fluidity_register_css_js();

	#	Stylesheets
    wp_enqueue_style('tcc-fawe');	#  font-awesome needs to be loaded before bootstrap, due to css conflict (sr-only)
    if (tcc_option('active','social')==='yes') {
		 wp_enqueue_style('fa-social'); }
    wp_enqueue_style('bootstrap.css');
    wp_enqueue_style('fluidity');
    wp_enqueue_style('fluid-color');

    #  Javascript
    if (!(tcc_layout('menu')==='bootstrap')) {
      wp_enqueue_script( '_s-navigation', get_theme_file_uri('js/navigation.js'), array(), '20151215', true );
    }
    wp_enqueue_script('bootstrap.js');
    wp_enqueue_script('tcc-skiplink');
    if ((tcc_layout('widget')!=='perm') || is_404()) {
      wp_enqueue_script('tcc-collapse'); }
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply'); }  #  enable threaded comments

    // experimental
    $hdr_state = tcc_layout('header');
    if ($hdr_state==='fixed') {
      wp_enqueue_script('tcc-fixed');
    } else if ($hdr_state==='reduce') {
      wp_enqueue_script('tcc-reduce-css');
      wp_enqueue_script('tcc-reduce-js');
    } else if ($hdr_state==='hide') {
      #add_action('wp_footer','fluid_footer_autohide',99);
    }

    do_action('tcc_after_enqueue');
    do_action('tcc_enqueue');  #  load child theme enqueues here

  }
  add_action('wp_enqueue_scripts','tcc_enqueue');
}

if (!function_exists('fluidity_register_css_js')) {
	function fluidity_register_css_js() {
		wp_register_style('fa-social',      get_theme_file_uri("css/fa-social-hover.css"), array('tcc-fawe'), FLUIDITY_VERSION);
		wp_register_style('fluidity',       get_theme_file_uri("style.css"),               null,              FLUIDITY_VERSION);
		wp_register_style('tcc-reduce-css', get_theme_file_uri("css/header-reduce.css"),   null,              FLUIDITY_VERSION);
		wp_register_script('tcc-sprintf',   get_theme_file_uri("js/sprintf.js"),       null,                          FLUIDITY_VERSION, true);
		wp_register_script('tcc-library',   get_theme_file_uri("js/library.js"),       array('jquery','tcc-sprintf'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-collapse',  get_theme_file_uri("js/collapse.js"),      array('jquery','tcc-library'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-skiplink',  get_theme_file_uri("js/skip-link-focus-fix.js"), array('jquery'),         FLUIDITY_VERSION, true);
		wp_register_script('tcc-fixed',     get_theme_file_uri("js/header-fixed.js"),  array('jquery'),               FLUIDITY_VERSION, true);
		wp_register_script('tcc-reduce-js', get_theme_file_uri("js/header-reduce.js"), array('jquery'),               FLUIDITY_VERSION, true);
	}
}

if (!function_exists('fluidity_admin_enqueue')) {
  function fluidity_admin_enqueue() {
    fluidity_register_fontawesome();
    fluidity_register_css_js();
    wp_enqueue_style('tcc-fawe');
    do_action('fluidity_admin_enqueue');
  }
  add_action('admin_enqueue_scripts','fluidity_admin_enqueue');
}

if (!function_exists('fluidity_register_bootstrap')) {
  function fluidity_register_bootstrap() {
    $base_url = get_template_directory_uri();
    wp_register_style('bootstrap.css', "$base_url/css/bootstrap.min.css", null,           '3.3.7');
    wp_register_script('bootstrap.js', "$base_url/js/bootstrap.min.js",   array('jquery'),'3.3.7',true);
  }
} else {
  function fluidity_bootstrap_backup() {
    wp_enqueue_style('bootstrap-backup', get_theme_file_uri('css/bootstrap-backup.min.css'),null,FLUIDITY_VERSION);
  }
  add_action('tcc_pre_enqueue','fluidity_bootstrap_backup');
}

if (!function_exists('fluidity_register_fontawesome')) {
  function fluidity_register_fontawesome() {
    wp_register_style('tcc-fawe', get_template_directory_uri()."/css/font-awesome.min.css",false,'4.6.3');
  }
}

if (!function_exists('fluidity_register_color_scheme')) {
  function fluidity_register_color_scheme() {
    if ($color_file=fluid_color_scheme()) {
      wp_register_style('fluid-color',  get_theme_file_uri("css/colors/$color_file.css"),false,FLUIDITY_VERSION);
    }
  }
}

##  simple query template
if (!function_exists('fluidity_show_query')) { // FIXME: move this
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

/*
if (!is_child_theme()) {
  require_once('includes/collective.php');
}*/
