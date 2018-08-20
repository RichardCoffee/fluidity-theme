<?php
/*
function fluid_child_generate_filters() {
	$templates = array(
		'fluid_author_template_dir'  => 'template-parts',
		'fluid_author_template_root' => 'profile',
		'fluid_header_template_dir'  => 'template-parts',
		'fluid_header_template_root' => 'header',
	);
	foreach( $templates as $filter => $values ) {
		add_filter( $filter, function( $assigned, $slug ) use ( $values ) {
			return $values;
		});
	}
} //*/

/***   Sidebars   ***/

add_filter( 'fluid_register_sidebars', function( $sidebars ) {
	$sidebars = array(
		array(
			'name'          => __('Home Page Top Row Sidebar','tcc-theme'),
			'id'            => 'home_page_top_row',
			'before_widget' => '<div class="col-md-4 hidden-sm hidden-xs"><div class="column-widget">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div><h3>',
			'after_title'   => '</h3></div>',
		),
		array(
			'name'          => __('Home Page Sidebar','tcc-theme'),
			'id'            => 'home_page_sidebar',
			'before_widget' => '<div class="panel panel-primary">',
			'before_title'  => '<div class="panel-heading"><h3 class="panel-title">',
			'after_title'   => '</h3></div><div class="panel-body">',
			'after_widget'  => '</div></div>'
		),
		array(
			'name'          => __('Footer Row Sidebar','tcc-theme'),
			'id'            => 'footer_row_sidebar',
			'before_widget' => '<div class="col-md-4 hidden-sm hidden-xs">',
			'after_widget'  => '</div>',
			'before_title'  => '<div><h3>',
			'after_title'   => '</h3></div>'
		),
	);
	return $sidebars;
} );

/***   General Filters   ***/

	add_filter( 'fluid_color_scheme',   function( $arg ) { return 'none'; } );
#	add_filter( 'fluid_login_password', function( $arg ) { return __( 'Password', 'tcc-fluid' ); } );
#	add_filter( 'fluid_login_username', function( $arg ) { return __( 'Username or Email Address', 'tcc-fluid' ); } );
#	add_filter( 'fluid_login_text',     function( $arg ) { return __( 'Sign In', 'tcc-fluid' ); } );
#	add_filter( 'fluid_lostpw_text',    function( $arg ) { return __( 'Lost Password', 'tcc-fluid' ); } );
#	add_filter( 'fluid_logout_text',    function( $arg ) { return __( 'Sign Out', 'tcc-fluid' ); } );
