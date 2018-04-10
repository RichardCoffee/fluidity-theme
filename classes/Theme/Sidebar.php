<?php


class TCC_Theme_Sidebar {

	private $main_css    = '';
	private $sidebar_css = '';
	private $sidebar     = null;

	use TCC_Trait_Singleton;

	private function __construct( $args = array() ) {
		if ( ! $this->sidebar ) { // FIXME: wtf? this will always fail
			$args = $this->check_args( $args );
			if ( ( $args['position'] !== 'none' ) && $args['action'] ) {
fluid()->log($args);
				$this->sidebar = new TCC_Widget_Sidebar( $args );
			}
		}
	}

	private function check_args( $args ) {
		$defaults = array(
			'action'     => 'tcc_before_main',
			'css'        => ( tcc_layout( 'fluid_sidebar', 'no' ) === 'no' ) ? tcc_layout( 'sidebar_css', 'col-md-3' ) : '',
			'horizontal' => false,
			'position'   => $this->positioning(),
			'sidebar'    => get_page_slug(),
		);
		$args = array_merge( $defaults, $args );
		$args = apply_filters( 'tcc_theme_sidebar_args', $args );
		$args['action'] = $this->action_position( $args );
		return $args;
	}

	private function action_position( $args ) {
		$action = $args['action'];
		if ( ! $args['horizontal'] && ( $args['position'] !== 'none' ) ) {
			if ( $this->is_mobile() ) {
				$mobile = tcc_layout( 'mobile_sidebar', 'bottom' );
				if ( $mobile === 'none' ) {
					$action = false;
				} else if ( $mobile === 'bottom' ) {
					$action = 'tcc_after_main';
				}
			} else if ( ( tcc_layout( 'sidebar', 'left' ) === 'right' ) && $args['css'] ) {
				$action = 'tcc_after_main';
			}
		}
		return $action;
	}

	protected function positioning() {
		$side = 'none';
		if ( ! defined( 'TCC_NO_SIDEBAR' ) ) {
			$side = tcc_layout( 'sidebar', 'left' );
		}
		if ( defined( 'TCC_LEFT_SIDEBAR'  ) ) { $side = 'left';  }
		if ( defined( 'TCC_RIGHT_SIDEBAR' ) ) { $side = 'right'; }
		return $side;
	}

	private function is_mobile() {
		# Use mobble plugin if present
		if ( class_exists( 'Mobile_Detect' ) ) {
			$mobile = is_mobile();
		} else {
			$mobile = wp_is_mobile();
		}
		return $mobile;
	}

}
