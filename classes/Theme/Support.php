<?php
/**
 * classes/Theme/Support.php
 *
 * @link https://github.com/RichardCoffee/fluidity-theme/wiki/Theme-Support
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/Support.php
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/support.php
 * @author Richard Coffee <richard.coffee@gmail.com>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

/**
 * Sets up the wordpress theme support
 *
 * @since 20170508
 * @link https://github.com/RichardCoffee/fluidity-theme/wiki/Theme-Support
 */
class TCC_Theme_Support {

	/**
	 * set maximum content width
	 *
	 * @var int
	 */
	public $content_width =  1600;
	/**
	 * css file name for TinyMCE
	 *
	 * @var string
	 */
	public $editor_style  = 'css/editor-style.css';
	/**
	 * used as prefix for apply_filter calls
	 *
	 * @var string
	 */
	public $filter_prefix = 'fluid';

	/**
	 * initialize tasks for theme support
	 *
	 */
	public function __construct() {
		add_action( 'after_setup_theme',        [ $this, 'load_theme_support'       ], 100 );
		add_action( 'init',                     [ $this, 'post_type_support'        ] );
		add_filter( 'theme_scandir_exclusions', [ $this, 'theme_scandir_exclusions' ] );
		$this->editor_style = apply_filters( $this->filter_prefix . '_editor_style', $this->editor_style );
	}

	/**
	 * run all methods needed for theme feature support
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Theme_Features
	 */
	public function load_theme_support() {
		$support = array(
			'automatic-feed-links',
			'content-width',
			'custom-background',
			'custom-header',
			'custom-logo',
			'customize-selective-refresh-widgets',
			'editor-style',
			'html5',
			'post-formats',
			'post-thumbnails',
			'starter-content',
			'title-tag',
		);
		$support = apply_filters( $this->filter_prefix . '_load_theme_support', $support );
		if ( ! empty( $support ) ) {
			$functions = array_map(
				function( $item ) {
					return str_replace( '-', '_', $item );
				},
				$support
			);
			foreach( $functions as $run_this ) {
				if ( method_exists( $this, $run_this ) ) {
					$this->$run_this();
				}
			}
		}
	}

	/**
	 * adds RSS feed links to HTML <head>
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Automatic_Feed_Links
	 */
	protected function automatic_feed_links() {
		add_theme_support( 'automatic-feed-links' );
	}

	/**
	 * set content_width global
	 *
	 * @since 20180401
	 * @link https://codex.wordpress.org/Content_Width
	 */
	protected function content_width() {
		global $content_width;
		$content_width = apply_filters( $this->filter_prefix . '_support_content_width', $this->content_width );
	}

	/**
	 * set defaults for custom background feature
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Custom_Backgrounds
	 */
	protected function custom_background() {
		$background = array(
			'default-image'          => '',
			'default-preset'         => 'default',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-repeat'         => 'repeat',
			'default-attachment'     => 'scroll',
			'default-color'          => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		);
		$background = apply_filters( $this->filter_prefix . '_support_custom_background', $background );
		if ( (bool) $background ) {
			add_theme_support( 'custom-background', $background );
		}
	}

	/**
	 *  set defaults for custom header
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Custom_Headers
	 * @link https://codex.wordpress.org/Function_Reference/register_default_headers
	 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
	 */
	protected function custom_header() {
		$header = array(
			'default-image'          => '',
			'random-default'         => false,
			'width'                  => 0,
			'height'                 => 0,
			'flex-height'            => true,
			'flex-width'             => true,
			'default-text-color'     => '',
			'header-text'            => true, // array( 'site-title', 'site-description' ),
			'uploads'                => true,
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
			'video'                  => false,
			'video-active-callback'  => 'is_front_page',
		);
		$header = apply_filters( $this->filter_prefix . '_support_custom_header', $header );
		if ( (bool) $header ) {
			add_theme_support( 'custom-header', $header );
		}
	}

	/**
	 * set defaults for custom logo
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Theme_Logo
	 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
	 */
	protected function custom_logo() {
		$logo = array(
			'height'      => null,
			'width'       => null,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => '', // array( 'site-title', 'site-description' ),
		);
		$logo = apply_filters( $this->filter_prefix . '_support_custom_logo', $logo );
		if ( (bool) $logo ) {
			add_theme_support( 'custom-logo', $logo );
		}
	}

	/**
	 *  Adds support for partial refreshes for widgets
	 *
	 * @since 20180324
	 * @link https://make.wordpress.org/core/2016/03/22/implementing-selective-refresh-support-for-widgets/
	 */
	protected function customize_selective_refresh_widgets() {
		add_theme_support( 'customize-selective-refresh-widgets' );
	}

	/**
	 * load editor stylesheet
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Editor_Style
	 */
	protected function editor_style() {
		if ( $this->editor_style ) {
			$file = get_theme_file_path( $this->editor_style );
			if ( is_readable( $file ) ) {
				add_editor_style( $this->editor_style );
			}
		}
	}

	/**
	 * sets where html5 markup will be used
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Theme_Markup
	 */
	protected function html5() {
		$html5 = array(
			'caption',
			'comment-form',
			'comment-list',
			'gallery',
			'search-form',
		);
		$html5 = apply_filters( $this->filter_prefix . '_support_html5', $html5 );
		if ( (bool) $html5 ) {
			add_theme_support( 'html5', $html5 );
		}
	}

	/**
	 * set the usable post formats
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Post_Formats
	 */
	protected function post_formats() {
		$formats = array(
			'aside',
			'audio',
			'chat',
			'gallery',
			'image',
			'link',
			'quote',
			'status',
			'video',
		);
		$formats = apply_filters( $this->filter_prefix . '_support_post_formats', $formats );
		if ( (bool) $formats ) {
			add_theme_support( 'post-formats', $formats );
			add_filter( $this->filter_prefix . '_support_post_type', function( $supports ) {
				$supports[] = 'post-formats';
				return $supports;
			});
		}
	}

	/**
	 * set post type support
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Function_Reference/add_post_type_support
	 */
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
		);
		$supports = apply_filters( $this->filter_prefix . '_support_post_type', $supports );
		if ( (bool) $supports ) {
			# post type: post
			$posts = apply_filters( $this->filter_prefix . '_support_post_type_posts', $supports );
			if ( (bool) $posts ) {
				add_post_type_support( 'post', $posts );
			}
			# post type: page
			if ( is_post_type_hierarchical( 'page' ) ) {
				$supports[] = 'page-attributes';
			}
			$pages = apply_filters( $this->filter_prefix . '_support_post_type_pages', $supports );
			if ( (bool) $pages ) {
				add_post_type_support( 'page', $pages );
			}
		}
	}

	/**
	 * adds basic post thumbnail support
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Post_Thumbnails
	 */
	protected function post_thumbnails() {
		add_theme_support( 'post-thumbnails' );  # thumbnail (150px x 150px), medium (300px x 300px), large (640px x 640px), full (original size uploaded)
		do_action(  $this->filter_prefix . '_support_post_thumbnails' );
		add_filter( $this->filter_prefix . '_support_post_type', function( $supports ) {
			$supports[] = 'thumbnail';
			return $supports;
		});
	}

	/**
	 * adds starter content support
	 *
	 * @since 20180722
	 * @link https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
	 * @link https://developer.wordpress.org/reference/functions/get_theme_starter_content/
	 */
	protected function starter_content() {
		$content = apply_filters( $this->filter_prefix . '_support_starter_content', array() );
		if ( ! empty( $content ) ) {
			add_theme_support( 'starter-content', $content );
		}
	}

	/**
	 * tell wp which directories not to scan
	 *
	 * @since 20180324
	 * @link https://developer.wordpress.org/reference/hooks/theme_scandir_exclusions/
	 */
	public function theme_scandir_exclusions( $exclusions ) {
		$standard = array(
			'docs',
			'fonts',
			'icons',
			'languages',
			'scss',
		);
		# add these exclusions when WP is checking for page templates
		$not_templates = array();
		if ( was_called_by( 'get_post_templates' ) ) {
			$not_templates = array(
				'classes',
				'css',
				'includes',
				'js',
				'template-parts',
			);
		}
		return apply_filters( $this->filter_prefix . '_theme_scandir_exclusions', array_merge( $exclusions, $standard, $not_templates ), $exclusions );
	}

	/**
	 * add document title tag to HTML <head>
	 *
	 * @since 20170508
	 * @link https://codex.wordpress.org/Title_Tag
	 */
	protected function title_tag() {
		add_theme_support( 'title-tag' );
	}


}

/**
 *  used by theme_scandir_exclusions method
 *
 * @since 20180324
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/Logging.php
 * @param string $func
 * @return boolean
 */
if ( ! function_exists( 'was_called_by' ) ) {
	function was_called_by( $func ) {
		$call_trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		foreach( $call_trace as $current ) {
			if ( ( ! empty( $current['function'] ) ) && ( $current['function'] === $func ) ) {
				return true;
			}
		}
		return false;
	}
}
