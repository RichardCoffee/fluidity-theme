<?php
/*
 *  Template Part:  excerpt-link.php
 *
 */

define( 'ABSPATH' ) || exit;

who_am_i(); ?>

<div class="<?php clearfix()->div_class(); ?>">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

		<div class="row"><?php

			$link_css = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
			if ( has_post_thumbnail() ) { // FIXME:  need option for image/title placement ?>
				<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
					<?php fluid_thumbnail(); ?>
				</div><!-- .col-* --><?php
				$link_css = "col-lg-9 col-md-9 col-sm-12 col-xs-12";
			} ?>

			<div class="<?php echo $link_css; ?>">

				<?php fluid_content_header(); ?>

				<div class="article" itemprop="description">
					<?php the_content(); ?>
				</div><!-- .article -->

			</div><!-- .col-* -->
		</div><!-- .row -->

	</article>

	</div>

<?php clearfix()->apply();
