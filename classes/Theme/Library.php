<?php
/**
 *  Handle certain theme tasks
 *
 * @package Fluidity
 * @subpackage Theme
 * @since 20170418
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2017, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/Library.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Library class to provide certain functions for common usage.
 */
class TCC_Theme_Library {


	/**
	 * @since 20200223
	 * @var string Github branch to get updates from
	 */
	protected $branch = 'master';
	/**
	 * @since 20200223
	 * @var string URL of Github repository
	 */
	protected $github = 'https://github.com/RichardCoffee/fluidity-theme';


	/**
	 * @since 20170507
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/Attributes.php
	 */
	use TCC_Trait_Attributes;
	/**
	 * @since 20180324
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/Logging.php
	 */
	use TCC_Trait_Logging;
	/**
	 * @since 20180410
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/Magic.php
	 */
	use TCC_Trait_Magic;


	/**
	 *  Constructor method for the Library class.
	 *
	 * @since 20180410
	 */
	public function __construct() {
		// verify callable logging function
		$this->logging_check_function();
		// register method aliases
		$this->register__call( array( $this, 'logging_calling_location' ),          'debug_calling_function' );
		$this->register__call( array( $this, 'logging_get_calling_function_name' ), 'get_calling_function' );
		$this->register__call( array( $this, 'logging_was_called_by' ),             'was_called_by' );
		// log the stack when one of these actions is run
		if ( WP_DEBUG && function_exists( 'add_action' ) ) {
			add_action( 'deprecated_function_run',    array( $this, 'logging_log_deprecated' ), 10, 3 );
			add_action( 'deprecated_constructor_run', array( $this, 'logging_log_deprecated' ), 10, 3 );
			add_action( 'deprecated_file_included',   array( $this, 'logging_log_deprecated' ), 10, 4 );
			add_action( 'deprecated_argument_run',    array( $this, 'logging_log_deprecated' ), 10, 3 );
			add_action( 'deprecated_hook_run',        array( $this, 'logging_log_deprecated' ), 10, 4 );
			add_action( 'doing_it_wrong_run',         array( $this, 'logging_log_deprecated' ), 10, 3 );
		}
		if ( is_admin() ) {
			$this->load_update_checker();
		}
	}

	/**
	 *  Load the update checker
	 *
	 * @since 20200223
	 * @link https://github.com/YahnisElsts/plugin-update-checker
	 */
	protected function load_update_checker() {
		if ( $this->github ) {
			$puc_file = FLUIDITY_HOME . 'vendor/plugin-update-checker/plugin-update-checker.php';
			if ( is_readable( $puc_file ) ) {
				require_once( $puc_file );
				$puc = Puc_v4_Factory::buildUpdateChecker( $this->github, FLUIDITY_HOME . 'style.css', 'fluidity-theme' );
				$puc->setBranch( $this->branch );
			}
		}
	}

#	 * @since 20191216
	public function log_chance( $data, $max_chance = 1000 ) {
		if ( WP_DEBUG ) {
			if ( mt_rand( 1, $max_chance ) === 1 ) {
				$this->log( $data, 'stack' );
			}
		}
	}


	/**  Font Awesome icons  **/

	/**
	 *  Invokes get_fawe method with using wp_kses filter, and echos the result.
	 *
	 * @since 20170426
	 * @param string $icon Fawe icon to display.
	 */
	public function fawe( $icon = 'fa-question fa-border' ) {
		echo wp_kses( $this->get_fawe( $icon ), $this->kses() );
	}

	/**
	 *  Returns an html string to display a fawe icon.
	 *
	 * @since 20170426
	 * @param string $icon Fawe icon to retrieve.
	 * @return string html string for the fawe icon.
	 */
	public function get_fawe( $icon = 'fa-question fa-border' ) {
		$css = explode( ' ', $icon );
		$css = array_map(
			function( $icon ) {
				if ( ! ( substr( $icon, 0, 3 ) === 'fa-' ) ) {
					$icon = 'fa-' . $icon;
				}
				return $icon;
			},
			$css
		);
#		if ( ( ! in_array( 'fab', $css, true ) ) && ( ! in_array( 'fas', $css, true ) ) ) {
#			array_push( $css, 'fas' ); // default for 4.7.0 icons
#		}
#		array_push( $css, 'fa' ); // 4.7.0 crossover
		$args = array (
			'class'       => 'fa ' . implode( ' ', $css ),
			'aria-hidden' => 'true',
		);
		return $this->get_element( 'i', $args );
	}

	/**
	 *  Returns an array of fawe icon names, used for displaying on widgets.
	 *
	 * @since 20170427
	 * @return array An array of arrays of fawe icon names.
	 */
	public function get_widget_fawe() {
		return array(
			'default'  => array(
				'plus'  => 'fa-plus',
				'minus' => 'fa-minus',
			),
			'square'   => array(
				'plus'  => 'fa-plus-square',
				'minus' => 'fa-minus-square',
			),
			'circle'   => array(
				'plus'  => 'fa-plus-circle',
				'minus' => 'fa-minus-circle',
			),
			'sort'     => array(
				'plus'  => 'fa-sort-down',
				'minus' => 'fa-sort-up',
			),
			'sortel'   => array(
				'plus'  => 'fa-sort-down',
				'minus' => 'fa-sort-up fa-rotate-270',
			),
			'window'   => array(
				'plus'  => 'fa-window-maximize',
				'minus' => 'fa-window-minimize',
			),
			'toggle'   => array(
				'plus'  => 'fa-toggle-down',
				'minus' => 'fa-toggle-up',
			),
			'level'    => array(
				'plus'  => 'fa-level-down',
				'minus' => 'fa-level-up',
			),
		);
	}


	/**
	 *  Utilize the mobble plugin if it is present.
	 *
	 * @since 20180416
	 * @return bool True if mobile test passed.
	 */
	public function is_mobile() {
		if ( class_exists( 'Mobile_Detect' ) && function_exists( 'is_mobile' ) ) {
			$mobile = is_mobile();
		} else {
			$mobile = wp_is_mobile();
		}
		return $mobile;
	}

	/**
	 *  Provides a way to get metadata attributes for an attachment image
	 *
	 * @since 20170425
	 * @param integer $id Attachment ID
	 * @return mixed      Attachment metadata
	 */
	public function get_image_attrs( $id ) {
		$meta = wp_get_attachment_metadata( $id );
	}

	/**
	 *  Provides attributes parameter for wp_kses filter function, duplicated in TCC_Plugin_Library.
	 *
	 * @since 20180501
	 * @return array Array containing permissible attributes for html elements.
	 */
	public function kses() {
		return array(
			'a'    => [ 'class' => [ ], 'href' => [ ], 'itemprop' => [ ], 'rel' => [ ], 'target' => [ ], 'title' => [ ], 'aria-label' => [ ] ],
			'b'    => [ ],
			'i'    => [ 'class' => [ ] ],
			'span' => [ 'class' => [ ], 'itemprop' => [ ] ],
		);
	}

	/**
	 *  Get an object containing information about an html element
	 *
	 * @since 20180524
	 * @param string $html The html element to parse.
	 * @return object The object properties will contain information on the html element.
	 */
	public function get_html_object( $html ) {
		$obj = new stdClass();
		$doc = new DOMDocument();
		$doc->loadHTML( $html );
		$element = $doc->documentElement->firstChild->firstChild;
		$obj->attrs = array();
		$obj->tag   = $element->tagName;
		$obj->text  = $element->textContent;
		if ( $element->hasAttributes() ) {
			foreach ( $element->attributes as $attr ) {
				$obj->attrs[ $attr->nodeName ] = $attr->nodeValue;
			}
		}
		return $obj;
	}

	/**
	 *  makes the bbPress function available outside of admin pages.
	 *
	 * @since 20180906
	 * @uses get_option()
	 * @uses esc_attr()
	 * @uses apply_filters()
	 * @param string $option
	 * @param string $default
	 * @param bool $slug
	 * @return mixed
	 */
	public function bbp_get_form_option( $option, $default = '', $slug = false ) {
		if ( function_exists( 'bbp_get_form_option' ) ) {
			return bbp_get_form_option( $option, $default, $slug );
		}
		$value = get_option( $option, $default );
		if ( true === $slug ) {
			$value = esc_attr( apply_filters( 'editable_slug', $value ) );
		} else {
			$value = esc_attr( $value );
		}
		if ( empty( $value ) ) $value = $default;
		return apply_filters( 'bbp_get_form_option', $value, $option, $default, $slug );
	}


}
