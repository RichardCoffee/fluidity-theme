<?php

/*
 *  File Name:  excerpt.php
 *
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

	<div class="row"><?php

		$link_css = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
		if ( has_post_thumbnail() ) { // FIXME:  need option for image/title placement ?>
			<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
				<?php fluid_thumbnail(); ?>
			</div><!-- .col-* --><?php
			$link_css = "col-lg-9 col-md-9 col-sm-12 col-xs-12";
		} ?>

		<div class="<?php echo $link_css; ?>"><?php

			$tooltip = sprintf( esc_html__('Permanent Link to %s','tcc-fluid'), get_the_title()); ?>
			<h1 class="text-center" itemprop="headline">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e($tooltip); ?>"><?php fluid_title(40); ?></a>
			</h1><?php

			if (tcc_layout('exdate')==='show') { fluid_post_date(); } ?>

			<div class="article" itemprop="description"><?php
				the_content(); ?>
			</div><!-- .article -->
		</div><!-- .col-* -->
	</div><!-- .row -->

</article>
