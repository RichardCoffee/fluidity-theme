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
