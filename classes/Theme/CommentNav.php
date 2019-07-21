<?php
/**
 *  Supplies comment navigation functionality.
 *
 * @package Fluidity
 * @subpackage Comments
 * @since 20190721
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2019, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/CommentNav.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Intended for use on commant forms only
 *
 */
class TCC_Theme_CommentNav extends TCC_Theme_Navigation {

	/**
	 *  CSS class assigned to <nav> element.
	 *
	 * @since 20170510
	 * @var string
	 */
	protected $nav_css = 'comment-navigation';
	/**
	 *  CSS class assigned to <ul> element.
	 *
	 * @since 20170510
	 * @var string
	 */
	protected $ul_css = 'pager pager-comments';

	/**
	 *  Construction method for class.
	 *
	 * @since 20190721
	 * @param array $args Optional.  Associative array, whose only valid indexes are
	 *                    existing class properties. All other indexes will be ignored.
	 * @uses TCC_Trait_ParseArgs::parse_args()
	 */
	public function __construct( $args = array() ) {
		if ( ( get_comment_pages_count() > 1 ) && get_option( 'page_comments' ) ) {
			$this->navigation_text();
			$this->parse_args( $args );
			$this->generate_navigation();
		}
	}

	/**
	 *  Assign all text used for comment navigation.
	 *
	 * @since 20190721
	 */
	protected function navigation_text() {
		$this->newer_link = esc_html__( 'Newer Comments', 'tcc-fluid' );
		$this->older_link = esc_html__( 'Older Comments', 'tcc-fluid' );
		$this->sr_text    = esc_html__( 'Comment navigation', 'tcc-fluid' );
	}

	/**
	 *  Provide for forward and reverse orientation of the navigation buttons.
	 *
	 * @since 20190721
	 * @return array
	 */
	protected function get_orientation() {
		$orient = array(
			$right => array(
				'task'   => 'next',
				'class'  => 'next ' . $this->li_css,
				'format' => $this->right,
				'text'   => $this->newer_link,
			),
			$left => array(
				'task'   => 'previous',
				'class'  => 'previous ' . $this->li_css,
				'format' => $this->left,
				'text'   => $this->older_link,
			),
		);
		if ( $this->orientation === 'reverse' ) {
			$orient['right']['class' ] = 'previous ' . $this->li_css;
			$orient['right']['format'] = $this->left;
			$orient['left' ]['class' ] = 'next ' . $this->li_css;
			$orient['left' ]['format'] = $this->right;
		}
		return $orient;
	}

	/**
	 *  Show the navigation buttons.
	 *
	 * @since 20190721
	 * @param array $data Contains information for showing the buttons.
	 */
	protected function show_link( $data ) {
		$text = str_replace( '%title', esc_html( $data['text'] ), $data['format'] );
		$this->tag( 'li', [ 'class' => $data['class'] ] );
			if ( $data['task'] === 'next' ) {
				next_comments_link( $text );
			} else {
				previous_comments_link( $text );
			} ?>
		</li><?php
	}


}
