<?php
/*
 *  File Name: stock.php
 *
 */

get_header();

$mypage = get_page_slug();

#add_filter ( "fluid_{$mypage}_container_type", function ($css) { return "container"; } );

do_action("tcc_{$mypage}_page_top"); ?>

<main><?php

	if (is_page()) {
		tcc_page_parallax($mypage);
		tcc_page_title($mypage); // FIXME:  make title bar an option
	} ?>

	<div id="fluid-content" class="fluid-<?php echo $mypage; ?> <?php echo esc_attr(container_type($mypage)); ?>" <?php microdata()->Blog(); ?>>
		<div class="row">
			<?php who_am_i(); ?>

			<aside>
				<div class="fluid-sidebar hidden-sm hidden-xs">
					<?php fluidity_sidebar_layout($mypage); ?>
				</div>
			</aside>

			<div id="content" role="main" tabindex="-1"><?php

				if (have_posts()) {
					do_action("tcc_{$mypage}_page_preposts");

					$main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
					while (have_posts ()) {
						the_post();
						$slug = fluid_content_slug($mypage);
						get_template_part( "template-parts/$main", $slug );
						if (!is_singular()) {
							fluid_post_separator($mypage); }
					}

					do_action("tcc_{$mypage}_page_afterposts");
				} else {
					do_action("tcc_{$mypage}_page_noposts");
				} ?>

			</div><!-- #content -->

			<aside>
				<div class="fluid-sidebar visible-sm visible-xs">
					<?php fluidity_sidebar_layout($mypage); ?>
				</div>
			</aside>

		</div>
	</div><!-- #fluid-content -->

</main><?php

do_action("tcc_{$mypage}_page_bottom");

get_footer();
