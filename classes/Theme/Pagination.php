<?php

#	http://sgwordpress.com/teaches/how-to-add-wordpress-pagination-without-a-plugin/

class TCC_Theme_Pagination {


	protected $first = '&nbsp;&laquo;&nbsp;';
	protected $last  = '&nbsp;&raquo;&nbsp;';
	protected $library;
	protected $link  = '&nbsp;%s&nbsp;';
	protected $next  = '&nbsp;&gt;&nbsp;';
	protected $paged = 1;
	protected $pages = 1;
	protected $prev  = '&nbsp;&lt;&nbsp;';
	protected $range = 1;
	protected $show  = 3;

	use TCC_Trait_ParseArgs;
	use TCC_Trait_Singleton;


	public function __construct( $args = array() ) {
		$this->library = new TCC_Theme_Library;
		$this->get_paged();
		$this->get_pages();
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


$stats = array(
	'    pages:  ' . $this->pages,
	'    paged:  ' . $this->paged,
	'    range:  ' . $this->range,
	'showitems:  ' . $this->show,
);
echo "\n";
foreach( $stats as $stat ) {
	echo "<p>$stat</p>\n";
}

 ?>
		<nav aria-label="<?php esc_html_e( 'Page navigation' ,' tcc-fluid' ); ?>" role="navigation">
			<ul class="pagination"><?php
				if ( $this->show < $this->pages ) {
					$this->show_prefix_links();
				}
				for ( $i = 1; $i <= $this->pages; $i++ ) {
					$lnorm = $this->paged - $this->range - 1;
					$hnorm = $this->paged + $this->range + 1;
$d1 = ( ( $i < $this->show ) ? $this->range : 0 );
$d2 = ( ( $i > ( $this->pages - $this->show ) ) ? $this->range : 0 );
$lrange = $this->paged - $this->range - 1;# - $d1;
$hrange = $this->paged + $this->range + 1;# + $d2;
echo "I:$i";
echo "L:$lnorm/$lrange";
echo "H:$hnorm/$hrange";
					if ( ( $this->pages > 1 ) && ( ! ( ( $i >= $hrange ) || ( $i <= $lrange ) ) || ( $this->pages <= $this->show ) ) ) {
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
			</ul>
		</nav><?php
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
			'href'  => get_pagenum_link( $this->paged - 1 ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'prev',
		);
		$this->show_link( $attrs, $this->prev );
	}

	protected function show_current_link( $text ) { ?>
		<li>
			<span title="<?php esc_attr_e( 'Current Page', 'tcc-fluid' ); ?>">
				<?php e_esc_html( $text ); ?>
			</span>
		</li><?php
	}

	protected function show_page_link ( $int ) {
		$text = sprintf( _nx( 'Page %s', 'Page %s', $int, 'a number', 'tcc-fluid' ), $int );
		$attrs = array(
			'href'  => get_pagenum_link( $int ),
			'title' => $text,
			'aria-label' => $text,
		);
		$anchor = sprintf( $this->link, $int );
		$this->show_link( $attrs, $anchor );
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
			'href'  => get_pagenum_link( $this->pages ),
			'title' => $text,
			'aria-label' => $text,
			'rel'   => 'last',
		);
		$this->show_link( $attrs, $this->last );
	}

	protected function show_link( $attrs, $text ) { ?>
		<li>
			<a <?php $this->library->apply_attrs( $attrs ); ?>>
				<span aria-hidden="true">
					<?php e_esc_html( $text ); ?>
				</span>
			</a>
		</li><?php
	}


}
