<?php

who_am_i();

$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
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
	'orderby'      => 'login',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
	'count_total'  => false,
	'fields'       => 'all_with_meta',
	'who'          => '',
);
$users = get_users( $args );

if ( $users ) {
	global $wp_roles; ?>
	<div class="article"><?php
		clearfix()->initialize( [ 'lg' => 4, 'md' => 4, 'sm' => 6, 'xs' => 12 ] );
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
#					if ( WP_DEBUG && is_user_logged_in() && current_user_can( 'update_core' ) ) {
#						print_r($user);
#					} ?>
				</article>
			</div><?php
			clearfix()->apply();
		} ?>
	</div><?php
}
