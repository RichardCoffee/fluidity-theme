<?php
/**
 *  tcc-fluidity/functions.php
 *
 */

//this is basically just a header body class that is required for some odd reason to make the theme complete in wordpress's theme authoring test thingy...jlg
if ( ! isset( $content_width ) ) $content_width = 1600;

define( 'FLUIDITY_HOME', trailingslashit( dirname( __FILE__ ) ) );  #  get current directory
$data = get_file_data( FLUIDITY_HOME . 'style.css', array( 'ver' => 'Version' ) );
define( 'FLUIDITY_VERSION', $data['ver'] );

defined( 'TCC_THEME_VERSION' ) or define( 'TCC_THEME_VERSION', FLUIDITY_VERSION );
defined( 'TCC_SIDEBAR_FLOW' )  or define( 'TCC_SIDEBAR_FLOW', true );

require_once('includes/loader.php');
require_once('includes/debugging.php');              #  load logging function as soon as possible
require_once('includes/theme-support.php');
require_once('includes/bootstrap.php');
require_once(FLUIDITY_HOME.'includes/options.php');  #  Needs full path, or wp-admin/includes/options.php gets loaded instead
require_once('includes/colors.php');                 #  options.php must be loaded before colors.php
require_once('includes/comments.php');
require_once('includes/footer.php');
require_once('includes/library.php');
require_once('includes/login.php');
require_once('includes/menus.php');
require_once(FLUIDITY_HOME.'includes/misc.php');
require_once('includes/parallax.php');
require_once('includes/privacy.php');
require_once('includes/sidebars.php');
require_once(FLUIDITY_HOME.'includes/widgets.php');  #  Needs full path, or wp-admin/includes/widgets.php gets loaded instead

require_once('classes/autocomplete.php');
new TCC_MetaBox_GalleryView( array( 'type' => 'post' ) ); // TODO:  turn on/off in theme options

if (is_admin()) {
	TCC_Options_Fluidity::instance();
} else {
	require_once('includes/header.php');
	require_once('includes/in-the-loop.php');
	require_once('includes/pages.php');
	require_once('includes/third-party.php');
	require_once('classes/microdata.php');
	TCC_Form_Login::instance();
}

if ( ! function_exists( 'tcc_enqueue' ) ) {
	function tcc_enqueue() {

		do_action( 'tcc_pre_enqueue' );

		#	Register
		fluidity_register_fontawesome();
		fluidity_register_bootstrap();
		fluidity_register_color_scheme();
		fluidity_register_css_js();

		#	Stylesheets
		wp_enqueue_style('tcc-fawe');	#  font-awesome needs to be loaded before bootstrap, due to css conflict (sr-only)
		if (tcc_option('active','social')==='yes') {
			wp_enqueue_style('fa-social');
		}
		fluidity_enqueue_bootstrap();
#    wp_enqueue_style('fluidity');
#    wp_enqueue_style('fluid-color');

		do_action( 'tcc_during_enqueue' );

		#	Javascript
		if ( tcc_layout('menu') !== 'bootstrap' ) {
			wp_enqueue_script( '_s-navigation', get_theme_file_uri('js/navigation.js'), array(), '20151215', true );
		}
#    wp_enqueue_script('bootstrap.js');
		wp_enqueue_script('tcc-skiplink');
		if ((tcc_layout('widget')!=='perm') || is_404()) {
			wp_enqueue_script('tcc-collapse');
		}
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');  #  enable threaded comments
		}

		// experimental
		$hdr_state = tcc_layout('header');
		if ($hdr_state==='fixed') {
			 wp_enqueue_script('tcc-fixed');
		} else if ($hdr_state==='reduce') {
			wp_enqueue_style('tcc-reduce-css');
			wp_enqueue_script('tcc-reduce-js');
		} else if ($hdr_state==='hide') {
#			add_action('wp_footer','fluid_footer_autohide',99);
		}

		do_action('tcc_after_enqueue');
		do_action('tcc_enqueue');  #  load child theme enqueues here

	}
	add_action('wp_enqueue_scripts','tcc_enqueue');
}

if ( ! function_exists( 'fluidity_register_css_js' ) ) {
	function fluidity_register_css_js() {
		$style = ( WP_DEBUG ) ? 'style.css' : 'css/style.min.css';
		wp_register_style('fa-social',       get_theme_file_uri('css/fa-social-hover.css'), array('tcc-fawe'), FLUIDITY_VERSION);
		wp_register_style('fluidity',        get_theme_file_uri( $style ),                  null,              FLUIDITY_VERSION);
		wp_register_style('tcc-gallery-css', get_theme_file_uri('css/gallery.css'),         null,              FLUIDITY_VERSION);
		wp_register_style('tcc-reduce-css',  get_theme_file_uri('css/header-reduce.css'),   null,              FLUIDITY_VERSION);

		wp_register_script('tcc-sprintf',    get_theme_file_uri('js/sprintf.js'),       null,                          FLUIDITY_VERSION, true);
		wp_register_script('tcc-library',    get_theme_file_uri('js/library.js'),       array('jquery','tcc-sprintf'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-collapse',   get_theme_file_uri('js/collapse.js'),      array('jquery','tcc-library'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-gallery-js', get_theme_file_uri('js/gallery.js'),       array('jquery','tcc-library'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-skiplink',   get_theme_file_uri('js/skip-link-focus-fix.js'), array('jquery'),         FLUIDITY_VERSION, true);
		wp_register_script('tcc-fixed',      get_theme_file_uri('js/header-fixed.js'),  array('jquery'),               FLUIDITY_VERSION, true);
		wp_register_script('tcc-reduce-js',  get_theme_file_uri('js/header-reduce.js'), array('jquery'),               FLUIDITY_VERSION, true);

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
/*
if ( ! function_exists( 'fluidity_register_bootstrap' ) ) {
	function fluidity_register_bootstrap() {
		wp_register_style('bootstrap.css', get_theme_file_uri('css/bootstrap.min.css'), null,           '3.3.7');
		wp_register_script('bootstrap.js', get_theme_file_uri('js/bootstrap.min.js'),   array('jquery'),'3.3.7',true);
	}
} //*/

if (!function_exists('fluidity_register_fontawesome')) {
  function fluidity_register_fontawesome() {
    wp_register_style('tcc-fawe', get_theme_file_uri('css/font-awesome.min.css'),false,'4.6.3');
  }
}

if (!function_exists('fluidity_register_color_scheme')) {
  function fluidity_register_color_scheme() {
    if ($color=fluid_color_scheme()) {
      wp_register_style('fluid-color',  get_theme_file_uri("css/colors/$color.css"),false,FLUIDITY_VERSION);
    }
  }
}

if ( ! function_exists( 'add_privacy_filters' ) && file_exists( WP_CONTENT_DIR . '/privacy.flg' ) ) {
	function add_privacy_filters( $locale = '' ) {
		include_once('classes/privacy.php');
		$instance = Privacy_My_Way::instance();
		return $locale;
	}
	add_action( 'wp_version_check', 'add_privacy_filters' );
	add_filter( 'core_version_check_locale', 'add_privacy_filters' );
}

function themeslug_customize_register( $wp_customize ) {
  log_entry( $wp_customize );
}
add_action( 'customize_register', 'themeslug_customize_register' );

function tcc_template_test( $template ) {
	log_entry('template redirect: '.$template);
}
add_action( 'template_redirect', 'tcc_template_test',0);
add_action( 'template_redirect', 'tcc_template_test',1001);
