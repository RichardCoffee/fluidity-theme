<?php
/**
 *  functions.php
 *
 */

define( 'FLUIDITY_HOME', trailingslashit( dirname( __FILE__ ) ) );  #  get current directory
$data = get_file_data( FLUIDITY_HOME . 'style.css', array( 'ver' => 'Version' ) );
define( 'FLUIDITY_VERSION', $data['ver'] );

defined( 'TCC_THEME_VERSION' ) or define( 'TCC_THEME_VERSION', FLUIDITY_VERSION );

require_once('includes/loader.php');
require_once('includes/debugging.php');              #  load logging function as soon as possible
require_once(FLUIDITY_HOME.'includes/update.php');   #  Needs full path, or wp-admin/includes/update.php gets loaded instead
require_once('includes/bootstrap.php');
require_once(FLUIDITY_HOME.'includes/options.php');  #  Needs full path, or wp-admin/includes/options.php gets loaded instead
require_once('includes/colors.php');                 #  options.php must be loaded before colors.php
require_once( 'includes/enqueue.php' );
require_once('includes/library.php');
require_once('includes/menus.php');
require_once(FLUIDITY_HOME.'includes/misc.php');     #  Needs full path, or wp-admin/includes/misc.php gets loaded instead
require_once('includes/parallax.php');
require_once('includes/sidebars.php');
require_once( 'includes/support.php' );
require_once( 'includes/third-party.php' );
require_once(FLUIDITY_HOME.'includes/widgets.php');  #  Needs full path, or wp-admin/includes/widgets.php gets loaded instead
add_action( 'rest_api_init', function() {
	require_once( 'includes/wp-rest-api.php' );
});

fluidity_check_update();

#  prime classes
require_once( 'classes/autocomplete.php' );
fluid_register_sidebars(); # TCC_Register_Sidebars
fluid_theme_support();     # TCC_Theme_Support
fluid_login();             # TCC_Theme_Login class
fluid_customizer();        # TCC_Options_Customizer

if ( is_admin() ) {
	fluid_options();
	require_once( FLUIDITY_HOME . 'includes/admin.php' );      #  Needs full path, or wp-admin/includes/admin.php gets loaded instead
	require_once( FLUIDITY_HOME . 'includes/dashboard.php' );  #  Needs full path, or wp-admin/includes/dashboard.php gets loaded instead
} else {
	require_once('includes/comments.php');
	require_once('includes/footer.php');
	require_once('includes/header.php');
	require_once('includes/in-the-loop.php');
	require_once('includes/pages.php');
	require_once('classes/microdata.php');
}


/**  Test functions  **/

fluid()->log( get_theme_mod( 'custom-logo' ) );

/*function themeslug_customize_register( WP_Customize_Manager $wp_customize ) {
  fluid()->log( $wp_customize );
}
add_action( 'customize_register', 'themeslug_customize_register' ); //*/

add_filter( 'heartbeat_received', function ( $resource, $data ) {
	fluid()->log( $resource, $data );
	return $resource;
}, 10, 2 ); //*/

/*add_action( 'wp_loaded', function () {
#		global $wp_filter;
#		log_entry( $wp_filter );
#		$filter = 'logout_redirect';
		$filter = 'the_content';
		log_entry( list_filter_hooks( $filter ) );
	}
); //*/

/*add_action( 'tcc_inside_page', function( $slug ) {
	tellme( 'color scheme:  ' . fluid_color_scheme() );
} ); //*/

/*add_action( 'wp_loaded', function () {
	$color   = new TCC_Options_ColorScheme;
	$schemes = $color->get_available_color_schemes( array( 'none' => __( 'Do not use internal color scheme', 'tcc-fluid' ) ) );
	fluid()->log( $schemes );
} ); //*/

fluid()->log( array(
	'api_status'     => tcc_option( 'status', 'apicontrol', 'on' ),
	'social_active'  => tcc_option( 'active', 'social', 'no' ),
	'content_exlen'  => tcc_option( 'exlength', 'content', 55 ),
	'content_exdate' => tcc_option( 'exdate', 'content', 'show' )
	'content_post'   => tcc_option( 'postdate', 'content', 'original' )
) );
