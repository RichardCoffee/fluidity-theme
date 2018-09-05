<?php
/**
 *  functions.php
 *
 * @package Fluidity
 * @subpackage Main
 * @since 20150501
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/functions.php
 */
defined( 'ABSPATH' ) || exit;

define( 'FLUIDITY_HOME', trailingslashit( dirname( __FILE__ ) ) );  #  get current directory
$data = get_file_data( FLUIDITY_HOME . 'style.css', array( 'ver' => 'Version' ) );
define( 'FLUIDITY_VERSION', $data['ver'] );

defined( 'TCC_THEME_VERSION' ) or define( 'TCC_THEME_VERSION', FLUIDITY_VERSION );

require_once( 'includes/loaders.php' );
require_once('includes/debugging.php');              #  load logging function as soon as possible
require_once(FLUIDITY_HOME.'includes/update.php');   #  Needs full path, or wp-admin/includes/update.php gets loaded instead
require_once('includes/bootstrap.php');
require_once(FLUIDITY_HOME.'includes/options.php');  #  Needs full path, or wp-admin/includes/options.php gets loaded instead
require_once( 'includes/enqueue.php' );
require_once('includes/library.php');
require_once('includes/menus.php');
require_once(FLUIDITY_HOME.'includes/misc.php');     #  Needs full path, or wp-admin/includes/misc.php gets loaded instead
require_once( 'includes/pages.php' );
require_once('includes/parallax.php');
require_once('includes/sidebars.php');
require_once( 'includes/support.php' );
require_once( 'includes/third-party.php' );
require_once(FLUIDITY_HOME.'includes/widgets.php');  #  Needs full path, or wp-admin/includes/widgets.php gets loaded instead
add_action( 'rest_api_init', function() {
	require_once( 'includes/wp-rest-api.php' );
});

fluidity_check_update();

#  prime the pump
require_once( 'classes/autocomplete.php' );
fluid_register_sidebars(); # TCC_Register_Sidebars
fluid_theme_support();     # TCC_Theme_Support
fluid_login();             # TCC_Theme_Login
fluid_color();             # TCC_Theme_ColorScheme
fluid_customizer();        # TCC_Theme_Customizer
new TCC_Options_Survey;    # check about sending plugin list for survey

if ( is_admin() ) {
	fluid_options();
	require_once( FLUIDITY_HOME . 'includes/admin.php' );      #  Needs full path, or wp-admin/includes/admin.php gets loaded instead
	require_once( FLUIDITY_HOME . 'includes/dashboard.php' );  #  Needs full path, or wp-admin/includes/dashboard.php gets loaded instead
	require_once( FLUIDITY_HOME . 'includes/plugins.php' );
} else {
	require_once( 'includes/author.php' );
	require_once('includes/comments.php');
	require_once('includes/footer.php');
	require_once('includes/header.php');
	require_once('includes/in-the-loop.php');
	require_once('classes/microdata.php');
}


/**  Test functions  **/
/*
add_filter( 'heartbeat_received', function ( $resource, $data ) {
	fluid(1)->log( $resource, $data );
	return $resource;
}, 10, 2 ); //*/

#global $shortcode_tags;
#fluid()->log( $shortcode_tags );
