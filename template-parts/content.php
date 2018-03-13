<?php

/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>><?php

	if ( ! tcc_is_page() ) {
		fluid_content_header();
	}

	fluid_thumbnail(); ?>

	<div class="article" itemprop="articleBody">
		<?php the_content(); ?>
	</div><?php

	if ( is_single() ) {
		$taxonomy = apply_filters( 'fluid_content_taxonomy', 'category' );
#		if ( current_user_can( 'manage_options' ) ) {
			new TCC_Theme_Navigation( array( 'taxonomy' => $taxonomy ) );
#		} else {
#			fluid_navigation( $taxonomy, true );
#		}
		echo "<p> </p>";
		fluid_postmetadata();
	}

	tcc_show_comments(); ?>

</article>
