<?php


class TCC_Theme_Sidebar {

	private $main_css    = '';
	private $sidebar_css = 'col-md-3';

	protected $action     = 'tcc_before_main';
	protected $mobile     = 'tcc_after_main';
	protected $css        =  array();
	protected $horizontal =  false;
	protected $position   = 'none';
	protected $root       = 'sidebar';
	protected $sidebar    = 'standard';
	protected $slug;

	use TCC_Trait_ParseArgs;
	use TCC_Trait_Singleton;

	private function __construct( $args = array() ) {

		if ( defined( 'TCC_NO_SIDEBAR' ) ) {
			static::$abort__construct = true;
			return;
		}
		$this->position = $this->positioning();
		$this->sidebar  = get_page_slug();
		$this->slug     = $this->sidebar;
		$this->css      = ( tcc_layout( 'fluid_sidebar', 'no' ) === 'no' ) ? tcc_layout( 'sidebar_css', $this->sidebar_css ) : '';

		$args = apply_filters( 'fluid_theme_sidebar_args', $args );
		if ( $this->position === 'none' ) {
			static::$abort__construct = true;
			return;
		}
		$this->parse_args( $args );
		if ( ! $this->horizontal ) {
			$this->check_mobile();
		}
		if ( empty( $this->action ) ) {
			static::$abort__construct = true;
			return;
		}
		add_action( $this->action, array( $this, 'show_sidebar' ) );
	}

	private function check_mobile() {
		if ( fluid()->is_mobile() ) {
			$mobile = tcc_layout( 'mobile_sidebar', 'bottom' );
			if ( $mobile === 'none' ) {
				$this->action = false;
			} else if ( $mobile === 'bottom' ) {
				$this->action = 'tcc_after_main';
			}
		} else if ( tcc_layout( 'sidebar', 'left' ) === 'right' ) {
			$this->action = 'tcc_after_main';
		}
	}

	protected function positioning() {
		if ( defined( 'TCC_NO_SIDEBAR' ) ) {
			return 'none';
		}
		$side = tcc_layout( 'sidebar', 'left' );
		if ( defined( 'TCC_LEFT_SIDEBAR'  ) ) { $side = 'left';  }
		if ( defined( 'TCC_RIGHT_SIDEBAR' ) ) { $side = 'right'; }
		return $side;
	}

	public function show_sidebar() {
		$attrs = array(
			'class' => $this->build_class(),
			'role'  => 'complementary',
		);
		$attrs = array_merge( $attrs, microdata()->microdata_attrs( 'WPSideBar' ) ); ?>
		<div <?php fluid()->apply_attrs( $attrs ); ?>>
			<?php get_template_part( $this->root, $this->sidebar ); ?>
		</div><?php
	}

	protected function build_class() {
		$css = array(
			'fluid-sidebar',
			'fluid-sidebar-' . $this->position,
			'noprint',
			'pull-' . $this->position,
			'widget-area',
		);
		if ( $this->slug ) {
			$css[] = 'fluid-sidebar-' . $this->slug;
		}
		if ( $this->slug !== $this->sidebar ) {
			$css[] = 'fluid-sidebar-' . $this->sidebar;
		}
		return array_unique( array_merge( $css, ( ( is_array( $this->css ) ) ? $this->css : explode( ' ', $this->css ) ) ) );
	}


}
