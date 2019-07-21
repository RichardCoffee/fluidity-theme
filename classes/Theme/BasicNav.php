<?php
/**
 *  Supplies basic navigation functions.
 *
 * @package Fluidity
 * @subpackage Navigation
 * @since 20170510
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/BasicNav.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Abstract class that provides generic post navigation functions.
 *
 * @since 20170510
 */
abstract class TCC_Theme_BasicNav {

	/**
	 *  CSS class assigned to <nav> element.
	 *
	 * @since 20170510
	 * @var string
	 */
	protected $nav_css = 'posts-navigation';
	/**
	 *  Text used for title and aria-label on <nav> element.
	 *
	 * @since 20170510
	 * @var string
	 */
	protected $sr_text = '';

	/**
	 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Trait/Attributes.php
	 */
	use TCC_Trait_Attributes;
	/**
	 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Trait/ParseArgs.php
	 */
	use TCC_Trait_ParseArgs;

	/**
	 * method to generate links html
	 *
	 * @since 20170510
	 */
	abstract protected function generate_links();

	/**
	 * basic construction method for class
	 *
	 * @since 20170510
	 * @param array $args Optional.  Associative array, whose only valid indexes are
	 *                    existing class properties. All other indexes will be ignored.
	 * @uses TCC_Trait_ParseArgs::parse_args()
	 */
	public function __construct( $args = array() ) {
		$this->sr_text = __( 'Post navigation' , 'tcc-fluid' );
		$this->parse_args( $args );
	}

	/**
	 * replicates wordpress navigation template use
	 *
	 * @since 20170510
	 * @uses apply_filters()
	 * @uses sanitize_html_class()
	 * @uses esc_html()
	 */
	protected function generate_navigation() {
		$template = apply_filters( 'navigation_markup_template', null, $this->nav_css );
		if ( $template ) {
			printf( $template, sanitize_html_class( $this->nav_css ), esc_html( $this->sr_text ), $this->generate_links() );
		} else {
			if ( $template = $this->generate_markup() ) {
				printf( $template, $this->generate_links() );
			}
		}
	}

	/**
	 * creates div.nav-links html for wrapping navigation links
	 *
	 * @since 20170510
	 * @uses TCC_Trait_Attributes::get_tag()
	 * @return string
	 */
	protected function generate_markup() {
		$attrs = array(
			'class' => 'navigation noprint ' . $this->nav_css,
			'role'  => 'navigation',
			'title' => $this->sr_text,
			'aria-label' => $this->sr_text,
		);
		$html = $this->get_tag( 'nav', $attrs );
		$html.= '<div class="nav-links">%s</div>';
		$html.= '</nav>';
		return $html;
	}

}
