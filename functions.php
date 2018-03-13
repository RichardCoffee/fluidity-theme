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

require_once('includes/loader.php');
require_once('includes/debugging.php');              #  load logging function as soon as possible
require_once(FLUIDITY_HOME.'includes/update.php');
require_once('includes/bootstrap.php');
require_once(FLUIDITY_HOME.'includes/options.php');  #  Needs full path, or wp-admin/includes/options.php gets loaded instead
require_once('includes/colors.php');                 #  options.php must be loaded before colors.php
require_once( 'includes/enqueue.php' );
require_once('includes/library.php');
require_once('includes/login.php');
require_once('includes/menus.php');
require_once(FLUIDITY_HOME.'includes/misc.php');
require_once('includes/parallax.php');
require_once('includes/sidebars.php');
require_once(FLUIDITY_HOME.'includes/widgets.php');  #  Needs full path, or wp-admin/includes/widgets.php gets loaded instead
add_action( 'rest_api_init', function() {
	require_once('includes/wp-rest-api.php');
});
require_once( 'classes/autocomplete.php' );

new TCC_Theme_Support;

if ( is_admin() ) {
	TCC_Options_Fluidity::instance();
} else {
	require_once('includes/comments.php');
	require_once('includes/footer.php');
	require_once('includes/header.php');
	require_once('includes/in-the-loop.php');
	require_once('includes/pages.php');
	require_once('includes/third-party.php');
	require_once('classes/microdata.php');
	TCC_Form_Login::instance();
}

if ( ! function_exists( 'fluidity_theme_scandir_exclusions' ) ) {
	function fluidity_theme_scandir_exclusions( $exclusions ) {
		$exclusions = array_merge( $exclusions, array(
			'docs',
			'fonts',
			'icons',
			'languages',
			'scss',
		) );
		#	add these exclusions when WP is checking for page templates
		if ( was_called_by( 'page_attributes_meta_box' ) ) {
			$exclusions = array_merge( $exclusions, array(
				'classes',
				'css',
				'includes',
				'js',
				'template-parts',
			) );
		}
		return $exclusions;
	}
	add_filter( 'theme_scandir_exclusions', 'fluidity_theme_scandir_exclusions' );
}

/**  Test functions  **/
/*
function themeslug_customize_register( $wp_customize ) {
  log_entry( $wp_customize );
}
add_action( 'customize_register', 'themeslug_customize_register' ); //*/
/*
function tcc_template_test( $template, $stem ) {
	log_entry('slug: '.$template,'stem: '.$stem);
}
add_action( 'get_template_part_template-parts/header', 'tcc_template_test',0,2);
add_action( 'get_template_part_template-parts/header', 'tcc_template_test',1001,2); //*/
/*add_action( 'wp_loaded', function () {
#		global $wp_filter;
#		log_entry( $wp_filter );
#		$filter = 'logout_redirect';
		$filter = 'the_content';
		log_entry( list_filter_hooks( $filter ) );
	}
); //*/
add_filter('tcc_template-parts_root', function( $rootslug, $pageslug ) {
	if ( $pageslug === 'forum' ) {
		return 'content';
	}
	return $rootslug;
}, 10, 2);

