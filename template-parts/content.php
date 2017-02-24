<?php

/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

	<?php fluid_thumbnail();

	if ( ! is_page() ) { ?>

		<h1 class="text-center">
			<?php tcc_post_title(); ?>
			<?php fluid_edit_post_link(); ?>
		</h1>

		<h3 class="post-date text-center">
			<?php fluid_post_date( true ); ?>
		</h3><?php

	} ?>


	<div class="article" itemprop="articleBody">
		<?php the_content(); ?>
	</div><?php

	if ( is_single() ) {
		$taxonomy = apply_filters( 'fluid_content_taxonomy', 'category' );
log_entry('before fluid_navigation');
		fluid_navigation( $taxonomy, true );
log_entry('after fluid_navigation');
		fluid_postmetadata();
	}

	tcc_show_comments(); ?>

</article>
