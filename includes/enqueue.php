<?php

if ( ! function_exists( 'tcc_enqueue' ) ) {
	function tcc_enqueue() {
		do_action( 'tcc_pre_enqueue' );
		#	Register scripts
		fluidity_register_fontawesome();
		fluidity_register_bootstrap();
		fluidity_register_color_scheme();
		fluidity_register_css_js();
		#	Stylesheets
		wp_enqueue_style( 'tcc-fawe' );   #  font-awesome needs to be loaded before bootstrap, due to css conflict (sr-only)
		if ( tcc_option( 'active', 'social' ) === 'yes' ) {
			wp_enqueue_style( 'fa-social' );
		}
		fluidity_enqueue_bootstrap();
		do_action( 'tcc_during_enqueue' );
		#Javascript
		if ( tcc_layout( 'menu' ) !== 'bootstrap' ) {
			wp_enqueue_script( '_s-navigation', get_theme_file_uri( 'js/navigation.js' ), array(), '20151215', true );
		}
		wp_enqueue_script( 'tcc-skiplink' );
		if ( ( tcc_layout( 'widget' ) !== 'perm' ) || is_404() ) {
			wp_enqueue_script( 'tcc-collapse' );
		}
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );  #  enable threaded comments
		}
		// experimental
		$hdr_state = tcc_layout( 'header' );
		if ( $hdr_state === 'fixed' ) {
			wp_enqueue_script( 'tcc-fixed' );
		} else if ( $hdr_state === 'reduce' ) {
			wp_enqueue_style( 'tcc-reduce-css' );
			wp_enqueue_script( 'tcc-reduce-js' );
		} else if ( $hdr_state === 'hide' ) {
#			add_action( 'wp_footer', 'fluid_footer_autohide', 99 );
		}
		do_action( 'tcc_after_enqueue' );
		do_action( 'tcc_enqueue' );  #  load child theme enqueues here
	}
	add_action( 'wp_enqueue_scripts', 'tcc_enqueue' );
}

if ( ! function_exists( 'fluidity_register_css_js' ) ) {
	function fluidity_register_css_js() {
		$style = ( WP_DEBUG ) ? 'style.css' : 'css/style.min.css';
		wp_register_style('fa-social',       get_theme_file_uri('css/fa-social-hover.css'), array('tcc-fawe'), FLUIDITY_VERSION);
		wp_register_style('fluidity',        get_theme_file_uri( $style ),                  null,              FLUIDITY_VERSION);
		wp_register_style('tcc-gallery-css', get_theme_file_uri('css/gallery.css'),         null,              FLUIDITY_VERSION);
		wp_register_style('tcc-reduce-css',  get_theme_file_uri('css/header-reduce.css'),   null,              FLUIDITY_VERSION);
		wp_register_script('tcc-sprintf',    get_theme_file_uri('js/sprintf.js'),       null,                          FLUIDITY_VERSION, true);
		wp_register_script('tcc-library',    get_theme_file_uri('js/library.js'),       array('jquery','tcc-sprintf'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-collapse',   get_theme_file_uri('js/collapse.js'),      array('jquery','tcc-library'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-gallery-js', get_theme_file_uri('js/gallery.js'),       array('jquery','tcc-library'), FLUIDITY_VERSION, true);
		wp_register_script('tcc-skiplink',   get_theme_file_uri('js/skip-link-focus-fix.js'), array('jquery'),         FLUIDITY_VERSION, true);
		wp_register_script('tcc-fixed',      get_theme_file_uri('js/header-fixed.js'),  array('jquery'),               FLUIDITY_VERSION, true);
		wp_register_script('tcc-reduce-js',  get_theme_file_uri('js/header-reduce.js'), array('jquery'),               FLUIDITY_VERSION, true);
	}
}

if ( ! function_exists( 'fluidity_admin_enqueue' ) ) {
	function fluidity_admin_enqueue() {
		fluidity_register_fontawesome();
		fluidity_register_css_js();
		wp_enqueue_style( 'tcc-fawe' );
		do_action( 'fluidity_admin_enqueue' );
	}
	add_action( 'admin_enqueue_scripts', 'fluidity_admin_enqueue' );
}

if ( ! function_exists( 'fluidity_register_fontawesome' ) ) {
	function fluidity_register_fontawesome() {
		wp_register_style( 'tcc-fawe', get_theme_file_uri( 'vendor/css/font-awesome.min.css' ), false, '4.7.0' );
	}
}

if ( ! function_exists( 'fluidity_register_color_scheme' ) ) {
	function fluidity_register_color_scheme() {
		if ( $color = fluid_color_scheme() ) {
			wp_register_style( 'fluid-color',  get_theme_file_uri( "css/colors/$color.css" ), false, FLUIDITY_VERSION );
		}
	}
}

if ( ! function_exists( 'fluidity_enqueue_styles' ) ) {
	function fluidity_enqueue_styles() {
		wp_enqueue_style( 'fluidity' );
		wp_enqueue_style( 'fluid-color' );
	}
	add_action( 'tcc_during_enqueue', 'fluidity_enqueue_styles', 1 );
}
