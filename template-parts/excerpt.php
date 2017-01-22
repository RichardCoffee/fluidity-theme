<?php

/*
 *  File Name:  excerpt.php
 *
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>><?php

	$format  = esc_html__('Permanent Link to %s','tcc-fluid');
	$tooltip = sprintf($format,get_the_title()); ?>
	<h1 class="text-center" itemprop="headline">
		<?php tcc_post_title(40); ?>
	</h1>

	<?php if (tcc_layout('exdate')==='show') { ?>
		<h2 class="text-center">
			<?php fluid_post_date(); ?>
		</h2><?php
	} ?>

	<div class="article" itemprop="description"><?php
		the_excerpt(); ?>
	</div>

</article>
