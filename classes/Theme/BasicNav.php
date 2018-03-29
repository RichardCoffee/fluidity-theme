<?php
/**
 * classes/Theme/BasicNav.php
 *
 * @package Fluidity
 * @subpackage Navigation
 * @since 2.3.0
 */
/**
 * abstract class that provides generic post navigation functions
 *
 * @since 2.3.0
 */
abstract class TCC_Theme_BasicNav {

	/**
	 * css class assigned to <nav> object
	 *
	 * @since 2.3.0
	 * @var string
	 */
	protected $nav_css = 'posts-navigation';

	/**
	 * text used for title and aria-label on <nav> object
	 *
	 * @since 2.3.0
	 * @var string
	 */
	protected $sr_text = '';

	use TCC_Trait_Attributes;
	use TCC_Trait_ParseArgs;

	/**
	 * method to generate links html
	 *
	 * @since 2.3.0
	 */
	abstract protected function generate_links();

	/**
	 * basic construct method for class
	 *
	 * @since 2.3.0
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		$this->sr_text = __( 'Post navigation' ,' tcc-fluid' );
		$this->parse_args( $args );
	}

	/**
	 * replicates wordpress navigation template use
	 *
	 * @since 2.3.0
	 * @return string
	 */
	protected function generate_navigation() {
		$links = $this->generate_links();
		$template = apply_filters( 'navigation_markup_template', null, $this->nav_css );
		if ( $template ) {
			$html = sprintf( $template, sanitize_html_class( $this->nav_css ), esc_html( $this->sr_text ), $links );
		} else {
			if ( $template = $this->generate_markup() ) {
				$html = sprintf( $template, $links );
			} else {
				$html = '';
			}
		}
		return $html;
	}

	/**
	 * creates div.nav-links html for wrapping navigation links
	 *
	 * @since 2.3.0
	 * @return string
	 */
	protected function generate_markup() {
		$attrs = array(
			'class' => 'navigation no-print ' . $this->nav_css,
			'title' => $this->sr_text,
			'aria-label' => $this->sr_text,
			'role'  => 'navigation',
		);
		$html = $this->get_apply_attrs_tag( $attrs, 'nav' );
		$html.= '<div class="nav-links">%s</div>';
		$html.= '</nav>';
		return $html;
	}


}
