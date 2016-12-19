<?php

/*
 *  File:  includes/index.php
 *
 */

function fluid_index_page($page='index') { ?>

	<main><?php
log_entry('dump',"page slug: $page");
log_entry(0,'paral:  '.tcc_design('paral'));
		if (tcc_design('paral')==='yes') {
			$pageID = tcc_get_page_id_by_slug($page);
log_entry("page ID: $pageID");
			if ($pageID) {
				if (has_post_thumbnail($pageID)) {
					$imgID  = get_post_thumbnail_id($pageID);
					$imgURL = wp_get_attachment_url( $imgID ); ?>
					<style>
						.parallax-image { background-image: url("<?php echo $imgURL; ?>"); height:400px; }
					</style>
					<div class="parallax-top parallax-image">
					</div><?php
				}
			}
		} ?>




	<div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>>
		<div class="row"><?php
			who_am_i(1);
			do_action("fluid_{$page}_page_top"); ?>

			<div class="fluid-sidebar hidden-sm hidden-xs"><?php
				fluidity_sidebar_layout($page); ?>
			</div>

			<div id="content" role="main" tabindex="-1"><?php
				do_action("fluid_{$page}_page_title");
				if (have_posts()) {

					$main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
					while (have_posts ()) {
						the_post(); ?>

						<div <?php microdata()->BlogPosting(); ?>><?php
							$slug = fluid_content_slug($page);
							get_template_part("template-parts/$main",$slug); ?>
						</div><?php

						fluid_post_separator($page);
					}
					fluid_navigation();
					do_action("fluid_{$page}_page_afterposts");

				} else {
					do_action("fluid_{$page}_page_noposts");
				} ?>
			</div><!-- #content -->

			<div class="fluid-sidebar visible-sm visible-xs"><?php
				fluidity_sidebar_layout($page); ?>
			</div>

		</div><!-- .row -->
	</div><!-- .container -->
</main><?php

}
