<?php
/**
 *  Handles post pagination.
 *
 * @package Fluidity
 * @subpackage Navigation
 * @since 20190721
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2019, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/PostPages.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Handles post pagination tasks.
 *
 */
class TCC_Theme_PostPages extends TCC_Theme_Pagination {

	/**
	 *  Text used for going to the next page.
	 *
	 * @since 20190721
	 * @var string
	 */
	protected $next = '';
	/**
	 *  Text used for going to the previous page.
	 *
	 * @since 20190721
	 * @var string
	 */
	protected $prev = '';

	/**
	 *  Setup for showing pagination.
	 *
	 * @since 20170505
	 * @param array $args
	 * @uses TCC_Trait_ParseArgs::parse_args()
	 * @global $multipage
	 * @global $numpages
	 * @global $page
	 */
	public function __construct( $args = array() ) {
		global $multipage, $numpages, $page;
		if ( $multipage ) {
			$this->pagination_text();
			$this->parse_args( $args );
			$this->show  = $numpages;
			$this->range = $numpages;
			$this->paged = $page;
			$this->pages = $this->show + 1;
			$new = apply_filters( 'fluid_link_pages_args', (array) $this );
			if ( ! ( $new === (array) $this ) ) {
				$this->parse_args( $args );
			}
			$this->generate_navigation();
		}
	}

	/**
	 *  Strings to be displayed for page options.
	 *
	 * @since 20190721
	 */
	protected function pagination_text() {
		$this->nav_css = 'post-page-navigation';
		$this->sr_text = esc_html__( 'Post Page Navigation', 'tcc-fluid' );
		parent::pagination_text();
		$this->text['plabel'] = esc_html__( 'Previous', 'tcc-fluid' );
		$this->text['nlabel'] = esc_html__( 'Next', 'tcc-fluid' );
	}

	/**
	 *  Show the previous page link.
	 *
	 * @since 20190721
	 */
	protected function show_prefix_links() {
		if ( $this->paged > 1 ) {
			$this->show_previous_link();
		}
		$this->pages = $this->show;
	}

	/**
	 *  Show a page link.
	 *
	 * @since 20190721
	 */
	protected function show_page_link( $int ) {
		parent::show_page_link( $int );
		if ( $int === $this->pages ) {
			$this->show = $this->pages - 1;
		}
	}

	/**
	 *  Show the next page link.
	 *
	 * @since 20190721
	 */
	protected function show_suffix_links() {
		if ( $this->paged < $this->pages ) {
			$this->show_next_link();
		}
	}

	/**
	 *  Get the href link.
	 *
	 * @since 20190721
	 * @param integer $int
	 * @return string
	 */
	protected function get_link( $int ) {
		$anchor = _wp_link_page( $int );
		$html   = fluid()->get_html_object( $anchor . '</a>' );
		return $html->attrs['href'];
	}

	/**
	 *  Show the page link
	 *
	 * @since 20190721
	 * @param array $attrs
	 * @param string $text
	 */
	protected function show_link( $attrs, $text ) {
		if ( $attrs['class'] === 'prev page-numbers' ) {
			$text = $this->text['plabel'];
		} else if ( $attrs['class'] === 'next page-numbers' ) {
			$text = $this->text['nlabel'];
		}
		parent::show_link( $attrs, $text );
	}


}
