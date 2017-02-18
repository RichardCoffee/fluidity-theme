<?php
/*
 *  File Name: stock.php
 *
 */

get_header();

$mypage = get_page_slug();

#add_filter ( "fluid_{$mypage}_container_type", function ( $css ) { return 'container'; } );
#add_filter ( "fluid_sidebar_css_$mypage",      function ( $css ) { return "col-md-2 $css"; } );
#add_filter ( "tcc_main_css_$mypage",           function ( $css ) { return "col-md-10 $css"; } );

#do_action( "tcc_top_$mypage" ); ?>

<div id="fluid-content" class="fluid-<?php e_esc_attr( $mypage); ?> <?php e_esc_attr( container_type( $mypage ) ); ?>" <?php microdata()->Blog(); ?>>

<?php
		if ( is_page() ) {
			tcc_page_parallax( $mypage );
		}
		if ( is_page() || is_archive() ) {
			#echo get_page_title( $mypage );
			tcc_page_title( $mypage ); // FIXME
		} ?>

	<div class="row">
		<?php who_am_i(); ?>

		<?php tcc_sidebar( $mypage, array( 'hidden-sm', 'hidden-xs' ) ); ?>

		<main id="content" role="main" tabindex="-1"><?php

			do_action( "tcc_before_posts_$mypage" );

			if ( have_posts() ) {

				do_action( "tcc_before_loop_$mypage" );

				$root = ( is_single() || is_page() ) ? 'content' : tcc_layout( 'content' );
				while ( have_posts () ) {
					the_post();
					$stem = fluid_content_slug( $mypage );
					get_template_part( "template-parts/$root", $stem );
					if ( ! is_singular() ) {
						fluid_post_separator( $mypage );
					}
				}

				if ( ! is_singular() ) { ?>
					<div class="row">
						<div class="text-wide text-center">
						<?php pagination(); ?>
					</div>
					</div><?php
				}

#				do_action( "tcc_after_loop_$mypage" );
			} else {
#				do_action( "tcc_no_loop_$mypage" );
			}

#			do_action( "tcc_after_posts_$mypage" ); ?>

		</main><!-- #content -->

		<?php tcc_sidebar( $mypage, array( 'visible-sm', 'visible-xs' ) ); ?>

	</div>
</div><!-- #fluid-content --><?php

#do_action( "tcc_bottom_$mypage" );

get_footer();
