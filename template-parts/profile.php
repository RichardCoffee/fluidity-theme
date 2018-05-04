<?php
/*
 *  File Name:  template-parts/profile.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div <?php microdata()->Person(); ?>>
	<h1 class="text-center">
		<?php printf( esc_html_x( 'All posts by %s', "post author's name", 'tcc-fluid' ), get_the_author() ); ?>
	</h1><?php
	if ( $descrip = get_the_author_meta( 'description' ) ) { ?>
		<div class="author-description">
			<?php e_esc_html( $descrip ); ?>
		</div><?php
	} ?>
</div>
