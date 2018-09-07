<?php
/**
 *  handles posts pagination, on archive pages.
 *
 * @package Fluidity
 * @subpackage Navigation
 * @since 20170505
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/Pagination.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  handles theme pagination tasks.
 *
 * @link http://sgwordpress.com/teaches/how-to-add-wordpress-pagination-without-a-plugin/
 * @link wp-includes/link-template.php
 * @link wp-includes/general-template.php
 */
class TCC_Theme_Pagination extends TCC_Theme_BasicNav {

	/**
	 *  symbol used for going to the first page.
	 *
	 * @since 20170505
	 * @var string
	 */
	protected $first = '&laquo;';
	/**
	 *  symbol used for going to the last page.
	 *
	 * @since 20170505
	 * @var string
	 */
	protected $last = '&raquo;';
	/**
	 *  format used for links.
	 *
	 * @since 20170505
	 * @var string
	 */
	protected $link = '&nbsp;%s&nbsp;';
	/**
	 *  symbol used for going to the next page.
	 *
	 * @since 20170505
	 * @var string
	 */
	protected $next = '&gt;';
	/**
	 *  symbol used for going to the previous page.
	 *
	 * @since 20170505
	 * @var string
	 */
	protected $prev = '&lt;';
	/**
	 *  default url link used for main menu item.
	 *
	 * @since 20170505
	 * @var string
	 */
	protected $paged = 1;
	/**
	 *  number of pages.
	 *
	 * @since 20170505
	 * @var int
	 */
	protected $pages = 1;
	/**
	 *  page range to be displayed.
	 *
	 * @since 20170505
	 * @var int
	 */
	protected $range = 1;
	/**
	 *  number of total page links to show, calced from $this->range.
	 *
	 * @since 20170505
	 * @var int
	 */
	protected $show = 3;

	/**
	 *  sets up for showing pagination.
	 *
	 * @since 20170505
	 * @param array $args
	 * @uses TCC_Theme_BasicNav::__construct()
	 */
	public function __construct( $args = array() ) {
		$this->get_paged();
		$this->get_pages();
		parent::__construct( $args );
		$this->show = ( $this->range * 2 ) + 1;
		$this->pagination();
	}

	/**
	 *  initialize the paged property.
	 *
	 * @since 20170505
	 * @global $paged
	 */
	protected function get_paged() {
		global $paged;
		if ( $paged ) {
			$this->paged = $paged;
		}
	}

	/**
	 *  gets the number of pages.
	 *
	 *
	 * @since 20170505
	 * @global $wp_query
	 */
	protected function get_pages() {
		global $wp_query;
		if ( $wp_query ) {
			$pages = $wp_query->max_num_pages;
			if ( $pages ) {
				$this->pages = $pages;
			}
		}
	}

	/**
	 *  check to see if pagination is required.
	 *
	 * @since 20170505
	 */
	protected function pagination() {
		if ( $this->pages > 1 ) {
			$this->generate_navigation();
		}
	}

	/**
	 *  generate the links for pagination.
	 *
	 * @since 20170506
	 */
	protected function generate_links() {
		ob_start(); ?>
		<ul class="pagination page-numbers"><?php
			if ( $this->show < $this->pages ) {
				$this->show_prefix_links();
			}
			for ( $i = 1; $i <= $this->pages; $i++ ) {
				$delta1 = ( $i > ( $this->pages - $this->show ) ) ? $this->range : 0;
				$delta2 = ( $i < ( $this->show + 1 ) ) ? $this->range : 0 ;
				$lrange = $this->paged - $this->range - 1 - $delta1;
				$hrange = $this->paged + $this->range + 1 + $delta2;
				if ( ( ! ( ( $i >= $hrange ) || ( $i <= $lrange ) ) || ( $this->pages <= $this->show ) ) ) {
					if ( $this->paged === $i ) {
						$this->show_current_link( $i );
					} else {
						$this->show_page_link( $i );
					}
				}
			}
			if ( $this->show < $this->pages ) {
				$this->show_suffix_links();
			} ?>
		</ul><?php
		return ob_get_clean();
	}

	/**
	 *  show the first and/or previous links.
	 *
	 * @since 20170505
	 */
	protected function show_prefix_links() {
		if ( ( $this->paged > 2 ) && ( $this->paged > ( $this->range + 1 ) ) ) {
			$this->show_first_link();
		}
		if ( $this->paged > 1 ) {
			$this->show_previous_link();
		}
	}

	/**
	 *  show the first page link.
	 *
	 * @since 20170505
	 * @uses __()
	 * @uses get_pagenum_link()
	 */
	protected function show_first_link() {
		$text = __( 'Go to the first page', 'tcc-fluid' );
		$attrs = array(
			'class' => 'first page-numbers',
			'href'  => get_pagenum_link( 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'prev',
		);
		$this->show_link( $attrs, $this->first );
	}

	/**
	 *  show the previous link.
	 *
	 * @since 20170505
	 * @uses __()
	 * @uses get_pagenum_link()
	 */
	protected function show_previous_link() {
		$text = __( 'Go to the previous page', 'tcc-fluid' );
		$attrs = array(
			'class' => 'prev page-numbers',
			'href'  => get_pagenum_link( $this->paged - 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'prev',
		);
		$this->show_link( $attrs, $this->prev );
	}

	/**
	 *  show the current page text.
	 *
	 * @since 20170505
	 * @uses __()
	 * @uses number_format_i18n()
	 * @uses TCC_Trait_Attributes::apply_attrs()
	 */
	protected function show_current_link( $int ) {
		$text = __( 'Current Page', 'tcc-fluid' );
		$attrs = array(
			'class' => 'page-numbers current',
			'title' => $text,
			'aria-label' => $text,
		); ?>
		<li>
			<span <?php $this->apply_attrs( $attrs ); ?>><?php
				printf( $this->link, number_format_i18n( $int ) ); ?>
			</span>
		</li><?php
	}

	/**
	 *  show a page link.
	 *
	 * @since 20170505
	 * @uses _nx()
	 * @uses get_pagenum_link()
	 */
	protected function show_page_link ( $int ) {
		$text = sprintf( _nx( 'Go to page %s', 'Go to page %s', $int, 'page number', 'tcc-fluid' ), $int );
		$attrs = array(
			'class' => 'page-numbers',
			'href'  => get_pagenum_link( $int ),
			'title' => $text,
			'aria-label' => $text,
		);
		$this->show_link( $attrs, number_format_i18n( $int ) );
	}

	/**
	 *  show the last and/or last page links
	 *
	 * @since 20170505
	 */
	protected function show_suffix_links() {
		if ( $this->paged < $this->pages ) {
			$this->show_next_link();
		}
		if ( ( $this->paged < ( $this->pages - 1 ) ) && ( ( $this->paged + $this->range - 1 ) < $this->pages ) ) {
			$this->show_last_link();
		}
	}

	/**
	 *  show the next page link.
	 *
	 * @since 20170505
	 * @uses __()
	 * @uses get_pagenum_link()
	 */
	protected function show_next_link() {
		$text = __('Go to next page','tcc-fluid');
		$attrs = array(
			'class' => 'next page-numbers',
			'href'  => get_pagenum_link( $this->paged + 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'next',
		);
		$this->show_link( $attrs, $this->next );
	}

	/**
	 *  show the last page link.
	 *
	 * @since 20170505
	 * @uses __()
	 * @uses get_pagenum_link()
	 */
	protected function show_last_link() {
		$text = __('Go to last page','tcc-fluid');
		$attrs = array(
			'class' => 'last page-numbers',
			'href'  => get_pagenum_link( $this->pages ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'last',
		);
		$this->show_link( $attrs, $this->last );
	}

	/**
	 *  show a link.
	 *
	 * @since 20170505
	 * @uses apply_filters()
	 * @uses esc_html()
	 * @uses TCC_Trait_Attributes::apply_attrs()
	 */
	protected function show_link( $attrs, $text ) {
		$attrs['href'] = apply_filters( 'paginate_links', $attrs['href'], $attrs ); ?>
		<li>
			<a <?php $this->apply_attrs( $attrs ); ?>>
				<span aria-hidden="true">
					<?php echo esc_html( sprintf( $this->link, $text ) ); ?>
				</span>
			</a>
		</li><?php
	}


}
