<?php
/**
 *  Serves as an abstract class for adding a dynamic sub-menu to a wordpress menu.
 *
 * @package Fluidity
 * @subpackage Menus
 * @since 20180905
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/NavWalker/Dynamic.php
 * @link https://www.daggerhart.com/dynamically-add-item-to-wordpress-menus/
 * @link https://github.com/daggerhart/wp-custom-menu-items
 */
defined( 'ABSPATH' ) || exit;



abstract class TCC_NavWalker_Dynamic {


	/**
	 * @since 20200401
	 * @var object  Point to instance of TCC_NavWalker_Insert.
	 */
	protected $insert = null;
	/**
	 * @since 20180905
	 * @var int  Number used as lower count limit when displaying submenu items.
	 */
	protected $limit = 0;
	/**
	 * @since 20180905
	 * @var string  Default url link used for main menu item.
	 */
	protected $link = 'javascript: void(0);';
	/**
	 * @since 20180905
	 * @var int  Maximum number of submenu items to display.
	 */
	protected $maximum = 6;
	/**
	 * @since 20180905
	 * @var string  Default menu to add items to.
	 */
	protected $menu = 'primary-menu';
	/**
	 * @since 20180905
	 * @var string  Default position in menu to add main menu item.
	 */
	protected $position = 10;
	/**
	 * @since 20180905
	 * @var string  Default string used as css postfix for menu item.
	 */
	protected $type = 'dynamic';
	/**
	 * @since 20180905
	 * @var int  Default id used for the main menu item.
	 */
	protected $top_id = 529876; // just a large number
	/**
	 * @since 20180905
	 * @var string  Used as the text for the main menu item.
	 */
	protected $title = '';
	/**
	 * @since 20180905
	 * @var int  Used for the width for the inserted css selector.
	 */
	protected $width = 1;


	/**
	 * @since 20180905
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/ParseArgs.php
	 */
	use TCC_Trait_ParseArgs;

	/**
	 *  constructor function.
	 *
	 * @since 20180905
	 * @param array $args  Associative array, whose only valid indexes are existing class properties. All other indexes will be ignored.
	 * @uses TCC_Trait_ParseArgs::parse_args()
	 */
	public function __construct( $args = array() ) {
		require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
		$this->insert  = TCC_NavWalker_Insert::instance();
		$this->link    = home_url( '/' );
		$this->top_id += mt_rand( 1, $this->top_id );
		$this->parse_args( $args );
		$this->maximum = apply_filters( 'fluid_dynamic_submenu_maximum', $this->maximum );
		add_action( 'fluid_custom_css',   [ $this, 'fluid_custom_css' ] );
#		add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 100, 4 );
	}

	/**
	 *  creates an associative array containing all required default indexes for menu items.
	 *
	 * @since 20180905
	 * @return array  Default value for a menu item.
	 */
	protected function item_defaults() {
		return array(
			'menu'   => $this->menu,
			'title'  => '',
			'url'    => $this->link,
			'order'  => $this->position,
			'parent' => 0,
			'ID'     => 0,
		);
	}

	/**
	 *  add a main menu item.
	 *
	 * @since 20180905
	 * @param string $title  Title for the menu.
	 */
	protected function add_menu_item( $title ) {
		$item = array_merge(
			$this->item_defaults(),
			array(
				'title' => $title,
				'type'  => $this->type,
				'ID'    => $this->top_id,
			)
		);
		$this->add_item( $item );
	}

	/**
	 *  Add a main menu object.
	 *
	 * @since 20180906
	 * @param string $title
	 * @param object $object
	 * @param int    $object_id
	 */
	protected function add_menu_object( $title, $object, $object_id ) {
		$item = array_merge(
			$this->item_defaults(),
			array(
				'title'  => $title,
				'type'   => $this->type,
				'ID'     => $this->top_id,
				'object' => $object,
				'object_id' => $object_id,
			)
		);
		$this->add_item( $item );
	}

	/**
	 *  Used as a default loop for adding submenu items.
	 *
	 * @since 20180905
	 * @param array $items  Items to be displayed.  Minimum required indexes are 'count', 'name', and 'path'.  Array should be pre-sorted.
	 * @uses TCC_Trait_Attributes::get_element()
	 *//*
	protected function sub_menu_loop( $items ) {
		$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
		$order   = 1;
		foreach( $items as $item ) {
			if ( ! ( $this->limit < $item['count'] ) ) { break; }
			if ( $order > $this->maximum ) { break; }
			$name = sprintf( $pattern, $item['name'], $item['count'] );
			$this->width = max( $this->width, ( strlen( $item['name'] . $item['count'] ) + 3 ) );
			$this->add_sub_menu_item( $name, $item['path'], $order++, $this->type );
		}
	} //*/

	/**
	 *  Add a submenu item.
	 *
	 * @since 20180905
	 * @param string $name   Item title.
	 * @param string $path   Link URL.
	 * @param int    $order  Order to appear in the submenu.
	 * @param string $type   Item type identifier.
	 */
	protected function add_sub_menu_item( $name, $path, $order, $type ) {
		$item = array_merge(
			$this->item_defaults(),
			array(
				'title'  => $name,
				'url'    => $path,
				'order'  => $order,
				'parent' => $this->top_id,
				'type'   => $type,
			)
		);
		$this->add_item( $item ); //*/
	}

	/**
	 *  add a menu item, used for both main and submenu items.
	 *
	 * @since 20180905
	 * @param array $item  Item to be added.
	 */
	protected function add_item( $item ) {
		$slug = $item['menu'];
		$this->insert->menus[ $slug ] = $slug;
		$this->insert->items[] = $item;
	}

	/**
	 *  add css to control the width of the submenu items
	 *
	 * @since 20180905
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/header.php:fluid_custom_css()
	 */
	public function fluid_custom_css() {
		$select = "li.menu-item-type-{$this->type} ul.sub-menu";
		$select = apply_filters( 'fluid_menu_item_selector', $select, $this->type );
		$width  = round( $this->width / 4 * 3 );
		$width  = apply_filters( 'fluid_menu_item_width', $width, $this->type );
		printf( '%s { width: %dem; }', $select, $width );
	}

	/**
	 *  hooks the wp nav_menu_css_class filter
	 *
	 * @since 20180906
	 *//*
	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
#		fluid()->log( $classes, $item, $args, $depth );
		return $classes;
	} //*/


}
