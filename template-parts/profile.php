<?php
/*
 *  File Name:  template-parts/profile.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div <?php microdata()->Person(); ?>><?php

	fluid()->element(
		'h1',
		[ 'class' => 'text-center' ],
		sprintf( _x( 'Author %s', "post author's name", 'tcc-fluid' ), get_the_author() )
	);
	echo '<hr>';

	if ( $descrip = get_the_author_meta( 'description' ) ) {
		fluid()->element( 'div', [ 'class' => 'author-description' ], $descrip );
		echo '<hr>';
	}

	$skills = get_the_author_meta( 'skills' );
	if ( ! empty( $skills ) ) { ?>
		<div class="row"><?php
			fluid()->element( 'h1', [ 'class' => 'text-center' ], __( 'Skill Set', 'tcc-fluid' ) );
			foreach( $skills as $text => $icon ) { ?>
				<div class="col-3 col-md-2 col-lg-1 text-center"><?php
					fluid()->element( 'i', [ 'class' => $icon, 'style' => 'font-size: 50px;' ] );
					fluid()->element( 'h5', [ ], $text ); ?>
				</div><?php
			} ?>
		</div>
		<hr><?php
	}

	fluid()->element( 'h1', [ 'class' => 'text-center' ], __( 'Posts', 'tcc-fluid' ) ); ?>

</div>
