<?php
/*
 *  File Name:  excerpt.php
 *
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
			the_excerpt(); ?>
		</section>

	</article>

</div>

<?php clearfix()->apply();
