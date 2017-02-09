<?php

/**
 * The template for displaying 404 pages (Not Found)
 */

defined( 'ABSPATH' ) || exit;
$mypage = get_page_slug( '404' );
add_filter( 'fluid_404_container_type', function( $args ) { return 'container'; } );

get_header();

if (WP_DEBUG && is_user_logged_in() && current_user_can('update_core')) { ?>
	<div class="panel panel-fluidity collapse-auto">
		<div class="panel-heading">
			<h3 class="panel-title">Word Press Information</h3>
		</div>
		<div class="panel-body">
			<pre><?php
				print_r($_GET);
				print_r($_POST);
				print_r($wp_query); ?>
			</pre><?php
			debug_rewrite_rules(); ?>
		</div>
	</div><?php
} //*/ ?>

<div class="<?php container_type( $mypage ); ?>">
	<div class="row text-center">
		<div class="col-md-12">
			<article>

				<h2  class="page-title">
					<?php esc_html_e( "Ooops.....Well this is somewhat embarrassing, isn't it?", 'tcc-fluid' ); ?>
				</h2>

				<div class="page-content">

					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<p><?php esc_html_e( 'It seems as if the page you are looking for is not here. I guess you could hang out here all day if you really wanted to. But if ya wanted to you could.', 'tcc-fluid' ); ?><a href="/about-us/">click here</a> and try again. :-)</p>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
							<?php get_search_form(); ?>
						</div>
					</div>

					<h1>
						<a href="<?php echo home_url() ; ?>">
							<?php esc_html_e( 'Home Page', 'tcc-fluid' ); ?>
						</a>
					</h1>

				</div><!-- .page-content -->
			</article>
		</div><!-- .col-md-12 -->
	</div><!-- .row -->
</div><!-- .<?php container_type( $mypage ); ?> --><?php


get_footer(); ?>
