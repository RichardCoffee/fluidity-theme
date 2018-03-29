<?php

/*
 *    This class can only be used inside the Loop.
 */

class TCC_Theme_Navigation extends TCC_Theme_BasicNav {


	protected $all_links      = true;
	protected $excluded_terms = array();
	protected $left           = '<span aria-hidden="true">&laquo;</span> %title';
	protected $li_css         = 'btn-fluidity';
	protected $newer_link     = '';
	protected $older_link     = '';
	protected $posts          = array();
	protected $right          = '%title <span aria-hidden="true">&raquo;</span>';
	protected $same_term      = false;
	protected $show_newer     = true;
	protected $show_older     = true;
	protected $taxonomy       = '';
	protected $text           = array();
	protected $ul_css         = '';

	use TCC_Trait_Logging;


	public function __construct( $args = array() ) {
		parent::__construct( $args );
		$this->navigation_text();
		$this->taxonomy = apply_filters( 'fluid_navigation_taxonomy', $this->taxonomy );
		$this->check_posts();
		$this->navigation();
	}

	protected function navigation_text() {
		$this->text = array(
			'new_all' => __( 'Newer Post', 'tcc-fluid' ),
			'new_tax' => _x( 'Newer %s post', 'the taxonomy label (singular)', 'tcc-fluid' ),
			'old_all' => __( 'Older Post', 'tcc-fluid' ),
			'old_tax' => _x( 'Older %s post', 'the taxonomy label (singular)', 'tcc-fluid' ),
			'sr_all'  => __( 'Posts Navigation', 'tcc-fluid' ),
			'sr_tax'  => __( 'Category Navigation', 'tcc-fluid' )
		);
	}

	protected function navigation() {
		if ( $this->taxonomy || $this->all_links ) { ?>
			<div class="article">
				<div id="post-link-separator-top" class="post-link-separator post-link-separator-top"></div><?php
					if ( $this->taxonomy ) {
						echo $this->taxonomy_links();
					}
					if ( $this->taxonomy && $this->all_links ) { ?>
						<div id="post-link-separator-middle" class="post-link-separator post-link-separator-middle"></div><?php
					}
					if ( $this->all_links ) {
						echo $this->all_links();
					} ?>
				<div id="post-link-separator-bottom" class="post-link-separator post-link-separator-bottom"></div>
			</div><?php
		} //*/
	}

	protected function check_posts() {
		$posts = array();
		$this->excluded_terms = apply_filters( 'fluid_navigation_excluded_terms', $this->excluded_terms, $this->taxonomy );

		$posts['prev_tax'] = $this->get_adjacent_post( true,  $this->excluded_terms, true,  $this->taxonomy );
		$posts['next_tax'] = $this->get_adjacent_post( true,  $this->excluded_terms, false, $this->taxonomy );
		$posts['prev_all'] = $this->get_adjacent_post( false, $this->excluded_terms, true );
		$posts['next_all'] = $this->get_adjacent_post( false, $this->excluded_terms, false );
		$this->posts = $posts;
$this->log(
"    taxonomy: $this->taxonomy",
"previous tax: {$posts['prev_tax']->ID} ".$posts['prev_tax']->post_title,
"previous all: {$posts['prev_all']->ID} ".$posts['prev_all']->post_title,
"    next tax: {$posts['next_tax']->ID} ".$posts['next_tax']->post_title,
"    next all: {$posts['next_all']->ID} ".$posts['next_all']->post_title
); //*/
		if ( $this->taxonomy && $this->all_links ) {
			if ( ( $posts['prev_tax']->ID === $posts['prev_all']->ID ) && ( $posts['next_tax']->ID === $posts['next_all']->ID ) ) {
				$this->taxonomy = '';
			} else {
				$this->show_newer = ( $posts['next_tax']->ID !== $posts['next_all']->ID );
				$this->show_older = ( $posts['prev_tax']->ID !== $posts['prev_all']->ID );
$this->log(
'show newer:  ' . $this->show_newer,
'show older:  ' . $this->show_older
);
			}
		} //*/
	}

	protected function get_adjacent_post( $in_same_tax, $excluded, $direction, $taxonomy = 'category' ) {
		$post = get_adjacent_post( $in_same_tax, $excluded, $direction, $taxonomy );
		if ( empty( $post ) ) {
			$post = new stdClass;
			$post->ID = 0;
			$post->post_title = '';
		}
		return $post;
	}

	protected function taxonomy_links() {
		if ( $this->taxonomy === 'category' ) {
			$category = $this->get_post_category();
			$this->newer_link = sprintf( $this->text['new_tax'], $category );
			$this->older_link = sprintf( $this->text['old_tax'], $category );
		} else {
			$tax_obj = get_taxonomy( $this->taxonomy );
			$this->newer_link = sprintf( $this->text['new_tax'], $tax_obj->labels->singular_name );
			$this->older_link = sprintf( $this->text['old_tax'], $tax_obj->labels->singular_name );
		}
		$this->sr_text    = $this->text['sr_tax'];
		$this->ul_css     = 'pager pager-taxonomy';
		$this->same_term  = true;
		return $this->generate_navigation();
	}

	protected function get_post_category() {
		$terms = wp_get_post_terms( get_the_ID(), 'category' );
		return ( ( ! $terms) || is_wp_error( $terms ) ) ? 'Category' : $terms[0]->name;
	}

	protected function all_links() {
		$this->newer_link = $this->text['new_all'];
		$this->older_link = $this->text['old_all'];
		$this->sr_text    = $this->text['sr_all'];
		$this->show_newer = true;
		$this->show_older = true;
		$this->ul_css     = 'pager pager-all';
		$this->same_term  = false;
		return $this->generate_navigation();
	}

	protected function generate_links() {
		if ( ( ! $this->show_older ) && ( ! $this->show_newer ) ) {
			return '';
		}
		ob_start(); ?>
			<div class="row">
				<ul class="<?php echo esc_attr( $this->ul_css ); ?>"><?php
					$li_attrs = array(
						'class' => 'previous '. $this->li_css,
						'title' => $this->older_link,
					);
					if ( $this->show_older ) {
						$this->apply_attrs_tag( $li_attrs, 'li' );
							previous_post_link( '%link', $this->left, $this->same_term, $this->excluded_terms, $this->taxonomy ); ?>
						</li><?php
					}
					$li_attrs = array(
						'class' => 'next '. $this->li_css,
						'title' => $this->newer_link,
					);
					if ( $this->show_newer ) {
						$this->apply_attrs_tag( $li_attrs, 'li' );
							next_post_link( '%link', $this->right, $this->same_term, $this->excluded_terms, $this->taxonomy ); ?>
						</li><?php
					} ?>
				</ul>
			</div><?php //*/
		return ob_get_clean();
	}


}
