<?php

the_post_thumbnail();                  // without parameter -> 'post-thumbnail'
the_post_thumbnail('thumbnail');       // Thumbnail (default 150px x 150px max)
the_post_thumbnail('medium');          // Medium resolution (default 300px x 300px max)
the_post_thumbnail('large');           // Large resolution (default 640px x 640px max)
the_post_thumbnail('full');            // Full resolution (original size uploaded)
#the_post_thumbnail(array(100, 100));   // Other resolutions

// Enable post thumbnails
add_theme_support('post-thumbnails');
#set_post_thumbnail_size(245, 165);

// Enable post and comments RSS feed links to head
add_theme_support('automatic-feed-links');

// Code for custom background support
add_theme_support('custom-background');

// supports title tag
add_theme_support('title-tag');

// enable custom header support
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

add_editor_style();
