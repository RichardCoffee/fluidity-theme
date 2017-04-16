<?php

define( 'FLUIDITY_HOME', '/home/richard/work/entechpc/fluidity' );

#require( '../includes/loader.php' );
require( FLUIDITY_HOME . '/classes/Theme/Page.php' );
require( FLUIDITY_HOME . '/classes/Trait/Singleton.php' );

$page = TCC_Theme_Page::get_instance('test-page');

var_dump( $page );
