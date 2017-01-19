<?php

/*
 *  this file specifically for things James is working on
 */

function james_header_logo_class( array $args ) {
	$remove = 'hidden-xs';
	if(($key = array_search($remove, $args)) !== false) {
		unset($args[$key]);
	}
	return $args;
}
add_filter('tcc_header_logo_class','james_header_logo_class');
