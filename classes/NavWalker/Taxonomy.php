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

	/**
	 *  default order that terms are retrieved in.
	 *
	 * @since 20180816
	 * @var string
	 */
	protected $order = 'DESC';
	/**
	 *  default field used to order terms.
	 *
	 * @since 20180816
	 * @var string
	 */
	protected $orderby = 'count';
	/**
	 *  default parent id.
	 *
	 * @since 20180816
	 * @var int
	 */
	protected $parent =  0;
	/**
	 *  default string used as css postfix for menu item.
	 *
	 * @since 20180816
	 * @var string
	 * @see TCC_NavWalker_Dynamic::$type
	 */
	protected $type = 'navtax';
	/**
	 *  default taxonomy used for submenu items.  Also used as css postfix for submenu items.
	 *
	 * @since 20180816
	 * @var string
	 */
	protected $taxonomy = 'category';

	/**
	 *  constructor function.
	 *
	 * @since 20180905
	 * @param array $args Optional.  Associative array, whose only valid indexes are
	 *                    existing class properties, with additional class properties
	 *                    found in TCC_NavWalker_Dynamic. All other indexes will be ignored.
	 * @uses TCC_NavWalker_Dynamic::__constructer()
	 * @uses TCC_Trait_Logging::log()
	 */
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
	 * @uses TCC_Trait_Attributes::get_element()
	 */
	public function add_terms( $terms ) {
		$tax_meta = get_taxonomy( $this->taxonomy );
		if ( $tax_meta ) {
			$title = ( empty( $this->title ) ) ? $tax_meta->labels->name : $this->title;
			$this->add_menu_item( $title );
			$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
			$order = 1;
			foreach( $terms as $term ) {
				if ( ! ( $this->limit < $term->count ) ) { break; }
				if ( $order > $this->maximum ) { break; }
				$name  = sprintf( $pattern, $term->name, $term->count );
				$path  = home_url( '/' ) . 'category/' . $term->slug;
				$this->width = max( $this->width, ( strlen( $term->name . $term->count ) + 3 ) );
				$this->add_sub_menu_item( $name, $path, $order++, $this->taxonomy );
			}
		}
	}


}
