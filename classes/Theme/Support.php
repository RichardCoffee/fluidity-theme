<?php
/**
 * classes/Theme/Support.php
 *
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/Support.php
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/support.php
 * @author Richard Coffee <richard.coffee@gmail.com>
 */

/**
 * Sets up the wordpress theme support
 *
 * @link https://github.com/RichardCoffee/fluidity-theme/wiki/Theme-Support
 */
class TCC_Theme_Support {

	/**
	 * set maximum content width
	 *
	 * @var numeric
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
		add_action( 'after_setup_theme',        array( $this, 'load_theme_support'       ), 100 );
		add_action( 'init',                     array( $this, 'post_type_support'        ) );
		add_filter( 'theme_scandir_exclusions', array( $this, 'theme_scandir_exclusions' ) );
	}

	/**
	 * run all methods needed for theme feature support
	 *
	 * @link https://codex.wordpress.org/Theme_Features
	 */
	public function load_theme_support() {
		$funcs = array(
			'automatic_feed_links',
			'content_width',
			'custom_background',
			'custom_header',
			'custom_logo',
			'customize_selective_refresh_widgets',
			'editor_style',
			'html5',
			'post_formats',
			'post_thumbnails',
			'title_tag',
		);
		$funcs = apply_filters( $this->filter_prefix . '_load_theme_support', $funcs );
		if ( ! empty( $funcs ) ) {
			foreach( $funcs as $func ) {
				if ( method_exists( $this, $func ) ) {
					$this->$func();
				}
			}
		}
	}

	/**
	 * adds RSS feed links to HTML <head>
	 *
	 * @link https://codex.wordpress.org/Automatic_Feed_Links
	 */
	protected function automatic_feed_links() {
		add_theme_support( 'automatic-feed-links' );
	}

	/**
	 * set content_width global
	 *
	 * @link https://codex.wordpress.org/Content_Width
	 */
	protected function content_width() {
		global $content_width;
		$content_width = apply_filters( $this->filter_prefix . '_support_content_width', $this->content_width );
	}

	/**
	 * set defaults for custom background feature
	 *
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
		if ( $background ) {
			add_theme_support( 'custom-background', $background );
		}
	}

	/**
	 * set defaults for custom header
	 *
	 * @link https://codex.wordpress.org/Custom_Headers
	 * @link https://codex.wordpress.org/Function_Reference/register_default_headers
	 */
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
			'video'                  => false,
			'video-active-callback'  => 'is_front_page',
		);
		$header = apply_filters( $this->filter_prefix . '_support_custom_header', $header );
		if ( $header ) {
			add_theme_support( 'custom-header', $header );
		}
	}

	/**
	 * set defaults for custom logo
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
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
		if ( $logo )
		add_theme_support( 'custom-logo', $logo );
	}

	protected function customize_selective_refresh_widgets() {
		add_theme_support( 'customize-selective-refresh-widgets' );
	}

	/**
	 * load editor stylesheet
	 *
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
		if ( $html5 ) {
			add_theme_support( 'html5', $html5 );
		}
	}

	/**
	 * set the usable post formats
	 *
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
		if ( $formats ) {
			add_theme_support( 'post-formats', $formats );
			add_filter( $this->filter_prefix . '_theme_post_type_support', function( $supports ) {
				$supports[] = 'post-formats';
				return $supports;
			});
		}
	}

	/**
	 * set post type support
	 *
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
		$supports = apply_filters( $this->filter_prefix . '_theme_post_type_support', $supports );
		if ( $supports ) {
			# post type: post
			$posts = apply_filters( $this->filter_prefix . '_theme_post_type_support_posts', $supports );
			if ( $posts ) {
				add_post_type_support( 'post', $posts );
			}
			# post type: page
			if ( is_post_type_hierarchical( 'page' ) ) {
				$supports[] = 'page-attributes';
			}
			$pages = apply_filters( $this->filter_prefix . '_theme_post_type_support_pages', $supports );
			if ( $pages ) {
				add_post_type_support( 'page', $pages );
			}
		}
	}

	/**
	 * adds basic post thumbnail support
	 *
	 * @link https://codex.wordpress.org/Post_Thumbnails
	 */
	protected function post_thumbnails() {
		add_theme_support( 'post-thumbnails' );  # thumbnail (150px x 150px), medium (300px x 300px), large (640px x 640px), full (original size uploaded)
		do_action(  $this->filter_prefix . '_support_post_thumbnails' );
		add_filter( $this->filter_prefix . '_theme_post_type_support', function( $supports ) {
			$supports[] = 'thumbnail';
			return $supports;
		});
	}

	/**
	 * tell wp which directories not to scan
	 *
	 * @link https://developer.wordpress.org/reference/hooks/theme_scandir_exclusions/
	 */
	public function theme_scandir_exclusions( $exclusions ) {
		$exclusions = array_merge( $exclusions, array(
			'docs',
			'fonts',
			'icons',
			'languages',
			'scss',
		) );
		# add these exclusions when WP is checking for page templates
		if ( was_called_by( 'get_post_templates' ) ) {
			$exclusions = array_merge( $exclusions, array(
				'classes',
				'css',
				'includes',
				'js',
				'template-parts',
			) );
		}
		return $exclusions;
	}

	/**
	 * add document title tag to HTML <head>
	 *
	 * @link https://codex.wordpress.org/Title_Tag
	 */
	protected function title_tag() {
		add_theme_support('title-tag');
	}


}

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
