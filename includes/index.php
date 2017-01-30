<?php

/*
 *  File:  includes/index.php
 *
 */

function fluid_index_page( $page='index' ) {

	do_action("tcc_{$page}_page_top"); ?>

	<main><?php

		if (is_page()) {
			tcc_page_parallax();
			tcc_page_title($page); // FIXME:  make title bar an option
		} ?>

		<div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo esc_attr(container_type($page)); ?>" <?php microdata()->Blog(); ?>>
			<div class="row"><?php
				who_am_i(1); ?>

				<aside>
					<div class="fluid-sidebar hidden-sm hidden-xs">
						<?php fluidity_sidebar_layout($page); ?>
					</div>
				</aside>

				<div id="content" role="main" tabindex="-1"><?php

					if (have_posts()) {
						do_action("tcc_{$page}_page_preposts");

						$main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
						$css  = ($main==='content') ? '' : apply_filters("tcc_{$page}_excerpt_css",'lg=4&md=4&sm=6&xs=12');
						$cnt  = 0;
						while (have_posts ()) {
							the_post();
							$slug = fluid_content_slug($page); ?>
							<div class="<?php echo esc_attr(tcc_bootstrap_css($css)); ?>"><?php
								get_template_part("template-parts/$main",$slug); ?>
							</div><?php
							tcc_apply_clearfix($css.'&cnt='.++$cnt);
							if (!is_singular()) {
								fluid_post_separator($page); }
						}

						do_action("tcc_{$page}_page_afterposts");
					} else {
						do_action("tcc_{$page}_page_noposts");
					} ?>
				</div><!-- #content -->

				<aside>
					<div class="tcc-sidebar visible-sm visible-xs">
						<?php fluidity_sidebar_layout($page); ?>
					</div>
				</aside>

			</div><!-- .row -->
		</div><!-- #fluid_content -->

	</main>

	<?php do_action("tcc_{$page}_page_bottom");

}
