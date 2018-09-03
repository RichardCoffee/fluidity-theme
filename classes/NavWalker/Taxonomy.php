<?php
/**
 *  Add the terms of a taxonomy to a menu
 *
 * @since 20170111
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/NavWalker/Taxonomy.php
 * @link https://gist.github.com/daggerhart/c17bdc51662be5a588c9
 */

class TCC_NavWalker_Taxonomy {

	private $limit    =  0;
	private $maximum  =  10;
	private $menu     = 'primary';
	private $order    = 'DESC';
	private $orderby  = 'count';
	private $position =  0;
	private $taxonomy = 'category';
	private $top_id   =  529876; // hopefully there won't actually be a menu item with this id.  TODO: check this in db

	use TCC_Trait_ParseArgs;


	public function __construct( $args = array() ) {
		$this->parse_args( $args );
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
			'parent'          => 0, // show only top-level terms
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
			require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
			custom_menu_items::add_item( $this->menu, $tax_meta->labels->name, 'javascript: void(0);', $this->position, 0, $this->top_id );
			$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
			$order = 1;
			$width = 0;
			foreach( $terms as $term ) {
				if ( ! ( $this->limit < $term->count ) ) { continue; }
				if ( $order > $this->maximum ) { continue; }
				$name  = sprintf( $pattern, $term->name, $term->count );
				$path  = home_url( '/' ) . 'category/' . $term->slug;
				$width = max( $width, ( strlen( $term->name . $term->count ) + 1 ) );
#fluid()->log( $name, $path, $width );
				custom_menu_items::add_item( $this->menu, $name, $path, $order++, $this->top_id );
			} ?>
			<style>
				.main-navigation ul.sub-menu {
					width: <?php echo round( $width / 4 * 3 ); ?>em;
				}
			</style><?php
		}
	}


}
