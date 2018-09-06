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

	static private $custom = null;

	use TCC_Trait_ParseArgs;


	public function __construct( $args = array() ) {
		require_once( FLUIDITY_HOME . 'vendor/custom-menu-items.php' );
		self::$custom = custom_menu_items::get_instance();
		$this->top_id += mt_rand( 1, $this->top_id );
		$this->parse_args( $args );
		add_action( 'fluid_custom_css',   [ $this, 'fluid_custom_css' ] );
#		add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 100, 4 );
	}

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

	protected function add_menu_item( $title ) {
		$item = array_merge(
			$this->item_defaults(),
			array(
				'title' => $title,
				'type'  => $this->slug,
				'ID'    => $this->top_id,
			)
		);
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

	protected function add_item( $item ) {
		$slug = $item['menu'];
		self::$custom->menus[ $slug ] = $slug;
		self::$custom->menu_items[] = $item;
	}

	public function fluid_custom_css() {
		$width = round( $this->width / 4 * 3 );
		echo "\nli.menu-item-type-{$this->slug} ul.sub-menu {\n\twidth: {$width}em;\n}";
	}

	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
		fluid()->log( $classes, $item, $args, $depth );
		return $classes;
	}


}
