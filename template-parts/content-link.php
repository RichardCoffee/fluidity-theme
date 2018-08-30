<?php
/*
 *  Template Part:  content-link.php
 *
 */

defined( 'ABSPATH' ) || exit;

who_am_i(); ?>

<div class="<?php clearfix()->div_class(); ?>">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

		<header><?php
/*			$link_css = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
			if ( has_post_thumbnail() ) { // FIXME:  need option for image/title placement ?>
				<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
					<?php fluid_thumbnail(); ?>
				</div><!-- .col-* --><?php
				$link_css = "col-lg-9 col-md-9 col-sm-12 col-xs-12";
			} //*/
			do_action( 'fluid_content_header' ); ?>
		</header>

		<section class="article" itemprop="description"><?php
				the_content(); ?>
		</section><!-- .article -->

		<footer><?php
			do_action( 'fluid_content_footer' ); ?>
		</footer>

	</article>

</div>

<?php clearfix()->apply();
