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

			$link_css = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
			if ( has_post_thumbnail() ) { ?>
				<div class="col-lg-3 col-md-3 hidden-sm hidden-xs"><?php
					fluid_thumbnail(); ?>
				</div><!-- .col-* --><?php
				$link_css = "col-lg-9 col-md-9 col-sm-12 col-xs-12";
			} ?>

			<section class="<?php e_esc_attr( $link_css ); ?> article" itemprop="description"><?php
				the_content(); ?>
			</section><!-- .article -->

		</div><!-- .row -->

	</article>

</div><?php

clearfix()->apply();
