<?php
/**
 *  Insert menu items.  Based largely on the link below.
 *
 * @package Fluidity
 * @subpackage Menus
 * @since 20200402
 * @link https://github.com/daggerhart/wp-custom-menu-items
 */
defined('ABSPATH') || exit;


class TCC_NavWalker_Insert {

	/**
	 * @since 20200402
	 * @var array  Menus to be modified.
	 */
	public $menus = array();
	/**
	 * @since 20200402
	 * @var array  Items to be added to the menus.
	 */
	public $items = array();


	/**
	 * @since 20200402
	 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Trait/Singleton.php
	 */
	use TCC_Trait_Singleton;

	/**
	 *  Constructor method.
	 *
	 * @since 20200402
	 */
	private function __construct() {
		if ( ! is_admin() ) {
			add_filter( 'wp_get_nav_menu_items',  [ $this, 'wp_get_nav_menu_items'  ], 11, 3 );
			add_filter( 'wp_get_nav_menu_object', [ $this, 'wp_get_nav_menu_object' ], 11, 2 );
		}
	}


	/**  Menu Items  **/

	/**
	 *  Adds the items to the menus.
	 *
	 * @since 20200402
	 * @param array  $items  array of WP_Post objects.
	 * @param object $menu   instance of a WP_Term object.
	 * @param array  $args   Criteria that wp used to select the menu items.
	 * @return array         The modified list of menu items.
	 */
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

	/**
	 *  Retrieve the items for the current menu.
	 *
	 * @since 20200402
	 * @param  string $slug  Menu slug.
	 * @return array         Items matching the current menu.
	 */
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

	/**
	 *  Convert an item array into an object, with reasonable defaults.
	 *
	 * @since 20200402
	 * @param  array $item  Item to be converted.
	 * @return object       Item as an object.
	 */
	private function item_obj( $item ) {
		$default = array();
		$fields  = [ 'ID', 'title', 'url', 'menu_order', 'menu_item_parent', 'db_id', 'type', 'object', 'object_id', 'target', 'classes', 'attr_title', 'description', 'xfn', 'status' ];
		foreach( $fields as $field ) {
			$default[ $field ] = '';
		}
		$new = array_merge( $default, array_intersect_key( $item, $default ) );
		$new['ID']    = $this->item_ID( $item );
		$new['db_id'] = $new['ID'];
		$new['class'] = [];
		if ( array_key_exists( 'order',  $item ) ) $new['menu_order'] = $item['order'];
		if ( array_key_exists( 'parent', $item ) ) $new['menu_item_parent'] = $item['parent'];
		return (object) $new;
	}

	/**
	 *  Provide an ID for an item.
	 *
	 * @since 20200402
	 * @param  array $item  The item to produce an ID for.
	 * @return int          The generated ID.
	 */
	private function item_ID( $item ) {
		return ( $item['ID'] ) ? $item['ID'] : 1000000 + $item['order'] + $item['parent'];
	}

	/**
	 *  Reorder the items in the menu.
	 *
	 * @since 20200402
	 * @param  array $item  Items to be reordered.
	 * @return array        The reordered items.
	 */
	private function reorder_items( $items ){
		$items = wp_list_sort( $items, 'menu_order' );
		$count = count( $items );
		for( $i = 0; $i < $count; $i++ ){
			$items[ $i ]->menu_order = $i;
		}
		return $items;
	}


	/**  Menu Object  **/

	/**
	 *  Adds the new item to the current menu.
	 *
	 * @since 20200402
	 * @param WP_Term    $menu_obj  The current menu object.
	 * @param int|string $menu      Menu ID or slug.
	 * @return WP_Term              Menu object.
	 */
	public function wp_get_nav_menu_object( $menu_obj, $menu ) {
		if ( ( $menu_obj instanceOf WP_Term ) && array_key_exists( $menu_obj->slug, $this->menus ) ) {
			$menu_obj->count += $this->count_items( $menu_obj->slug );
		}
		return $menu_obj;
	}

	/**
	 *  Counts items for current menu.
	 *
	 * @since 20200402
	 * @param string $slug  The menu slug.
	 * @return int          Item count.
	 */
	private function count_items( $slug ) {
		if ( array_key_exists( $slug, $this->menus ) ) {
			$items = $this->get_items( $slug );
			return count( $items );
		}
		return 0;
	}


}
