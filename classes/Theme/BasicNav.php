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
		$this->sr_text = __( 'Post navigation' , 'tcc-fluid' );
		$this->parse_args( $args );
	}

	/**
	 * replicates wordpress navigation template use
	 *
	 * @since 2.3.0
	 * @return string
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
	 * @since 2.3.0
	 * @return string
	 */
	protected function generate_markup() {
		$attrs = array(
			'class' => 'navigation noprint ' . $this->nav_css,
			'title' => $this->sr_text,
			'aria-label' => $this->sr_text,
		);
		$html = $this->get_tag( 'nav', $attrs );
		$html.= '<div class="nav-links">%s</div>';
		$html.= '</nav>';
		return $html;
	}


}
