<?php
/*
 *  File Name: stock.php
 *
 */

define( 'ABSPATH' ) || exit;

get_header();

$mypage = get_page_slug();

TCC_Theme_Sidebar::get_instance( array() );	#	See docs/sidebar.txt on what values the array can have

do_action( 'tcc_page_top', $mypage ); ?>

<div id="fluid-content" class="fluid-<?php e_esc_attr( $mypage); ?> <?php e_esc_attr( container_type( $mypage ) ); ?>" <?php microdata()->Blog(); ?>>

	<?php do_action( 'tcc_inside_page', $mypage ); ?>

	<div class="row">
		<?php who_am_i(); ?>

		<?php do_action( 'tcc_before_main', $mypage ); ?>

		<main id="content" class="<?php echo tcc_main_tag_css( '', $mypage ); ?>" role="main" tabindex="-1"><?php

			do_action( 'tcc_before_posts', $mypage );

			if ( have_posts() ) {

				do_action( 'tcc_before_loop', $mypage );

				$root = ( is_single() || is_page() || is_singular() ) ? 'content' : tcc_content( 'content', 'excerpt' );
				$root = apply_filters( 'tcc_template-parts_root', $root, $mypage );
				while ( have_posts () ) { ?>
					<div><?php
						the_post();
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
							<?php new TCC_Theme_Pagination(); ?>
						</div>
					</div><?php
				}

#				do_action( 'tcc_after_loop', $mypage );
			} else {
#				do_action( 'tcc_no_loop', $mypage );
			}

			do_action( 'tcc_after_posts', $mypage ); ?>

		</main><!-- #content -->

		<?php do_action( 'tcc_after_main', $mypage ); ?>

	</div>
</div><!-- #fluid-content --><?php

#do_action( 'tcc_page_bottom', $mypage );

get_footer();
