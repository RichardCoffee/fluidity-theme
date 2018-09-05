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
/**
 */
class TCC_NavWalker_Forums extends TCC_NavWalker_Dynamic {

	protected $slug = 'forums';


	public function __construct( $args = array() ) {
		parent::__construct( $args );
		if ( ! is_callable( 'bbpress' ) ) { return; }
		$this->title = $this->get_forums_title();
		$forums = $this->get_forums();
		$forums = $this->get_forum_counts( $forums );
fluid()->log( 'forums', $forums );
	}

	protected function get_forums_title() {
		$labels = bbp_get_forum_post_type_labels();
		return $labels['menu_name'];
	}

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

	protected function get_forum_counts( $forums ) {
		foreach( $forums as &$forum ) {
			$forum->comment_count = bbp_get_forum_topic_count( $forum->ID );
		}
	}


}
