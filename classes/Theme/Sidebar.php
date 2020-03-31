<?php


class TCC_Theme_Sidebar {

	private $main_css    = 'col-lg-9 col-md-9 col-sm-12 col-xs-12';
	private $sidebar_css = 'col-lg-3 col-md-3 col-sm-12 col-xs-12';

	protected $action     = 'fluid_before_main';
	protected $mobile     = 'fluid_after_main';
	protected $css        =  array();
	protected $horizontal =  false;
	protected $position   = 'none';
	protected $root       = 'sidebar';
	protected $sidebar    = 'standard';
	protected $fluid      = 'static';
	protected $slug;

	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->position = $this->positioning();
		if ( defined( 'FLUID_NO_SIDEBAR' ) ) {
			$this->position = 'none';
		}
		if ( ! ( $this->position === 'none' ) ) {
			$this->sidebar = get_page_slug();
			$this->slug    = $this->sidebar;
			$this->fluid   = get_theme_mod( 'sidebar_fluidity', 'static' );
			$this->css     = ( $this->fluid === 'static' ) ? tcc_layout( 'sidebar_css', $this->sidebar_css ) : '';
			$args = apply_filters( 'fluid_theme_sidebar_args', $args );
			$this->parse_args( $args );
			if ( defined( 'FLUID_LEFT_SIDEBAR'  ) || defined( 'FLUID_RIGHT_SIDEBAR' ) ) {
				$this->fluid = 'static';
			}
			if ( ! $this->horizontal ) {
				$this->check_mobile();
			}
			if ( ! empty( $this->action ) ) {
				add_action( 'tcc_custom_css', [ $this, 'fluid_style' ] );
				add_action( $this->action,    [ $this, 'show_sidebar' ] );
			}
			$this->adjust_content_width();
		}
	}

	public function is_sidebar_active() {
		return ! ( $this->position === 'none' );
	}

	private function check_mobile() {
		if ( fluid()->is_mobile() ) {
			$mobile = get_theme_mod( 'sidebar_mobile', 'bottom' );
			if ( $mobile === 'none' ) {
				$this->action = false;
			} else if ( $mobile === 'bottom' ) {
				$this->action = 'fluid_after_main';
			}
		} else if ( ( $this->fluid === 'static' ) && get_theme_mod( 'sidebar_position', 'right' ) === 'right' ) {
			$this->action = 'fluid_after_main';
		}
	}

	public function fluid_style() {
		if ( ( ! fluid()->is_mobile() ) && ( ! ( $this->fluid === 'static' ) ) ) {
			echo "\n#commentform input.form-control,\n#commentform textarea.form-control {\n\twidth: 73%;\n}\n";
			echo "\n.featured-image .img-responsive {\n\tmax-width: 71%;\n}\n";
			do_action( 'fluidity_sidebar_fluid_styling' );
		}
	}

	protected function positioning() {
		$side = get_theme_mod( 'sidebar_position', 'right' );
		if ( defined( 'FLUID_LEFT_SIDEBAR'  ) ) { $side = 'left';  }
		if ( defined( 'FLUID_RIGHT_SIDEBAR' ) ) { $side = 'right'; }
		if ( is_attachment() )                { $side = 'none';  }
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
		if ( $this->fluid === 'fluid' ) {
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

	/**
	 * @brief Provides for css applied to main content tag.
	 *
	 * @param string $page
	 * @param string $css initial css class(es)
	 * @return string css classes to be applied
	 */
	public function main_tag_css( $page, $css = '' ) {
		if ( $this->position === 'none' ) {
			$css .= ' has-no-sidebar';
		} else {
			if ( $this->fluid === 'static' ) {
				if ( empty( $css ) ) {
					$css = tcc_layout( 'main_css', $this->main_css );
				}
				$css .= ' has-static-sidebar';
			} else {
				$css .= ' has-fluid-sidebar';
			}
			$css .= ' has-sidebar';
		}
		return fluid()->sanitize_html_class( $css ); #  apply_filters( 'fluid_main_tag_css', $css, $page ) );
	}

	/**
	 *  Control oEmbed width?
	 *
	 * @since 20190715
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/support.php
	 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Theme/Support.php
	 */
	protected function adjust_content_width() {
		global $content_width;
		$content_width = apply_filters( 'fluid_sidebar_content_width', 760 );
	}


}
