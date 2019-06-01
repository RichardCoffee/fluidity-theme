<?php
/**
 *  Gutenburg Editor Blocks
 *
 * @package Fluidity
 * @subpackage Gutenburg
 * @since 20190529
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2019, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Block/Block.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Abstract class that provides generic editor block functions.
 *
 * @since 20190529
 */
abstract class TCC_Block_Block {

	/**
	 *  Indicates javascript is not in separate file, and is to be written as inline.  Testing only, =not= recommended for production proposes.
	 *
	 * @since 20190601
	 * @var bool
	 */
	protected $inline = false;
	/**
	 *  Internal path and name of javascript file
	 *
	 * @since 20190529
	 * @var string
	 */
	protected $javascript = 'js/my-new-block.js';
	/**
	 *  Dependencies required for the javascript file
	 *
	 * @since 20190601
	 * @var array
	 */
	protected $dependencies = [ 'wp-blocks', 'wp-editor' ];
	/**
	 *  Block slug
	 *
	 * @since 20190529
	 * @var string
	 */
	protected $slug = 'my-new-block';
	/**
	 *  Indicates that inline javascript has been called
	 *
	 * @since 20190601
	 * @var bool
	 */
	static private $inline_js = false;

	/**
	 *  Basic construction method for class
	 *
	 * @since 20190529
	 */
	public function __construct() {
		if ( $this->inline ) {
			if ( ! static::$inline_js ) {
				add_action( 'wp_footer', [ $this, 'add_inline_javascript' ], 1000 );
				static::$inline_js = true;
			}
			add_action( 'fluid_inline_javascript', [ $this, 'inline_javascript' ] );
		} else {
			add_action( 'enqueue_block_editor_assets', [ $this, 'load_block' ] );
		}
	}

	public function add_inline_javascript() {
		echo '<script>';
		do_action( 'fluid_inline_javascript' );
		echo '</script>';
	}

	public function inline_javascript() {
		#  Over-written by child class
	}

	public function load_block() {
		wp_enqueue_script( $this->slug, get_theme_file_uri( $this->javascript ), $this->dependencies, true );
	}


}
