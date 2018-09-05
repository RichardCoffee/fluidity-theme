<?php
/*
 *  File Name: template-parts/content.php
 *
 * @since 20160301
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/template-parts/content.php
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

defined( 'ABSPATH' ) || exit;

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?> role="article">

	<header><?php
		do_action( 'fluid_content_header' ); ?>
	</header>

	<section class="article" itemprop="articleBody"><?php
		the_content(); ?>
	</section>

	<footer><?php
		do_action( 'fluid_content_footer' ); ?>
	</footer>

</article>
