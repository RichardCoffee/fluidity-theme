<?php
/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

define( 'ABSPATH' ) || exit;

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

	<?php do_action( 'fluid_content_header' ); ?>

	<div class="article" itemprop="articleBody">
		<?php the_content(); ?>
	</div>

	<?php do_action( 'fluid_content_footer' ); ?>

</article>
