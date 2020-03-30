<?php
/*
 *  Template Part:  excerpt-link.php
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

defined( 'ABSPATH' ) || exit;

who_am_i(); ?>

<div class="<?php clearfix()->div_class(); ?>">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>><?php

		fluid()->element( 'link', [ 'itemprop' => 'mainEntityOfPage', 'href' => get_the_permalink() ] ); ?>

		<div class="row"><?php

			$link_css = 'col-12';
			if ( has_post_thumbnail() ) { ?>
				<div class="hidden-md col-lg-3"><?php
					fluid_thumbnail(); ?>
				</div><!-- .col-* --><?php
				$link_css = 'col-12 col-lg-9';
			} ?>

			<section class="<?php e_esc_attr( $link_css ); ?> article" itemprop="description"><?php
				the_content(); ?>
			</section><!-- .article -->

		</div><!-- .row -->

	</article>

</div><?php

clearfix()->apply();
