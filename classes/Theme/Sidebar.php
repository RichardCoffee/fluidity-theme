<?php


class TCC_Theme_Sidebar {


	private $main_css    = '';
	private $sidebar_css = '';
	private $sidebar     = null;

	use TCC_Trait_Singleton;

	private function __construct( $args = array() ) {
		if ( ! $this->sidebar ) {
			$args = $this->check_args( $args );
			$this->sidebar = new TCC_Widget_Sidebar( $args );
log_entry($this);
		}
	}

	private function check_args( $args ) {
		$defaults = array(
			'action'     => 'tcc_before_main',
			'css'        => tcc_layout( 'sidebar_css' ),
			'horizontal' => false,
			'position'   => tcc_layout( 'sidebar' ),
			'sidebar'    => get_page_slug(),
		);
		$args = array_merge( $defaults, $args );
		$args['action'] = $this->action_position( $args );
		$args['css']    = $this->bootstrap_css(   $args );
#$available = $GLOBALS['wp_registered_sidebars'];
#log_entry($available);
		return $args;
	}

	private function action_position( $args ) {
		$action = $args['action'];
		if ( ! $args['horizontal'] && ( $args['position'] !== 'none' ) ) {
			if ( $this->is_mobile() ) {
				$mobile = tcc_layout( 'mobile_sidebar' );
				if ( $mobile === 'none' ) {
					$action = false;
				} else if ( $mobile === 'bottom' ) {
					$action = 'tcc_after_main';
				}
			} else if ( ( tcc_layout( 'sidebar' ) === 'right' ) && $this->css ) {
				$action = 'tcc_after_main';
			}
		}
		return $action;
	}

	private function bootstrap_css( $bootstrap ) {
		$defaults = array(
			'lg' => 3,
			'md' => 3,
			'sm' => 12,
			'xs' => 12,
		);
		$main    = array();
		$sidebar = array();
		$parsed  = wp_parse_args( $bootstrap, $defaults );
		foreach( $parsed as $col => $num ) {
			$sidebar[] = "col-$col-$num";
			if ( $num < 12 ) {
				$main[] = "col-$col-" . ( 12 - $num );
			} else {
				$main[] = "col-$col-12";
			}
		}
		$this->main_css    = join( ' ', $main );
		$this->sidebar_css = join( ' ', $sidebar );
		add_filter( 'tcc_main_tag_css', function( $css ) { return $this->main_css; });
		return $this->sidebar_css;
	}

	private function is_mobile() {
		#| Use mobble plugin if present
		if ( class_exists( 'Mobile_Detect' ) ) {
			$mobile = is_mobile();
		} else {
			$mobile = wp_is_mobile();
		}
		return $mobile;
	}

}
