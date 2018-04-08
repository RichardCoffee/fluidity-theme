<?php

/**
 * Search
 *
 * @package bbPress
 * @subpackage Theme
 */

include_once( FLUIDITY_HOME . 'includes/search.php' );

$bbpress_search = array(
	'form' => array(
		'id'     => 'bbp-search-form',
		'method' => 'get',
		'action' =>  bbp_search_url(),
		'role'   => 'search',
	),
	'label' => array(
		'class' => 'screen-reader-text hidden',
		'for'   => 'bbp_search',
	),
	'hidden' => array(
		'type'  => 'hidden',
		'name'  => 'action',
		'value' => 'bbp-search-request',
	),
	'search' => array(
		'type'     => 'text',
		'id'       => 'bbp_search',
		'name'     => 'bbp_search',
		'tabindex' =>  bbp_tab_index(),
		'value'    =>  bbp_get_search_terms(),
	),
	'button' => array(
		'type'     => 'submit',
		'id'       => 'bbp_search_submit',
		'class'    => 'button',
		'tabindex' =>  bbp_tab_index(),
	),
	'text' => array(
		'label'  => __( 'Search for:', 'bbpress' ),
		'button' => __( 'Search', 'bbpress' ),
	),
);

fluid_show_search_form( $bbpress_search );

