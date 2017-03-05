<?php
/*
 *  File Name: stock.php
 *
 */

get_header();

$mypage = get_page_slug();

#add_filter ( "fluid_{$mypage}_container_type", function ( $css ) { return 'container'; } );
#add_filter ( "fluid_sidebar_css_$mypage",      function ( $css, $mypage ) { return 'col-lg-3 col-md-3 col-sm-12 col-xs-12';  }, 10, 2 );
#add_filter ( "tcc_main_tag_css_$mypage",       function ( $css ) { return "col-lg-9 col-md-9 col-sm-12 col-xs-12"; } );

TCC_Theme_Sidebar::get_instance( array() );	#	See docs/sidebar.txt on what values the array can have

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

		<?php do_action( 'tcc_before_main' ); ?>

		<main id="content" class="<?php echo tcc_main_tag_css( '' ); ?>" role="main" tabindex="-1"><?php

#			do_action( 'tcc_before_posts' );
			do_action( "tcc_before_posts_$mypage" );

			if ( have_posts() ) {

				do_action( "tcc_before_loop_$mypage" );

				$root = ( is_single() || is_page() ) ? 'content' : tcc_layout( 'content' );
				while ( have_posts () ) {
					the_post(); ?>
					<div class="col-xs-12 nopad"><?php
						$stem = fluid_content_slug( $mypage );
						get_template_part( "template-parts/$root", $stem );
						if ( ! is_singular() ) {
							fluid_post_separator( $mypage );
						} ?>
					</div><?php
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

#			do_action( "tcc_after_posts_$mypage" );
			do_action( 'tcc_after_posts' ); ?>

		</main><!-- #content -->

		<?php do_action( 'tcc_after_main' ); ?>

	</div>
</div><!-- #fluid-content --><?php

#do_action( "tcc_bottom_$mypage" );

get_footer();
