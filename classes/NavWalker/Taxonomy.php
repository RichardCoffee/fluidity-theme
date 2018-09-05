<?php
/**
 *  Add the terms of a taxonomy to a menu
 *
 * @package Fluidity
 * @subpackage Taxonomy
 * @since 20170111
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/NavWalker/Taxonomy.php
 * @link https://gist.github.com/daggerhart/c17bdc51662be5a588c9
 */
defined( 'ABSPATH' ) || exit;

class TCC_NavWalker_Taxonomy extends TCC_NavWalker_Dynamic {

	protected $order    = 'DESC';
	protected $orderby  = 'count';
	protected $parent   =  0;
	protected $slug     = 'taxonomy';
	protected $taxonomy = 'category';


	public function __construct( $args = array() ) {
		parent::__construct( $args );
		$terms = $this->get_terms();
		if ( is_wp_error( $terms ) ) {
			fluid()->log( $terms );
			return $terms; //  return?  really?  programmer, where do you think this going to end up?
		}
		$this->menu     = apply_filters( 'fluid_navwalker_taxonomy_menu',     $this->menu, $this->taxonomy );
		$this->position = apply_filters( 'fluid_navwalker_taxonomy_position', $this->position, $this->menu, $this->taxonomy );
		$this->add_terms( $terms );
	}

	/**
	 *  get the taxonomy terms
	 *
	 * @since 20180916
	 * @link https://developer.wordpress.org/reference/functions/get_terms/
	 * @return array
	 */
	public function get_terms() {
		$args = array(
			'taxonomy'        => $this->taxonomy,
			'hide_empty'      => true,
			'order'           => $this->order,
			'orderby'         => $this->orderby,
			'parent'          => $this->parent, // 0 = show only top-level terms
			'suppress_filter' => false,
		);
		return get_terms( $args );
	}

	/**
	 *  add taxonomy name to top level menu, and taxonomy terms as submenu
	 *
	 * @since 20180916
	 * @param array $terms
	 */
	public function add_terms( $terms ) {
		$tax_meta = get_taxonomy( $this->taxonomy );
		if ( $tax_meta ) {
			$title = ( empty( $this->title ) ) ? $tax_meta->labels->name : $this->title;
			$this->add_menu_item( $title );
			$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
			$order = 1;
			foreach( $terms as $term ) {
				if ( ! ( $this->limit < $term->count ) ) { continue; }
				if ( $order > $this->maximum ) { continue; }
				$name  = sprintf( $pattern, $term->name, $term->count );
				$path  = home_url( '/' ) . 'category/' . $term->slug;
				$this->width = max( $this->width, ( strlen( $term->name . $term->count ) + 3 ) );
				$this->add_sub_menu_item( $name, $path, $order++ );
fluid()->log( 'width:  ' . $this->width );
			}
		}
	}


}
