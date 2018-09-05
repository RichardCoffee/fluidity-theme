<?php
/*
 *  File Name: template-parts/content-attachment.php
 *
 * @since 20180904
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/template-parts/content-attachment.php
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
		the_attachment_link( 0, 'full' );
#		the_content(); ?>
	</section>

	<footer><?php
		do_action( 'fluid_content_footer' ); ?>
	</footer>

</article>
