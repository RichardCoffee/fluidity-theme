<?php
/*
 *  File Name: stock.php
 *
 */

get_header();

$mypage = get_page_slug();

#add_filter ( "fluid_{$mypage}_container_type", function ( $css ) { return 'container'; } );

#do_action( "tcc_top_$mypage" ); ?>

<div id="fluid-content" class="fluid-<?php echo $mypage; ?> <?php echo esc_attr( container_type( $mypage ) ); ?>" <?php microdata()->Blog(); ?>>

<?php
		if ( is_page() ) {
			tcc_page_parallax( $mypage );
		}
		if ( is_page() || is_archive() ) {
			tcc_page_title( $mypage ); // FIXME:  make title bar an option
		} ?>

	<div class="row">
		<?php who_am_i(); ?>

		<?php fluidity_sidebar( 'hidden-sm hidden-xs', $mypage ); # Uses aside tag ?>

		<main class="">
			<div id="content" role="main" tabindex="-1"><?php

				do_action( "tcc_before_posts_$mypage" );

				if ( have_posts() ) {

#					do_action( "tcc_before_loop_$mypage" );

					$main = ( is_single() || is_page() ) ? 'content' : tcc_layout( 'content' );
					while ( have_posts () ) {
						the_post();
						$slug = fluid_content_slug( $mypage );
						get_template_part( "template-parts/$main", $slug );
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

#					do_action( "tcc_after_loop_$mypage" );
				} else {
#					do_action( "tcc_no_loop_$mypage" );
				}

#				do_action( "tcc_after_posts_$mypage" ); ?>
			</div><!-- #content -->
		</main>

		<?php fluidity_sidebar( 'visible-sm visible-xs', $mypage ); # Uses aside tag ?>

	</div>
</div><!-- #fluid-content --><?php

#do_action( "tcc_bottom_$mypage" );

get_footer();
