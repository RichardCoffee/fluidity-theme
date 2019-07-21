<?php
/*
 *  File Name:  excerpt.php
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
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
			global $more;
			$more = 0;

#			the_excerpt();
the_content();
 ?>
		</section>

	</article>

</div><?php

clearfix()->apply();
