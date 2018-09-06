<?php
/**
 *  Add bbPress forums as a dynamic sub-menu to a wordpress menu
 *
 * @package Fluidity
 * @subpackage bbPress
 * @since 20180905
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/NavWalker/Forums.php
 */
defined( 'ABSPATH' ) || exit;
/*
 */
class TCC_NavWalker_Forums extends TCC_NavWalker_Dynamic {

	/**
	 *  string used as postfix for css selector.
	 *
	 * @since 20180905
	 * @var string
	 * @see TCC_NavWalker_Dynamic::$type
	 */
	protected $type = 'forum';

	/**
	 *  constructor function.
	 *
	 * @since 20180905
	 * @param array $args Optional.  Associative array, whose only valid indexes are
	 *                    existing class properties, with additional class properties
	 *                    found in TCC_NavWalker_Dynamic. All other indexes will be ignored.
	 * @see TCC_NavWalker_Dynamic::__constructer()
	 * @uses home_url()
	 * @uses TCC_Theme_Library::bbp_get_form_option()
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );
		if ( ! is_callable( 'bbpress' ) ) { return; }
		$this->link .= fluid()->bbp_get_form_option( '_bbp_root_slug', 'forum', true );
		$forums = $this->get_forums();
		$counts = $this->get_forum_counts( $forums );
#fluid()->log( 'forums', $forums, $counts );
		$this->add_forums( $forums );
		$this->check_queried_object();
	}

	/**
	 *  get all public forums.
	 *
	 * @since 20180905
	 * @uses get_posts()
	 * @return array
	 */
	protected function get_forums() {
		$args = array(
			'post_type'           => bbp_get_forum_post_type(),
			'post_status'         => bbp_get_public_status_id(),
			'ignore_sticky_posts' => true,
			'orderby'             => 'menu_order title',
			'hide_empty'          => false,
		);
		return get_posts( $args );
	}

	/**
	 *  get topic counts for forums.
	 *
	 * @since 20180905
	 * @param array $forums An array of WP_Post objects.
	 * @uses bbp_get_forum_topic_count()
	 * @return array Array of WP_Post objects with a topic_count property added.
	 */
	protected function get_forum_counts( $forums ) {
#		$counts = array();
		foreach( $forums as &$forum ) {
			$forum->topic_count = bbp_get_forum_topic_count( $forum->ID );
#			$counts[] = bbp_get_forum_topic_count( $forum->ID );
		}
		return $forums;
#		return $counts;
	}

	/**
	 *  add forums as main menu item.
	 *
	 * @since 20180906
	 * @param array $forums
	 * @uses fluid()
	 * @uses TCC_Trait_Attributes::get_element()
	 * @uses bbp_get_forum_permalink()
	 */
	protected function add_forums( $forums ) {
		$order   = 1;
		$pattern = '%1$s ' . fluid()->get_element( 'span', [ 'class' => 'term-count' ], '%2$s' );
		$title   = ( empty( $this->title ) ) ? $this->get_forums_title() : $this->title;
		$this->add_menu_item( $title );
		foreach( $forums as $forum ) {
			$name = sprintf( $pattern, $forum->post_title, $forum->topic_count );
			$path = bbp_get_forum_permalink( $forum->ID );
			$this->add_sub_menu_item( $name, $path, $order++, 'forum' );
			$this->width = max( $this->width, ( strlen( $forum->post_title . $forum->topic_count ) + 3 ) );
		}
	}

	/**
	 *  retrieve the forum title string.
	 *
	 * @since 20180905
	 * @uses bbp_get_forum_post_type_labels()
	 * @return string String bbPress as a menu title.
	 */
	protected function get_forums_title() {
		$labels = bbp_get_forum_post_type_labels();
		return $labels['menu_name'];
	}

	protected function check_queried_object() {
		$object = get_queried_object();
		if ( ( $object instanceof WP_Post_Type ) && ( $object->name === $this->type ) ) {
			add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 100, 4 );
		}
	}

	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
		fluid()->log( $classes, $item, $args, $depth );
		return $classes;
	}


}
