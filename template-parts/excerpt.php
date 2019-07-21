<?php
/*
 *  File Name:  excerpt.php
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/template-parts/excerpt.php
 * @link https://wordpress.stackexchange.com/questions/38030/is-there-a-has-more-tag-method-or-equivalent
 */

defined( 'ABSPATH' ) || exit;

who_am_i(); ?>

<div class="<?php clearfix()->div_class(); ?>">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>><?php
		fluid()->element( 'link', [ 'itemprop' => 'mainEntityOfPage', 'href' => get_the_permalink() ] ); ?>

		<header class="excerpt-title-wrap"><?php
			do_action( 'fluid_excerpt_header' ); ?>
		</header>

		<section class="article" itemprop="description"><?php
			// Choose the manual excerpt if it exists.
			if ( has_excerpt() ) {
				the_excerpt();
			} else {
				$info = get_extended( $GLOBALS['post']->post_content );
				// Is there a more tag? Then use the teaser.
				if( ! empty( $info["extended"] ) ) {
					global $more;
					$more = 0;
					the_content( fluid_read_more_text() );
					$more = 1;
				} else {
					// Otherwise make an automatic excerpt.
					the_excerpt();
				}
			} ?>
		</section>

	</article>

</div><?php

clearfix()->apply();
