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

if ( $users ) { ?>
	<div class="row"><?php
		foreach( $users as $user ) { ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><?php
				print_r($user); ?>
			</div><?php
		} ?>
	</div><?php
}
