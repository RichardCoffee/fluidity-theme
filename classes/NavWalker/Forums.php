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
	 * @see TCC_NavWalker_Dynamic::$slug
	 */
	protected $slug = 'forums';

	/**
	 *  constructor function.
	 *
	 * @since 20180905
	 * @param array $args Optional.  Associative array, whose only valid indexes are
	 *                    existing class properties, with additional class properties
	 *                    found in TCC_NavWalker_Dynamic. All other indexes will be ignored.
	 * @see TCC_NavWalker_Dynamic::__constructer()
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );
		if ( ! is_callable( 'bbpress' ) ) { return; }
		$forums = $this->get_forums();
		$counts = $this->get_forum_counts( $forums );
fluid()->log( 'forums', $forums, $counts );
		$this->add_forums( $forums );
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
	 */
	protected function add_forums( $forums ) {
		$title = ( empty( $this->title ) ) ? $this->get_forums_title() : $this->title;
		$this->add_menu_item( $title );
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


}
