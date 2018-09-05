<?php
/**
 *  Add a dynamic sub-menu to a wordpress menu
 *
 * @package Fluidity
 * @subpackage Menus
 * @since 20180905
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/NavWalker/Dynamic.php
 * @link https://gist.github.com/daggerhart/c17bdc51662be5a588c9
 */
defined( 'ABSPATH' ) || exit;
/**
 *  serves as an abstract class for dynamic sub-menus
 */
abstract class TCC_NavWalker_Dynamic {

	protected $limit    =  0;
	protected $link     = 'javascript: void(0);';
	protected $maximum  =  10;
	protected $menu     = 'primary';
	protected $order    = 'DESC';
	protected $orderby  = 'count';
	protected $position =  0;
	protected $slug     = 'dynamic';
	protected $top_id   =  529876; // just a large number
	protected $title    = '';
	protected $width    =  1;

	use TCC_Trait_ParseArgs;


	public function __construct( $args = array() ) {
		require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
		$this->top_id += mt_rand( 1, $this->top_id );
		$this->parse_args( $args );
		add_action( 'fluid_custom_css', [ $this, 'fluid_custom_css' ] );
	}

	protected function item_defaults() {
		return array(
			'menu'   => $this->menu,
			'title'  => '',
			'url'    => $this->link,
			'order'  => $this->position,
			'parent' => 0,
			'ID'     => $this->top_id,
			'type'   => $this->slug,
		);
	}

	protected function add_menu_item( $title ) {
		$item = $this->item_defaults();
		$item['title'] = $title;
		$this->add_item( $item );
	}

	protected function sub_menu_loop( $items ) {
		$order = 1;
		foreach( $items as $item ) {
			if ( ! ( $this->limit < $item['count'] ) ) { continue; }
			if ( $order > $this->maximum ) { break; }
			$this->add_sub_menu_item( $item['name'], $item['path'], $order++ );
		}
	}

	protected function add_sub_menu_item( $name, $path, $order ) {
		custom_menu_items::add_item( $this->menu, $name, $path, $order, $this->top_id );
/*		$item = array_merge(
			$this->item_defaults(),
			array(
				'title'  => $name,
				'url'    => $path,
				'order'  => $order,
				'parent' => $this->top_id,
			)
		);
		$this->add_item( $item ); //*/
	}

	protected function add_item( $item ) {
		$slug   = $item['menu'];
		$custom = custom_menu_items::get_instance();
		$custom->menus[ $slug ] = $slug;
		$custom->menu_items[] = $item;
	}

	public function fluid_custom_css() {
		$width = round( $this->width / 4 * 3 );
		echo "\n.main-navigation ul.sub-menu {\n\twidth: {$width}em;\n}";
	}

	/**
	 *  add taxonomy name to top level menu, and taxonomy terms as submenu
	 *
	 * @since 20180916
	 * @param array $terms
	 *//*
	public function add_terms( $terms ) {
		$tax_meta = get_taxonomy( $this->taxonomy );
		if ( $tax_meta ) {
			require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
			$title = ( empty( $this->title ) ) ? $tax_meta->labels->name : $this->title;
			custom_menu_items::add_item( $this->menu, $title, $this->link, $this->position, 0, $this->top_id );
			$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
			$order = 1;
			$width = 0;
			foreach( $terms as $term ) {
				if ( ! ( $this->limit < $term->count ) ) { continue; }
				if ( $order > $this->maximum ) { continue; }
				$name  = sprintf( $pattern, $term->name, $term->count );
				$path  = home_url( '/' ) . 'category/' . $term->slug;
				$width = max( $width, ( strlen( $term->name . $term->count ) + 3 ) );
#fluid()->log( $name, $path, $width );
				custom_menu_items::add_item( $this->menu, $name, $path, $order++, $this->top_id );
			} ?>
			<style>
				.main-navigation ul.sub-menu {
					width: <?php echo round( $width / 4 * 3 ); ?>em;
				}
			</style><?php
		}
	} //*/


}
