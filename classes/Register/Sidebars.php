<?php
/**
 * classes/Register/Sidebars.php
 *
 */
/**
 * register sidebars
 *
 */
class TCC_Register_Sidebars {

	/**
	 * Font Awesome icons set
	 *
	 */
	protected $fawe = array();

	/**
	 * set up tasks for registering sidebars
	 *
	 */
	public function __construct() {
		$this->set_widget_icons();
		add_action( 'widgets_init',       array( $this, 'register_sidebars' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_widget_icons' ), 100 );
	}

	/**
	 * set up default theme sidebars
	 *
	 * @param array $sidebars
	 * @return array
	 */
	protected function default_sidebars( $sidebars = array() ) {
		#	Standard Page
		$sidebars['standard'] = array(
			'name' => __('Standard Page w/Panels','tcc-fluid'),
			'id'   => 'standard',
		);
		return apply_filters( 'fluid_default_sidebars', $sidebars );
	}

	/**
	 * assign before/after strings for widget and title
	 *
	 * @param array $sidebars
	 * @return array
	 */
	protected function prepare_sidebars( $sidebars = array() ) {
		$title  = $this->title_html();
		$widget = $this->widget_html();
		foreach( $sidebars as $slug => &$base) {
			$base['before_widget'] = $widget['before'];
			$base['before_title']  = $title[ 'before'];
			$base['after_title']   = $title[ 'after'];
			$base['after_widget']  = $widget['after'];
		}
		return $sidebars; # apply_filters( 'fluid_prepare_sidebars', $sidebars );
	}

	/**
	 * get and register sidebars
	 *
	 */
	public function register_sidebars() {
		remove_action( 'widgets_init', array( $this, 'register_sidebars' ) ); // prevents possible recursion
		$sidebars = $this->default_sidebars( array() );
		$sidebars = $this->prepare_sidebars( $sidebars );
		foreach( $sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

	/**
	 * retrieve and assign font awesome icon set
	 *
	 */
	protected function set_widget_icons() {
		$fawe_set = fluid()->get_widget_fawe();
		$current  = get_theme_mod( 'widgyt_icons', 'default' );
		$this->fawe = isset( $fawe_set[ $current ] ) ? $fawe_set[ $current ] : $fawe_set['default'];
	}

	/**
	 * pass icon set to javascript
	 *
	 */
	public function enqueue_widget_icons() {
		if ( wp_script_is( 'tcc-collapse', 'enqueued' ) ) {
			$icons = 'var col_icons = ' . json_encode( $this->fawe );
			wp_add_inline_script( 'tcc-collapse', $icons, 'before' );
		}
	}

	/**
	 * generate before/after html for title
	 *
	 * @return array
	 */
	protected function title_html() {
		$title  = array();
		$status = get_theme_mod( 'widgyt_collapse', 'perm' );
		$title['before']  = '<div class="panel-heading"';
		$title['before'] .= ( $status === 'perm' )   ? '' : ' role="button"';
		$title['before'] .= ( $status === 'closed' ) ? ' data-collapse="1">' : '>';
		$fa_sign          = ( $status === 'open' )   ? $this->fawe['minus'] : $this->fawe['plus'];
		$title['before'] .= ( $status === 'perm' )   ? '' : fluid()->get_fawe( "$fa_sign pull-right panel-sign" );
		$before_css       = ( $status === 'perm' )   ? '' : 'scroll-this pointer';
		$title['before'] .= "<div class='panel-title text-center $before_css'><b>";
		$title['after']   = '</b></div></div><div class="panel-body">';
		return $title; # apply_filters( 'fluid_register_sidebar_title', $title );
	}

	/**
	 * generate before/after html for widgets
	 *
	 * @return array
	 */
	protected function widget_html() {
		$widget = array();
		// string placeholders required for customizer theme support
		$widget['before'] = '<div id="%1$s" class="panel panel-fluidity %2$s">';
		#	Second /div closes "panel-body" div
		$widget['after']  = '</div></div>';
		return $widget; # apply_filters( 'fluid_register_sidebar_widget', $widget );
	}


}
