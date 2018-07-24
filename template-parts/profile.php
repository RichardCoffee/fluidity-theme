<?php
/*
 *  File Name:  template-parts/profile.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div <?php microdata()->Person(); ?>>

	<h1 class="text-center">
		<?php printf( esc_html_x( 'Author %s', "post author's name", 'tcc-fluid' ), get_the_author() ); ?>
	</h1>
	<hr><?php

	if ( $descrip = get_the_author_meta( 'description' ) ) { ?>
		<div class="author-description">
			<?php e_esc_html( $descrip ); ?>
		</div>
		<hr><?php
	}

	$skills = get_the_author_meta( 'skills' );
	if ( ! empty( $skills ) ) { ?>
		<div class="row"><?php
			fluid()->element( 'h1', [ 'class' => 'text-center' ], __( 'Skill Set', 'tcc-fluid' ) );
			foreach( $skills as $text => $icon ) { ?>
				<div class="col-md-1 text-center"><?php
					fluid()->element( 'i', [ 'class' => $icon, 'style' => 'font-size: 50px;' ] );
					fluid()->element( 'h5', [ ], $text ); ?>
				</div><?php
			} ?>
		</div>
		<hr><?php
	}

	fluid()->element( 'h1', [ 'class' => 'text-center' ], __( 'Posts', 'tcc-fluid' ) ); ?>

</div>
