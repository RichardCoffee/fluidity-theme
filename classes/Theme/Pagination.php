<?php

#	http://sgwordpress.com/teaches/how-to-add-wordpress-pagination-without-a-plugin/
#	wp-includes/link-template.php
#	wp-includes/general-template.php

class TCC_Theme_Pagination {


	protected $first   = '&laquo;';
	protected $last    = '&raquo;';
	protected $link    = '&nbsp;%s&nbsp;';
	protected $nav_css = 'posts-navigation';
	protected $next    = '&gt;';
	protected $paged   = 1;
	protected $pages   = 1;
	protected $prev    = '&lt;';
	protected $range   = 1;
	protected $show    = 3;
	protected $sr_text = '';

	use TCC_Trait_Attributes;
	use TCC_Trait_ParseArgs;


	public function __construct( $args = array() ) {
		$this->get_paged();
		$this->get_pages();
		$this->sr_text = __( 'Post navigation' ,' tcc-fluid' );
		$this->parse_args( $args );
		$this->show = ( $this->range * 2 ) + 1;
	}

	protected function get_paged() {
		global $paged;
		if ( $paged ) {
			$this->paged = $paged;
		}
	}

	protected function get_pages() {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( $pages ) {
			$this->pages = $pages;
		}
	}

	public function pagination() {
		if ( $this->pages > 1 ) {
			$links = $this->generate_links();
			$template = apply_filters( 'navigation_markup_template', null, $this->nav_css );
			if ( $template ) {
				$html = sprintf( $template, sanitize_html_class( $this->nav_css ), esc_html( $$this->sr_text ), $links );
			} else {
				$template = $this->generate_markup();
				$html = sprintf( $template, $links );
			}
			echo $html;
		}
	}

	protected function generate_markup() {
		$attrs = array(
			'class' => 'navigation ' . $this->nav_css,
			'title' => $this->sr_text,
			'aria-label' => $this->sr_text,
			'role' => 'navigation',
		);
		$html = $this->get_apply_attrs_nav( $attrs );
		$html.= '<div class="nav-links">%s</div></nav>';
		return $html;
	}

	protected function generate_links() {
		ob_start(); ?>
		<ul class="pagination page-numbers"><?php
			if ( $this->show < $this->pages ) {
				$this->show_prefix_links();
			}
			for ( $i = 1; $i <= $this->pages; $i++ ) {
				$lnorm = $this->paged - $this->range - 1;
				$hnorm = $this->paged + $this->range + 1;
$d1 = ( $i === $this->pages ) ? $this->range : 0;
#$d1 = ( $i < ( $this->paged - $this->range -1 ) ) ? $this->range : 0;
#$d1 = ( ( $i < ( $this->paged + $this->range ) ) && ( $i < ( $this->pages - $this->show - 1 ) ) ) ? $this->range : 0;
#$d1 = ( ( $i < ( $this->pages - $this->show + 1 ) ) ) ? $this->range : 0;
$d2 = ( ( $i > ( $this->pages - $this->show ) ) && ( $i < $this->show + 1 ) ) ? $this->range : 0 ;
$lrange = $this->paged - $this->range - 1;// - $d1;
$hrange = $this->paged + $this->range + 1;// + $d2;
#echo "I:$i";
#echo "L:$lnorm/$lrange";
#echo "H:$hnorm/$hrange";
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

	protected function show_prefix_links() {
		if ( ( $this->paged > 2 ) && ( $this->paged > ( $this->range + 1 ) ) ) {
			$this->show_first_link();
		}
		if ( $this->paged > 1 ) {
			$this->show_previous_link();
		}
	}

	protected function show_first_link() {
		$text = __( 'First Page', 'tcc-fluid' );
		$attrs = array(
			'class' => 'first page-numbers',
			'href'  => get_pagenum_link( 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'prev',
		);
		$this->show_link( $attrs, $this->first );
	}

	protected function show_previous_link() {
		$text = __( 'Previous Page', 'tcc-fluid' );
		$attrs = array(
			'class' => 'prev page-numbers',
			'href'  => get_pagenum_link( $this->paged - 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'prev',
		);
		$this->show_link( $attrs, $this->prev );
	}

	protected function show_current_link( $int ) {
		$text = __( 'Current Page', 'tcc-fluid' );
		$attrs = array(
			'class' => 'page-numbers current',
			'title' => $text,
			'aria-label' => $text,
		); ?>
		<li>
			<span <?php $this->apply_attrs( $attrs ); ?>>
				<?php printf( $this->link, number_format_i18n( $int ) ); ?>
			</span>
		</li><?php
	}

	protected function show_page_link ( $int ) {
		$text = sprintf( _nx( 'Page %s', 'Page %s', $int, 'a number', 'tcc-fluid' ), $int );
		$attrs = array(
			'class' => 'page-numbers',
			'href'  => get_pagenum_link( $int ),
			'title' => $text,
			'aria-label' => $text,
		);
		$this->show_link( $attrs, number_format_i18n( $int ) );
	}

	protected function show_suffix_links() {
		if ( $this->paged < $this->pages ) {
			$this->show_next_link();
		}
		if ( ( $this->paged < ( $this->pages - 1 ) ) && ( ( $this->paged + $this->range - 1 ) < $this->pages ) ) {
			$this->show_last_link();
		}
	}

	protected function show_next_link() {
		$text = __('Next Page','tcc-fluid');
		$attrs = array(
			'class' => 'next page-numbers',
			'href'  => get_pagenum_link( $this->paged + 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'next',
		);
		$this->show_link( $attrs, $this->next );
	}

	protected function show_last_link() {
		$text = __('Last Page','tcc-fluid');
		$attrs = array(
			'class' => 'last page-numbers',
			'href'  => get_pagenum_link( $this->pages ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'last',
		);
		$this->show_link( $attrs, $this->last );
	}

	protected function show_link( $attrs, $text ) {
		$attrs['href'] = apply_filters( 'paginate_links', $attrs['href'], $attrs ); ?>
		<li>
			<a <?php $this->apply_attrs( $attrs ); ?>>
				<span aria-hidden="true">
					<?php printf( $this->link, esc_html( $text ) ); ?>
				</span>
			</a>
		</li><?php
	}


}
