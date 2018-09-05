<?php
/**
 * @package Fluidity
 * @subpackage Search
 * @since 20150520
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/searchform.php
 */
defined( 'ABSPATH' ) || exit;

include_once( FLUIDITY_HOME . 'includes/search.php' );

$unique_id = uniqid();

$theme_search = array(
	'form' => array(
		'id'     => 'searchform-' . $unique_id,
		'class'  => 'searchform',
		'method' => 'get',
		'action' => home_url( '/' ),
		'role'   => 'search',
	),
	'label' => array(
		'class' => 'screen-reader-text',
		'for'   => 's-' . $unique_id,
	),
	'search' => array(
		'type'  => 'text',
		'id'    => 's-' . $unique_id,
		'class' => 'form-control searchform-input',
		'value' => '',
		'name'  => 's',
		'placeholder' => __( 'Search', 'tcc-fluid' ),
		'spellcheck'  => 'false',
		'autocorrect' => 'off'
	),
	'button' => array(
		'type'  => 'submit',
		'class' => 'btn btn-fluidity btn-search'
	),
	'text' => array(
		'label'  => __( 'Search field', 'tcc-fluid' ),
		'button' => __( 'Submit search terms', 'tcc-fluid' ),
	),
);

fluid_show_search_form( $theme_search );

