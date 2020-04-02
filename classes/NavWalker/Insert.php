<?php
/**
 *  Insert menu items.  Based largely(95%) on the link below.
 *
 * @link https://github.com/daggerhart/wp-custom-menu-items
 */
class TCC_NavWalker_Insert {


	public $menus = array();
	public $items = array();


	use TCC_Trait_Singleton;


	private function __construct() {
		if ( ! is_admin() ) {
			add_filter( 'wp_get_nav_menu_items',  [ $this, 'wp_get_nav_menu_items'  ], 11, 3 );
			add_filter( 'wp_get_nav_menu_object', [ $this, 'wp_get_nav_menu_object' ], 11, 2 );
		}
	}


	/**  Menu Items  **/

	public function wp_get_nav_menu_items( $items, $menu, $args ) {
		if ( array_key_exists( $menu->slug, $this->menus ) ) {
			$news = $this->get_items( $menu->slug );
			if ( $news ) {
				foreach ( $news as $new ) {
					$items[] = $this->item_obj( $new );
				}
			}
			$items = $this->reorder_items( $items );
		}
		return $items;
	}

	private function get_items( $slug ) {
		if ( array_key_exists( $slug, $this->menus ) ) {
			return array_filter(
				$this->items,
				function ( $item ) use ( $slug ) {
					return $item['menu'] === $slug;
				}
			);
		}
		return [];
	}

	private function item_obj( $item ) {
		//  Generic object made to look like a post object.
		$item_obj                   = new stdClass();
		$item_obj->ID               = $this->item_ID( $item );
		$item_obj->title            = $item['title'];
		$item_obj->url              = $item['url'];
		$item_obj->menu_order       = $item['order'];
		$item_obj->menu_item_parent = $item['parent'];
		//  Menu specific properties.
		$item_obj->db_id            = $item_obj->ID;
		$item_obj->type             = ( $item['type'] )      ? $item['type']      : '';
		$item_obj->object           = ( $item['object'] )    ? $item['object']    : '';
		$item_obj->object_id        = ( $item['object_id'] ) ? $item['object_id'] : '';
		//  Output attributes.
		$item_obj->classes          = [];
		$item_obj->target           = '';
		$item_obj->attr_title       = '';
		$item_obj->description      = '';
		$item_obj->xfn              = '';
		$item_obj->status           = '';
		return $item_obj;
	}

	private function item_ID( $item ) {
		return ( $item['ID'] ) ? $item['ID'] : 1000000 + $item['order'] + $item['parent'];
	}

	private function reorder_items( $items ){
		$items = wp_list_sort( $items, 'menu_order' );
		$count = count( $items );
		for( $i = 0; $i < $count; $i++ ){
			$items[ $i ]->menu_order = $i;
		}
		return $items;
	}


	/**  Menu Object  **/

	public function wp_get_nav_menu_object( $menu_obj, $menu ) {
		if ( ( $menu_obj instanceOf WP_Term ) && array_key_exists( $menu_obj->slug, $this->menus ) ) {
			$menu_obj->count += $this->count_items( $menu_obj->slug );
		}
		return $menu_obj;
	}

	private function count_items( $slug ) {
		if ( array_key_exists( $slug, $this->menus ) ) {
			$items = $this->get_items( $slug );
			return count( $items );
		}
		return 0;
	}


}
