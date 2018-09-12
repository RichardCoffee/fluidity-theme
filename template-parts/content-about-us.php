<?php

who_am_i();

# * @link https://codex.wordpress.org/Function_Reference/get_users
$args = array(
//	'blog_id'      => $GLOBALS['blog_id'], // defaults to current blog
	'role'         => '',
	'role__in'     => array(),
	'role__not_in' => array( 'subscriber' ),
	'meta_key'     => '',
	'meta_value'   => '',
	'meta_compare' => '',
	'meta_query'   => array(),
	'date_query'   => array(),
	'include'      => array(),
	'exclude'      => array(),
	'orderby'      => 'registered',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
//	'count_total'  => false, // do not set - set to false by get_users() automatically
	'fields'       => 'all_with_meta',
	'who'          => '',
);
$users = get_users( $args );

if ( $users ) {
	global $wp_roles; ?>
	<div class="article"><?php
		$cols = apply_filters( 'fluid_about_us_cols', [ 'lg' => 4, 'md' => 4, 'sm' => 6, 'xs' => 12 ] );
		clearfix()->initialize( $cols );
		foreach( $users as $user ) { ?>
			<div class="<?php clearfix()->div_class(); ?>">
				<article class="enclosure">
					<h3 class="text-center"><?php
						printf(
							"%s - <span class='block'>%s</span>\n",
							translate_user_role( $wp_roles->roles[ $user->roles[0] ]['name'] ),
							$user->display_name
						); ?>
					</h3><?php
					if ( function_exists( 'is_bbpress' ) ) { ?>
						<div class="text-center"><?php
							printf(
								esc_html_x( 'Forum %s', 'user bbpress forum role', 'tcc-fluid' ),
								bbp_get_user_display_role( $user->ID )
							); ?>
						</div><?php
					} ?>
					<hr>
				</article>
			</div><?php
			clearfix()->apply();
		} ?>
	</div><?php
}
