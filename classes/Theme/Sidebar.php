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
	protected $fluid      = 'no';
	protected $slug;

	use TCC_Trait_ParseArgs;
	use TCC_Trait_Singleton;

	private function __construct( $args = array() ) {

		$this->position = $this->positioning();
		if ( defined( 'TCC_NO_SIDEBAR' ) || ( $this->position === 'none' ) ) {
			static::$abort__construct = true;
			return;
		}

		$this->sidebar = get_page_slug();
		$this->slug    = $this->sidebar;
		$this->fluid   = get_theme_mod( 'sidebar_fluidity', 'no' );
		$this->css     = ( $this->fluid === 'no' ) ? tcc_layout( 'sidebar_css', $this->sidebar_css ) : '';

		$args = apply_filters( 'fluid_theme_sidebar_args', $args );
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
			$mobile = get_theme_mod( 'sidebar_mobile', 'bottom' );
			if ( $mobile === 'none' ) {
				$this->action = false;
			} else if ( $mobile === 'bottom' ) {
				$this->action = 'tcc_after_main';
			}
		} else if ( ( $this->fluid === 'no' ) && get_theme_mod( 'sidebar_position', 'right' ) === 'right' ) {
			$this->action = 'tcc_after_main';
		}
	}

	protected function positioning() {
		$side = get_theme_mod( 'sidebar_position', 'right' );
		if ( defined( 'TCC_LEFT_SIDEBAR'  ) ) { $side = 'left';  }
		if ( defined( 'TCC_RIGHT_SIDEBAR' ) ) { $side = 'right'; }
		$side = apply_filters( 'fluid_theme_sidebar_positioning', $side );
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
			'noprint',
			'widget-area',
		);
		if ( $this->fluid === 'yes' ) {
			$css[] = 'fluid-sidebar';
			$css[] = 'fluid-sidebar-' . $this->position;
			$css[] = 'pull-' . $this->position;
		}
		if ( $this->slug ) {
			$css[] = 'fluid-sidebar-' . $this->slug;
		}
		if ( $this->slug !== $this->sidebar ) {
			$css[] = 'fluid-sidebar-' . $this->sidebar;
		}
		return array_unique( array_merge( $css, ( ( is_array( $this->css ) ) ? $this->css : explode( ' ', $this->css ) ) ) );
	}


}
