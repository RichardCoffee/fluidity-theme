<?php

class TCC_Theme_Navigation extends TCC_Theme_BasicNav {


	protected $all_links      = true;
	protected $excluded_terms = array();
	protected $left           = '<span aria-hidden="true">&laquo;</span> %title';
	protected $li_css         = 'btn-fluidity';
	protected $newer_all      = '';
	protected $newer_link     = '';
	protected $newer_taxonomy = '';
	protected $older_all      = '';
	protected $older_link     = '';
	protected $older_taxonomy = '';
	protected $right          = '%title <span aria-hidden="true">&raquo;</span>';
	protected $show_newer     = true;
	protected $show_older     = true;
	protected $sr_all_links   = '';
	protected $sr_taxonomy    = '';
	protected $taxonomy       = '';
	protected $ul_css         = '';

	use TCC_Trait_Logging;


	public function __construct( $args = array() ) {
		$this->newer_all      = __( 'Newer Post', 'tcc-fluid' );
		$this->newer_taxonomy = _x( 'Newer %s post', 'the taxonomy label (singular)', 'tcc-fluid' );
		$this->older_all      = __( 'Older Post', 'tcc-fluid' );
		$this->older_taxonomy = _x( 'Older %s post', 'the taxonomy label (singular)', 'tcc-fluid' );
		$this->sr_all_links   = __( 'Posts Navigation', 'tcc-fluid' );
		$this->sr_taxonomy    = __( 'Category Navigation', 'tcc-fluid' );
		parent::__construct( $args );
		$this->navigation();
	}

	protected function navigation() {
		$this->get_posts();

/*
		if ( $this->taxonomy || $this->all_links ) { ?>
			<div>
				<div id="post-link-separator-top" class="post-link-separator post-link-separator-top"></div><?php
					if ( $this->taxonomy ) {
						$this->taxonomy_links();
					}
					if ( $this->taxonomy && $this->all_links ) { ?>
						<div id="post-link-separator-middle" class="post-link-separator post-link-separator-middle"></div><?php
					}
					if ( $this->all_links ) {
						$this->all_links();
					} ?>
				<div id="post-link-separator-bottom" class="post-link-separator post-link-separator-bottom"></div>
			</div><?php
		} //*/
	}

	protected function get_posts() {
		$this->excluded_terms = apply_filters( 'fluid_navigation_excluded_terms', $this->excluded_terms, $this->taxonomy );


$prev_tax = get_permalink( get_adjacent_post( true,  $exclude, true,  $taxonomy ) );
$next_tax = get_permalink( get_adjacent_post( true,  $exclude, false, $taxonomy ) );
$prev_all = get_permalink( get_adjacent_post( false, $exclude, true ) );
$next_all = get_permalink( get_adjacent_post( false, $exclude, false ) );
$this->log(
"    taxonomy: $this->taxonomy",
"previous tax: $prev_tax",
"previous all: $prev_all",
"    next tax: $next_tax",
"    next all: $next_all"
);



		if ( $this->taxonomy && $this->all_links ) {
			$prev_tax = $this->get_adjacent_post( true,  $this->excluded_terms, true,  $this->taxonomy );
			$next_tax = $this->get_adjacent_post( true,  $this->excluded_terms, false, $this->taxonomy );
			$prev_all = $this->get_adjacent_post( false, $this->excluded_terms, true );
			$next_all = $this->get_adjacent_post( false, $this->excluded_terms, false );
			if ( ( $prev_tax->ID === $prev_all->ID ) && ( $next_tax->ID === $next_all->ID ) ) {
				$this->taxonomy = '';
			} else {
				$this->show_newer = ( $next_tax->ID !== $next_all->ID );
				$this->show_older = ( $prev_tax->ID !== $prev_all->ID );
			}
		}
	}

	protected function get_adjacent_post( $in_same_tax, $excluded, $direction, $taxonomy = 'category' ) {
		$post = get_adjacent_post( $in_same_tax, $excluded, $direction, $taxonomy );
		if ( empty( $post ) ) {
			$post = new stdClass;
			$post->ID = 0;
		}
echo "<p>{$post->title}</p>";
		return $post;
	}

	protected function taxonomy_links() {
		$tax_obj = get_taxonomy( $this->taxonomy );
		$this->newer_link = sprintf( $this->newer_taxonomy, $tax_obj->labels->singular_name );
		$this->older_link = sprintf( $this->older_taxonomy, $tax_obj->labels->singular_name );
		$this->sr_text    = $this->sr_taxonomy;
		$this->ul_css     = 'pager pager-taxonomy';
		$this->generate_navigation();
	}

	protected function all_links() {
		$this->newer_link = $this->newer_all;
		$this->older_link = $this->older_all;
		$this->sr_text    = $this->sr_all_links;
		$this->show_newer = true;
		$this->show_older = true;
		$this->taxonomy   = '';
		$this->ul_css     = 'pager pager-all';
		$this->generate_navigation();
	}

	protected function generate_links() {
		ob_start(); ?>
			<ul class="<?php echo esc_attr( $this->ul_css ); ?>"><?php
				$li_attrs = array(
					'class' => 'previous '. $this->li_css,
					'title' => $this->older_link,
				);
				if ( $this->show_older ) {
					$this->apply_attrs_tag( $li_attrs, 'li' );
						previous_post_link( '%link', $this->left, true, $this->excluded_terms, $this->taxonomy ); ?>
					</li><?php
				}
				$li_attrs = array(
					'class' => 'next '. $this->li_css,
					'title' => $this->newer_link,
				);
				if ( $this->show_newer ) {
					$this->apply_attrs_tag( $li_attrs, 'li' );
						next_post_link( '%link', $this->right, true, $this->excluded_terms, $this->taxonomy ); ?>
					</li><?php
				} ?>
			</ul><?php
		return ob_get_clean();
	}


}
