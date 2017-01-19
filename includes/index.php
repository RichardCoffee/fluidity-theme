<?php

/*
 *  File:  includes/index.php
 *
 */

function fluid_index_page( $page='index' ) {

do_action("tcc_{$page}_page_top"); ?>

<main><?php

	if (is_page()) {
		if (tcc_design('paral')==='yes') {
			tcc_page_parallax($page);
		}
	} else if (is_page() || is_home()) {
		// FIXME:  make title bar an option
		tcc_page_title($page);
	} ?>


	<div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>>
		<div class="row"><?php
			who_am_i(1); ?>

			<div class="fluid-sidebar hidden-sm hidden-xs">
				<?php fluidity_sidebar_layout($page); ?>
			</div>

			<div id="content" role="main" tabindex="-1"><?php
				if (have_posts()) {

					do_action("tcc_{$page}_page_preposts");

					$main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
					while (have_posts ()) {
						the_post();
						$slug = fluid_content_slug($page);
						get_template_part("template-parts/$main",$slug);
						if (!is_singular()) {
							fluid_post_separator($page); }
					}

					do_action("tcc_{$page}_page_afterposts");
				} else {
					do_action("tcc_{$page}_page_noposts");
				} ?>
			</div><!-- #content -->

			<div class="tcc-sidebar visible-sm visible-xs">
				<?php fluidity_sidebar_layout($page); ?>
			</div>

		</div><!-- .row -->
	</div><!-- #fluid_content -->

	<?php do_action("tcc_{$page}_page_bottom"); ?>

</main><?php

}
