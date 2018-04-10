<?php

class TCC_Widget_Sidebar {

	protected $action     = 'tcc_before_main';
	protected $css        =  array();
	protected $horizontal =  false;
	protected $position   = 'none';
	protected $root       = 'sidebar';
	protected $sidebar    = 'standard';
	protected $slug;

	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->slug = get_page_slug();
		$this->parse_args( $args );
		if ( $this->action ) {
			add_action( $this->action, array( $this, 'show_sidebar' ) );
		}
	}

	public function show_sidebar() {
		$css = array(
			'noprint',
			'widget-area',
		);
		if ( $this->slug ) {
			$css[] = 'fluid-sidebar-' . $this->slug;
		}
		if ( $this->slug !== $this->sidebar ) {
			$css[] = 'fluid-sidebar-' . $this->sidebar;
		}
		if ( empty( $this->css ) ) {
			$css[] = 'fluid-sidebar';
			$css[] = 'fluid-sidebar-' . $this->position;
			$css[] = 'pull-' . $this->position;
		} else {
			$css = array_merge( $css, ( ( is_array( $this->css ) ) ? $this->css : explode( ' ', $this->css ) ) );
		}
		$css = array_unique( $css ); ?>
		<div class="<?php echo esc_attr( join( ' ', $css ) ); ?>" <?php microdata()->WPSideBar(); ?> role="complementary">
			<?php get_template_part( $this->root, $this->sidebar ); ?>
		</div><?php
	}


}	#	end of class TCC_Widget_Sidebar
