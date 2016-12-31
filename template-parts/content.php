<?php

/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

who_am_i(); ?>


<article id="post-<?php the_ID(); ?> " <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

	<?php fluid_thumbnail();

	if (!is_page()) { ?>

		<h1 class="text-center">
			<?php tcc_post_title(); ?>
			<?php fluid_edit_post_link(); ?>
		</h1><?php

		fluid_post_date(true);
	} ?>

	<div class="article" itemprop="articleBody">
		<?php the_content(); ?>
	</div><?php

	if (is_single()) {
		$mycat = '';
		$cats  = get_the_category();
		if ($cats) { $mycat = $cats[0]->slug; }
		fluid_navigation($mycat,true);
		fluid_postmetadata();
	} ?>

</article>
