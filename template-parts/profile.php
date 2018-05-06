<?php
/*
 *  File Name:  template-parts/profile.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div <?php microdata()->Person(); ?>>

	<h1 class="text-center">
		<?php printf( esc_html_x( 'Author %s', "post author's name", 'tcc-fluid' ), get_the_author() ); ?>
	</h1><?php

	if ( $descrip = get_the_author_meta( 'description' ) ) { ?>
		<div class="author-description">
			<?php e_esc_html( $descrip ); ?>
		</div><?php
	}

	$skills = get_the_author_meta( 'skills' );
	if ( ! empty( $skills ) ) {
		fluid()->element( 'h1', [ 'class' => 'text-center' ], __( 'Skills', 'tcc-fluid' ) );
		foreach( $skills as $text => $icon ) { ?>
			<div class="col-md-1"><?php
				fluid()->element( 'i', [ 'class' => $icon ] );
				fluid()->element( 'h3', [ 'class' => 'text-center' ], $text ); ?>
			</div><?php
		}
	} ?>

</div>
