<?php

add_theme_support('automatic-feed-links');
add_theme_support('html5',array('comment-list','comment-form','search-form','gallery','caption'));
add_theme_support('post-thumbnails');  # thumbnail (150px x 150px), medium (300px x 300px), large (640px x 640px), full (original size uploaded)
add_theme_support('post-thumbnails');
#add_theme_support('title-tag');



// Code for custom background support
$defaults = array(
  'default-color'          => 'ffffff',
  'default-image'          => '',
  'default-repeat'         => '',
  'default-position-x'     => '',
#  'wp-head-callback'       => '',
#  'admin-head-callback'    => '',
#  'admin-preview-callback' => '',
);
add_theme_support( 'custom-background', $defaults );

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
add_theme_support('custom-header',$defaults);

// Jetpack site logo
#$args = array( 'header-text' => array( 'site-title',
#                                       'site-description' ),
#               'size' => 'medium');
add_theme_support('site-logo'); #,$args);

$defaults = array(
#   'height'      => 100,
#   'width'       => 400,
   'flex-height' => true,
   'flex-width'  => true,
#   'header-text' => array( 'site-title', 'site-description' ),
);
add_theme_support('custom-logo',$defaults);

add_editor_style();
if (!function_exists('tcc_editor_styles')) {
  // FIXME:  check for file in child
  if (file_exists(get_template_directory().'tcc-editor-style.css')) {
    function tcc_editor_styles() {
      add_editor_style('tcc-editor-style.css');
    }
    add_action('admin_init','tcc_editor_styles' ); //*/
  }
}
