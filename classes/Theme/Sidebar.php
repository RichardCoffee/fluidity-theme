<?php


class TCC_Theme_Sidebar {

	protected static $sidebar = null;

	use TCC_Trait_Singleton;

	protected function __construct( $args = array() ) {
		if ( ! $sidebar ) {
			$args = $this->check_args( $args );
			self::$sidebar = new TCC_Widget_Sidebar( $args );
		}
	}

	private function check_args( $args ) {
		if ( ! isset( $args['sidebar'] ) ) {
			$available = $GLOBALS['wp_registered_sidebars'];
log_entry($available);
		}
	}

}
