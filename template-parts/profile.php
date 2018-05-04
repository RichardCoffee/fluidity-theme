<?php
/*
 *  File Name:  template-parts/profile.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php microdata()->Person(); ?>>
	<h1>
		<?php printf( esc_html_x( 'All posts by %s', "post author's name", 'tcc-fluid' ), get_the_author() ); ?>
	</h1><?php
	if ( $descrip = get_the_author_meta( 'description' ) ) { ?>
		<div class="author-description">
			<?php e_esc_html( $descrip ); ?>
		</div><?php
	} ?>
</div>
