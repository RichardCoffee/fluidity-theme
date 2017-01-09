<?php
/*
 *  File Name: stock.php
 *
 */

get_header();

$page = get_page_slug();
do_action("tcc_{$page}_page_top"); ?>

<main><?php

	if (is_page()) {
		tcc_page_parallax($page);
		tcc_page_title($page); // FIXME:  make title bar an option
	} ?>

	<div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>><?php
		who_am_i(); ?>

		<div class="fluid-sidebar hidden-sm hidden-xs">
			<?php fluidity_sidebar_layout($page); ?>
		</div>

		<div id="content" role="main" tabindex="-1"><?php

			if (have_posts()) {
				do_action("tcc_{$page}_page_title");

				$main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
				while (have_posts ()) {
					the_post();
					get_template_part("template-parts/$main",fluid_content_slug($page));
					if (!is_singular()) {
						fluid_post_separator($page); }
				}

				do_action("tcc_{$page}_page_afterposts");
			} else {
				do_action("tcc_{$page}_page_noposts");
			} ?>

		</div><!-- #content -->

		<div class="fluid-sidebar visible-sm visible-xs"><?php
			fluidity_sidebar_layout($page); ?>
		</div>

	</div><!-- #fluid-content -->

</main><?php

do_action("tcc_{$page}_page_bottom");

get_footer();
