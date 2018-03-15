<?php

/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

	<?php do_action( 'fluid_content_header' ); ?>

	<div class="article" itemprop="articleBody">
		<?php the_content(); ?>
	</div><?php

	if ( is_single() && ! tcc_is_page() ) {
		$taxonomy = apply_filters( 'fluid_content_taxonomy', 'category' );
		new TCC_Theme_Navigation( array( 'taxonomy' => $taxonomy ) );
#		echo "<p> </p>";
#		fluid_postmetadata();
	}

#	tcc_show_comments(); ?>

</article>
