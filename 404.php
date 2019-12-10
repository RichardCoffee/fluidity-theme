<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package Fluidity
 * @subpackage Main
 * @since 20150512
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/404.php
 */
defined( 'ABSPATH' ) || exit;

$mypage = get_page_slug( '404' );
add_filter( 'fluid_404_container_type', function( $args ) { return 'container'; } );

get_header();

if ( WP_DEBUG && is_user_logged_in() && current_user_can( 'update_core' ) ) { ?>
	<div class="panel panel-fluidity collapse-auto">
		<div class="panel-heading">
			Word Press Information
		</div>
		<div class="panel-body">
			<pre><?php
				print_r( $_GET );
				print_r( $_POST );
				print_r( $wp_query ); ?>
			</pre><?php
			debug_rewrite_rules(); ?>
		</div>
	</div><?php
} //*/ ?>

<div class="<?php container_type( $mypage ); ?>">
	<div class="row text-center">
		<div class="col-md-12">
			<article class="error-404 not-found"><?php
				fluid()->element( 'h2', [ 'class' => 'page-title' ], __( "Ooops.....Well this is somewhat embarrassing, isn't it?", 'tcc-fluid' ) ); ?>
				<div class="page-content"><?php
					fluid()->element( 'h3', [ ], __( "Sorry, but we can't seem to find that page.", 'tcc-fluid' ) );
					fluid()->element( 'h4', [ ], __( 'It may have moved or been deleted, or the original link could have been incorrect.', 'tcc-fluid' ) ); ?>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12"><?php
							get_search_form();
							the_widget( 'WP_Widget_Recent_Posts' ); ?>
						</div>
					</div>
				</div><!-- .page-content -->
			</article>
		</div><!-- .col-md-12 -->
	</div><!-- .row -->
</div><!-- .<?php container_type( $mypage ); ?> --><?php

get_footer(); ?>
