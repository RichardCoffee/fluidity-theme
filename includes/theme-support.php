<?php

add_theme_support('automatic-feed-links');
add_theme_support('html5',array('comment-list','comment-form','search-form','gallery','caption'));
add_theme_support( 'post-formats',
	apply_filters( 'fluidity_post_formats',
#		array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' )
		array( 'link' )
	)
);
add_theme_support('post-thumbnails');  # thumbnail (150px x 150px), medium (300px x 300px), large (640px x 640px), full (original size uploaded)
add_theme_support('title-tag');

// Enable custom background support
$defaults = array(
#	'default-image'          => get_template_directory_uri().'/screenshot.jpg',
#	'default-preset'         => 'default',
	'default-position-x'     => 'center',
#	'default-position-y'     => 'top',
	'default-size'           => 'cover',
	'default-repeat'         => 'no-repeat',
	'default-attachment'     => 'fixed',
#	'default-color'          => '',
#	'wp-head-callback'       => '_custom_background_cb',
#	'admin-head-callback'    => '',
#	'admin-preview-callback' => '',
);

add_theme_support( 'custom-background', $defaults ); //*/
/*
// Enable custom header support
$defaults = array(
  'default-image'          => '',
  'random-default'         => false,
  'width'                  => 0,
  'height'                 => 0,
  'flex-height'            => false,
  'flex-width'             => false,
  'default-text-color'     => '',
  'header-text'            => true,
  'uploads'                => true,
  'wp-head-callback'       => '',
  'admin-head-callback'    => '',
  'admin-preview-callback' => '',
);
add_theme_support('custom-header',$defaults); //*/

$defaults = array(
#   'height'      => 100,
#   'width'       => 400,
   'flex-height' => true,
   'flex-width'  => true,
#   'header-text' => array( 'site-title', 'site-description' ),
);
add_theme_support('custom-logo',$defaults); //*/

// Jetpack site logo
#$args = array( 'header-text' => array( 'site-title',
#                                       'site-description' ),
#               'size' => 'medium');
#add_theme_support('site-logo'); #,$args);
/*
add_editor_style();

if (!function_exists('tcc_editor_styles')) {
  if (file_exists(get_stylesheet_directory().'tcc-editor-style.css')) {
    function tcc_editor_styles() {
      add_editor_style('tcc-editor-style.css');
    }
    add_action('admin_init','tcc_editor_styles' );
  }
} //*/

if (!function_exists('tcc_post_revisions')) {
	function tcc_post_revisions() {
		add_post_type_support( 'post', 'revisions' );
	}
	add_action( 'admin_init', 'tcc_post_revisions' );
}
