<?php

class TCC_Widget_Sidebar {

	protected $action     = 'tcc_before_main';
	protected $css        =  array();
	protected $horizontal =  false;
	protected $is_mobile  =  false;
	protected $position   = 'none';
	protected $sidebar    = 'standard';
	protected $slug;

	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->slug      = get_page_slug();
		$this->is_mobile = $this->is_mobile();
		$this->position  = $this->positioning();
		$this->parse_args( $args );
		$this->css = ( is_array( $this->css ) ) ? $this->css : explode( ' ', $this->css );
		if ( $this->position !== 'none' ) {
			if ( ( $this->position === 'right' )  && $this->css ) {
				$this->action = 'tcc_after_main';
			}
			$mobile = tcc_layout( 'mobile_sidebar' );
			if ( $this->is_mobile && ( $mobile === 'bottom' ) && ( ! $this->horizontal ) ) {
				$this->action = 'tcc_after_main';
			}
			add_action( $this->action, array( $this, 'show_sidebar' ) );
		}
log_entry($this);
	}

	protected function is_mobile() {
		#	Use mobble plugin if present
		if ( class_exists( 'Mobile_Detect' ) ) {
			$mobile = is_mobile();
		} else {
			$mobile = wp_is_mobile();
		}
		return $mobile;
	}

	protected function positioning() {
		$side = 'none';
		if ( ! defined( 'TCC_NO_SIDEBAR' ) ) {
			$side = tcc_layout( 'sidebar' );
		}
		if ( defined( 'TCC_LEFT_SIDEBAR'  ) ) { $side = 'left';  }
		if ( defined( 'TCC_RIGHT_SIDEBAR' ) ) { $side = 'right'; }
		$side = ( $this->horizontal ) ? 'horizontal' : $side;
		return $side;
	}

	public function show_sidebar() {
		$css  = array(
			'fluid-sidebar-' . $this->position,
			'noprint',
			'widget-area',
		);
		if ( $this->slug ) {
			$css[] = 'fluid-sidebar-' . $this->slug;
		}
		if ( empty( $this->css ) ) {
			$css[] = 'fluid-sidebar';
			$css[] = 'pull-' . $this->position;
		} else {
			$css = array_merge( $css, $this->css );
		}
		$css = apply_filters( 'fluid_sidebar_css', $css );
		if ( $this->slug ) {
			$css = apply_filters( 'fluid_sidebar_css_' . $this->slug, $css );
		}
		$css = array_map( 'esc_attr', array_unique( $css ) ); ?>
		<div class="<?php echo join( ' ', $css ); ?>" <?php microdata()->WPSideBar(); ?> role="complementary">
			<?php get_template_part( 'sidebar', $this->sidebar ); ?>
		</div><?php
	}


}
