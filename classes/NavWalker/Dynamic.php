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
 * @link https://www.daggerhart.com/dynamically-add-item-to-wordpress-menus/
 * @link https://gist.github.com/daggerhart/c17bdc51662be5a588c9
 */
defined( 'ABSPATH' ) || exit;
/**
 *  serves as an abstract class for dynamic sub-menus
 *
 * @since 20180905
 */
abstract class TCC_NavWalker_Dynamic {

	/**
	 *  number used as lower count limit when displaying submenu items.
	 *
	 * @since 20180905
	 * @var int
	 */
	protected $limit = 0;
	/**
	 *  default url link used for main menu item.
	 *
	 * @since 20180905
	 * @var string
	 */
	protected $link = 'javascript: void(0);';
	/**
	 *  maximum number of submenu items to display.
	 *
	 * @since 20180905
	 * @var int
	 */
	protected $maximum = 6;
	/**
	 *  default menu to add items to.
	 *
	 * @since 20180905
	 * @var string
	 */
	protected $menu = 'primary-menu';
	/**
	 *  default position in menu to add main menu item.
	 *
	 * @since 20180905
	 * @var string
	 */
	protected $position = 10;
	/**
	 *  default string used as css postfix for menu item.
	 *
	 * @since 20180905
	 * @var string
	 */
	protected $type = 'dynamic';
	/**
	 *  default id used for the main menu item.
	 *
	 * @since 20180905
	 * @var int
	 */
	protected $top_id = 529876; // just a large number
	/**
	 *  used as the text for the main menu item.
	 *
	 * @since 20180905
	 * @var string
	 */
	protected $title = '';
	/**
	 *  used for the width for the inserted css selector.
	 *
	 * @since 20180905
	 * @var int
	 */
	protected $width = 1;

	/**
	 *  instance of custom_menu_item class.
	 *
	 * @since 20180905
	 * @var string
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/vendor/custom-menu-items.php
	 */
	static private $custom = null;

	/**
	 *  trait containing functions for parsing an associative array's data into a class's properties.
	 *
	 * @since 20180905
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/ParseArgs.php
	 */
	use TCC_Trait_ParseArgs;

	/**
	 *  constructor function.
	 *
	 * @since 20180905
	 * @param array $args Optional.  Associative array, whose only valid indexes are
	 *                    existing class properties. All other indexes will be ignored.
	 * @uses TCC_Trait_ParseArgs::parse_args()
	 */
	public function __construct( $args = array() ) {
		require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
		self::$custom = custom_menu_items::get_instance();
		$this->top_id += mt_rand( 1, $this->top_id );
		$this->link    = home_url( '/' );
		$this->maximum = apply_filters( 'fluid_dynamic_submenu_maximum', $this->maximum );
		$this->parse_args( $args );
		add_action( 'fluid_custom_css',   [ $this, 'fluid_custom_css' ] );
#		add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 100, 4 );
	}

	/**
	 *  creates an associative array containing all required default indexes for menu items.
	 *
	 * @since 20180905
	 * @return array
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
	 * @param string $title
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
	 *  used as a default loop for adding submenu items.
	 *
	 * @since 20180905
	 * @param array $items an array of associative arrays of items to be
	 *                     displayed.  Minimum required indexes are 'count',
	 *                     'name', and 'path'.  Array should be pre-sorted.
	 * @uses TCC_Trait_Attributes::get_element()
	 */
	protected function sub_menu_loop( $items ) {
		$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
		$order   = 1;
		foreach( $items as $item ) {
			if ( ! ( $this->limit < $item['count'] ) ) { break; }
			if ( $order > $this->maximum ) { break; }
			$name = sprintf( $pattern, $item['name'], $item['count'] );
			$this->width = max( $this->width, ( strlen( $item['name'] . $item['count'] ) + 3 ) );
			$this->add_sub_menu_item( $name, $item['path'], $order++ );
		}
	}

	/**
	 *  add a submenu item.
	 *
	 * @since 20180905
	 * @param string $name
	 * @param string $path
	 * @param int $order
	 * @param string $type
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
	 * @param array $item
	 */
	protected function add_item( $item ) {
		$slug = $item['menu'];
		self::$custom->menus[ $slug ] = $slug;
		self::$custom->menu_items[] = $item;
	}

	/**
	 *  add css to control the width of the submenu items
	 *
	 * @since 20180905
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/header.php::fluid_custom_css()
	 */
	public function fluid_custom_css() {
		$width = round( $this->width / 4 * 3 );
		echo "\nli.menu-item-type-{$this->type} ul.sub-menu {\n\twidth: {$width}em;\n}";
	}

	/**
	 *  hooks the wp nav_menu_css_class filter
	 *
	 * @since 20180906
	 */
	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
		fluid()->log( $classes, $item, $args, $depth );
		return $classes;
	}


}
