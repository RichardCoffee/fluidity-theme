<?php
/**
 *  Add the terms of a taxonomy to a menu
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/NavWalker/Taxonomy.php
 * @link https://gist.github.com/daggerhart/c17bdc51662be5a588c9
 */

class TCC_NavWalker_Taxonomy {

	private $menu     = 'primary';
	private $order    = 'DESC';
	private $orderby  = 'count';
	private $taxonomy = 'category';
	private $top_id   = 29876; // presumably there won't actually be a menu item with this id

	use TCC_Trait_ParseArgs;


	public function __construct( $args = array() ) {
		$this->parse_args( $args );
		$terms = $this->get_terms();
		if ( is_wp_error( $terms ) ) {
			fluid()->log( $terms );
			return $terms;
		}
#		fluid()->log($terms);
		$this->add_terms( $terms );
	}

#	 * @link https://developer.wordpress.org/reference/functions/get_terms/
	public function get_terms() {
		$args = array(
			'taxonomy'        => $this->taxonomy,
			'hide_empty'      => true,
			'order'           => $this->order,
			'orderby'         => $this->orderby,
			'parent'          => 0, // show only top-level terms
			'suppress_filter' => false,
		);
		return get_terms( $args );
	}

	public function add_terms( $terms ) {
		$tax_meta = get_taxonomy( $this->taxonomy );
		if ( $tax_meta ) {
			require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
			custom_menu_items::add_item( $this->menu, $tax_meta->labels->name, 'javascript: void(0);', 0, 0, $this->top_id );
			foreach( $terms as $term ) {
				$name = $term->name . fluid()->get_element( 'span', [ 'class' => 'term-count' ], $term->count );
				$path = 'category/' . $term->slug;
				custom_menu_items::add_item( $this->menu, $name, $path, 0, $this->top_id );
			}
		}
	}


}
