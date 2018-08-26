<?php

/* James - just ignore this first function, the good stuff is down below
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

/* // These are just examples - replace as needed.
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

/***   General filters   ***/

#	add_filter( 'fluid_color_scheme',      function( $arg ) { return 'none'; } );
#	add_filter( 'fluid_available_color_schemes', function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_copyright_name',    function( $arg ) { return microdata()->get_bloginfo( 'name' ); } );
#	add_filter( 'fluid_logo_div_css',      function( $arg ) { return 'pointer'; } );
#	add_filter( 'fluid_header_logo_size',  function( $arg ) { return 'full'; } );
#	add_filter( 'fluid_header_logo_class', function( $args, $size = 'full' ) { return [ 'custom-logo', 'centered', 'img-responsive', "attachment-$size", 'hidden-xs' ]; }, 10, 2 );
#	add_filter( 'fluid_google_fonts',      function( $args ) { return $args; } ); // see fluidity/classes/Options/Typography.php for array structure

/*** Login form filters ***/

#	add_filter( 'fluid_login_password', function( $arg ) { return __( 'Password', 'tcc-fluid' ); } );
#	add_filter( 'fluid_login_username', function( $arg ) { return __( 'Username or Email Address', 'tcc-fluid' ); } );
#	add_filter( 'fluid_login_text',     function( $arg ) { return __( 'Sign In', 'tcc-fluid' ); } );
#	add_filter( 'fluid_lostpw_text',    function( $arg ) { return __( 'Lost Password', 'tcc-fluid' ); } );
#	add_filter( 'fluid_logout_text',    function( $arg ) { return __( 'Sign Out', 'tcc-fluid' ); } );

/*** Content filters ***/

#	add_filter( 'fluid_post_date_sprintf',   function( $arg ) { return esc_html_x( 'Posted on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' ); } );
#	add_filter( 'fluid_read_more_css',       function( $arg ) { return 'read-more-link'; } );
#	add_filter( 'fluid_read_more_brackets',  function( $arg ) { return true; } );
#	add_filter( 'fluid_read_more_text',      function( $arg ) { return __( 'Read More', 'tcc-fluid' ); } );
#	add_filter( 'fluid_navigation_taxonomy', function( $arg ) { return 'category'; } );

/*** Comment filters ***/

// See classes/Form/Comments.php for all available filters - too many to list here

/*** Admin filters ***/

#	add_filter( 'wp_admin_bar_my_account_greeting',    function( $current, $original = 'Howdy' ) { return $current; }, 10, 2 );
#	add_filter( 'wp_admin_bar_my_account_profile_url', function( $current, WP_User $user ) { return $current; }, 10, 2 );

/*** Theme Support filters ***/

// See fluidity/classes/Theme/Support.php for the list of options for each filter
#	add_filter( 'fluid_load_theme_support',        function( $args ) { return $args; } );
#	add_filter( 'fluid_editor_style',              function( $arg )  { return 'css/editor-style.css'; } );
#	add_filter( 'fluid_support_content_width',     function( $arg )  { return 1600; } );
#	add_filter( 'fluid_support_custom_background', function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_support_custom_header',     function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_support_custom_logo',       function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_support_html5',             function( $args ) { return $args; } ); // return null for no support
/*	add_filter( 'fluid_support_post_formats',      function( $formats ) {
		#  WordPress: array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' );
		// fluidity supports these 3 by default so they are really here only as an example
		$support = array( 'image', 'link', 'quote' );
		return array_merge( $formats, $support );
	} ); //*/
#	add_filter( 'fluid_support_post_type',        function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_support_post_type_posts',  function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_support_post_type_pages',  function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_support_starter_content',  function( $args ) { return $args; } ); // return null for no support
#	add_filter( 'fluid_theme_scandir_exclusions', function( $args ) { return $args; } ); // return null for no support

/*** Bootstrap modal filters ***/

// none of these filters are currently active, just included here as a reference.
#### apply_filters( "{$this->prefix}_modal_main_attrs", $attrs );
#### apply_filters( "{$this->prefix}_modal_dialog_attrs", $attrs );
#### apply_filters( "{$this->prefix}_modal_header_attrs", $attrs );
#### apply_filters( "{$this->prefix}_modal_header_button_close_attrs", $attrs );
#### apply_filters( "{$this->prefix}_modal_body_attrs", $attrs );
#### apply_filters( "{$this->prefix}_modal_footer_attrs", $attrs );

/*** bbPress ***/

#	add_filter( 'fluid_bbp_topic_subscribed_default', function( $arg ) { return true; } );
