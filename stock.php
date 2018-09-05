<?php
/**
 * @package Fluidity
 * @subpackage Main
 * @since 20161206
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

defined( 'ABSPATH' ) || exit;

$mypage       = get_page_slug();
$page_sidebar = fluid_sidebar();

get_header();
who_am_i();

do_action( 'fluid_page_top', $mypage ); ?>

<div id="fluid-content" class="fluid-<?php e_esc_attr( $mypage); ?> <?php e_esc_attr( container_type( $mypage ) ); ?>" <?php microdata()->Blog(); ?>>

	<?php do_action( 'fluid_inside_page', $mypage ); ?>

	<div class="row">

		<?php do_action( 'fluid_before_main', $mypage ); ?>

		<main id="content" class="<?php e_esc_attr( $page_sidebar->main_tag_css( $mypage, '' ) ); ?>" tabindex="-1"><?php

			do_action( 'fluid_before_posts', $mypage );
			if ( have_posts() ) {
				do_action( 'fluid_before_loop', $mypage );

				$dir  = apply_filters( 'fluid_loop_template_dir', 'template-parts', $mypage );
				$root = ( is_singular() ) ? 'content' : get_theme_mod( 'content_excerpt', 'excerpt' );
				$root = apply_filters( 'fluid_loop_template_root', $root, $mypage );
				while ( have_posts () ) { ?>
					<div class="post-loop"><?php
						the_post();
						$stem = fluid_content_slug( $mypage );
						get_template_part( "$dir/$root", $stem );
						if ( ! is_singular() ) {
							fluid_post_separator( $mypage );
						} ?>
					</div><?php
				}

				if ( ! is_singular() ) { ?>
					<div class="row">
						<div class="text-wide text-center">
							<?php fluid_pagination(); ?>
						</div>
					</div><?php
				}

				do_action( 'fluid_after_loop', $mypage );
			} else {
				do_action( 'fluid_no_loop', $mypage );
			}
			do_action( 'fluid_after_posts', $mypage ); ?>

		</main><!-- #content -->

		<?php do_action( 'fluid_after_main', $mypage ); ?>

	</div>

</div><!-- #fluid-content --><?php

do_action( 'fluid_page_bottom', $mypage );

get_footer();
