<?php
/*
 *  File Name:  excerpt.php
 *
 */

defined( 'ABSPATH' ) || exit;

who_am_i(); ?>

<div class="<?php clearfix()->div_class(); ?>">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

		<div class="excerpt-title-wrap"><?php

			$format  = esc_html__( 'Permanent Link to %s', 'tcc-fluid' );
			$tooltip = sprintf( $format, get_the_title() ); ?>
			<h2 class="text-center" itemprop="headline">
				<?php tcc_post_title( 40 ); ?>
			</h2><?php

			if ( tcc_option( 'exdate', 'content', 'show' ) === 'show' ) { ?>
				<h3 class="text-center">
					<?php fluid_post_date(); ?>
				</h3><?php
			} ?>

		</div>

		<div class="article" itemprop="description"><?php
			the_excerpt(); ?>
		</div>

	</article>

</div>

<?php clearfix()->apply();
