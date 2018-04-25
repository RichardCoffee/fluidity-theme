<?php
/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

defined( 'ABSPATH' ) || exit;

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

	<header>
		<?php do_action( 'fluid_content_header' ); ?>
	</header>

	<section class="article" itemprop="articleBody">
		<?php the_content(); ?>
	</section>

	<footer>
		<?php do_action( 'fluid_content_footer' ); ?>
	</footer>

</article>
