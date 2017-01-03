<?php
/*
 *  File Name: stock.php
 *
 */

get_header();

$page = get_page_slug(); ?>

<main><?php

	if (is_page()) {
		tcc_page_parallax($page);
		tcc_page_title($page); // FIXME:  make title bar an option
	} ?>

	<div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>><?php

		who_am_i();
		do_action("fluid_{$page}_page_top"); ?>

		<div class="fluid-sidebar hidden-sm hidden-xs"><?php
			fluidity_sidebar_layout($page); ?>
		</div>

		<div id="content" role="main" tabindex="-1"><?php

			do_action("fluid_{$page}_page_title");

			if (have_posts()) {

				$main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
				while (have_posts ()) {
					the_post();
					get_template_part("template-parts/$main",fluid_content_slug($page));
					fluid_post_separator($page);
				}
				do_action("fluid_{$page}_page_afterposts");

			} else {
				do_action("fluid_{$page}_page_noposts");
			} ?>

		</div><!-- #content -->

		<div class="fluid-sidebar visible-sm visible-xs"><?php
			fluidity_sidebar_layout($page); ?>
		</div>

	</div><!-- #fluid-content -->
</main><?php

get_footer();
