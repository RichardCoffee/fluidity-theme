<?php

class TCC_Theme_Support {


	protected $editor_style  = 'editor-style.css';
	protected $filter_prefix = 'fluidity';


	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_theme_support' ) );
		add_action( 'init', array( $this, 'post_type_support' ) );
	}

	public function load_theme_support() {
		$this->automatic_feed_links();
		$this->custom_background();
#		$this->custom_header();
		$this->custom_logo();
		$this->editor_style();
		$this->html5();
		$this->post_formats();
		$this->post_thumbnails();
		$this->title_tag();
	}

	protected function automatic_feed_links() {
		add_theme_support( 'automatic-feed-links' );
	}

	protected function custom_background() {
		$background = array(
#			'default-image'          =>  get_theme_file_uri( 'screenshot.jpg' ),
#			'default-preset'         => 'default',
			'default-position-x'     => 'center',
#			'default-position-y'     => 'top',
			'default-size'           => 'cover',
			'default-repeat'         => 'no-repeat',
			'default-attachment'     => 'fixed',
#			'default-color'          => '',
#			'wp-head-callback'       => '_custom_background_cb',
#			'admin-head-callback'    => '',
#			'admin-preview-callback' => '',
		);
		add_theme_support( 'custom-background', $background );
	}

	protected function custom_header() {
		$header = array(
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
		add_theme_support( 'custom-header', $header );
	}

	protected function custom_logo() {
		$logo = array(
#			'height'      => 100,
#			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
#			'header-text' => array( 'site-title', 'site-description' ),
		);
		add_theme_support( 'custom-logo', $logo );
	}

	protected function editor_style() {
		if ( ! empty( $this->editor_file ) ) {
			add_theme_support('editor_style');
			$file = get_theme_file_path( $this->editor_style );
			if ( is_readable( $file ) ) {
				add_editor_style( $this->editor_style );
			}
		}
	}

	protected function html5() {
		$html5 = array(
			'caption',
			'comment-form',
			'comment-list',
			'gallery',
			'search-form',
		);
		add_theme_support( 'html5', $html5 );
	}

	protected function post_formats() {
		$formats = array(
#			'aside',
#			'audio',
#			'chat',
#			'gallery',
#			'image',
			'link',
#			'quote',
#			'status',
#			'video',
		);
		$formats = apply_filters( $this->filter_prefix . '_post_formats', $formats );
		if ( $formats ) {
			add_theme_support( 'post-formats', $formats );
			add_filter( $this->filter_prefix . '_theme_post_type_support', function( $supports ) {
				$supports[] = 'post-formats';
				return $supports;
			});
		}
	}

	public function post_type_support() {
		$supports = array(
			'title',
			'editor',
			'author',
			'excerpt',
			'trackbacks',
			'custom-fields',
			'comments',
			'revisions',
			'page-attributes',
		);
		$supports = apply_filters( $this->filter_prefix . '_theme_post_type_support', $supports );
		if ( $supports ) {
			$pages = apply_filters( $this->filter_prefix . '_theme_post_type_support_pages', $supports );
			if ( $pages ) {
				add_post_type_support( 'page', $pages );
			}
			$posts = apply_filters( $this->filter_prefix . '_theme_post_type_support_posts', $supports );
			if ( $posts ) {
				add_post_type_support( 'post', $posts );
			}
		}
	}



	protected function post_thumbnails() {
		add_theme_support( 'post-thumbnails' );  # thumbnail (150px x 150px), medium (300px x 300px), large (640px x 640px), full (original size uploaded)
		add_filter( $this->filter_prefix . '_theme_post_type_support', function( $supports ) {
			$supports[] = 'thumbnail';
			return $supports;
		});
	}

	protected function title_tag() {
		add_theme_support('title-tag');
	}


}
