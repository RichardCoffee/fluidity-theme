<?php
/**
 *  @brief This sets up the widget area sidebars.
 *
 *  @since 1.0.0
 *
 *  @package Fluidity
 *  @subpackage Sidebars
 */

/*
add_filter( 'tcc_theme_sidebar_args', function( $args ) {
	if ( in_array( get_page_slug(), array( '404', 'login' ) ) ) {
		$args['position'] = 'none';
	}
	return $args;
} ); //*/

/**
 */
/*
if ( ! function_exists( 'fluidity_register_sidebars' ) ) {
	function fluidity_register_sidebars() {
		$widget = tcc_layout('widget', 'perm' );
		$before_widget = '<div class="panel panel-fluidity">';
		$before_title  = '<div class="panel-heading"';
		$before_title .= ( $widget === 'perm' )   ? '' : ' role="button"';
		$before_title .= ( $widget === 'closed' ) ? ' data-collapse="1">' : '>';
		$fa_sign       = ( $widget === 'open' )   ? 'fa-minus' : 'fa-plus';
		$before_title .= ( $widget === 'perm' )   ? '' : "<i class='fa $fa_sign pull-right panel-sign'></i>";
		$before_css    = ( $widget === 'perm' )   ? '' : 'text-center scroll-this pointer';
		$before_title .= "<div class='panel-title $before_css'><b>";
		$after_title   = '</b></div></div><div class="panel-body">';
		$after_widget  = '</div></div>';
		#$before_widget = apply_filters('tcc_before_widget',$before_widget);
		#$before_title  = apply_filters('tcc_before_title', $before_title);
		#$after_title   = apply_filters('tcc_after_title',  $after_title);
		#$after_widget  = apply_filters('tcc_after_widget', $after_widget);
		$sidebars   = array();
		#	Standard Page
		$sidebars['standard'] = array(
			'name'          => esc_html__('Standard Page w/Panels','tcc-fluid'),
			'id'            => 'standard',
			'before_widget' => $before_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
			'after_widget'  => $after_widget);
		#	Home Page
		$sidebars['home'] = array(
			'name'          => esc_html__('Home Page w/Panels','tcc-fluid'),
			'id'            => 'home',
			'before_widget' => $before_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
			'after_widget'  => $after_widget,
		);
		$f2_before = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'><div class='panel panel-fluidity'><div class='panel-body back-fluidity'>";
		$sidebars['two'] = array(
			'name'          => esc_html__('Horizontal Panels (2 col)','tcc-fluid'),
			'id'            => 'footer2',
			'before_widget' => $f2_before,
			'before_title'  => '',
			'after_title'   => '',
			'after_widget'  => '</div></div></div>',
		);
		#	Header sidebar
		$sidebars['three'] = array(
			'name'          => esc_html__('Horizontal Panels (3 col)','tcc-fluid'),
			'id'            => 'three_column',
			'before_widget' => "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>$before_widget",
			'before_title'  => $before_title,
			'after_title'   => $after_title,
			'after_widget'  => "$after_widget</div>",
		);
		#	Footer sidebar
		$sidebars['four'] = array(
			'name'          => esc_html__('Horizontal Panels (4 col)','tcc-fluid'),
			'id'            => 'footer4',
			'before_widget' => "<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12'>$before_widget",
			'before_title'  => $before_title,
			'after_title'   => $after_title,
			'after_widget'  => "$after_widget</div>",
		);
		#	apply filters
		$sidebars = apply_filters('tcc_register_sidebars',$sidebars);
		foreach($sidebars as $sidebar) {
			register_sidebar($sidebar);
		}
	}
	add_action('widgets_init','fluidity_register_sidebars');
} //*/

function fluidity_the_widget($widget,$instance,$args) {
  log_entry('the widget',$widget,$instance,$args);
}
add_action('the_widget','fluidity_the_widget',999,3);

if (!function_exists('fluidity_get_sidebar')) {
  #  This function works in tandem with fluidity_sidebar_parameter()
  function fluidity_get_sidebar($sidebar='standard') {
    get_template_part('sidebar',$sidebar);
  }
}

if (!function_exists('fluidity_load_sidebar')) {
  function fluidity_load_sidebar($args,$force=false) {
    if (defined('TCC_NO_SIDEBAR')) { return; }  #  define in page template file
    $sidebars = ($force) ? (array)$args : array_merge((array)$args,array('standard','home'));
    $sidebars = apply_filters( 'fluidity_load_sidebar', $sidebars );
    $sidebars = apply_filters( 'fluidity_load_sidebar_' . get_page_slug(), $sidebars );
    $status   = tcc_settings( 'where', 'off' );
    foreach($sidebars as $sidebar) {
      if ( is_active_sidebar( $sidebar ) ) {
        if ($dyn=dynamic_sidebar($sidebar)) {
          #if ( $status === 'on' ) { echo "<p>$sidebar active</p>"; }
          return true;
        } #else if ( $status === 'on' ) { echo "<p>$sidebar non-dynamic</p>"; }
      } #else if ( $status === 'on' ) {   echo "<p>$sidebar not active</p>";  }
    }
    return $force;
  }
}

if (!function_exists('fluidity_sidebar_parameter')) {
  function fluidity_sidebar_parameter() {
    $trace = debug_backtrace();
    foreach($trace as $item) {
      if ($item['function']=='fluidity_get_sidebar') {
			return $item['args'][0]; }
      if (($item['function']=='get_template_part') && ($item['args'][0]=='sidebar')) {
			if (!empty($item['args'][1])) { return $item['args'][1]; }
		}
    }
    return '';
  }
}

if (!function_exists('fluidity_sidebar_layout')) {
	#	DEPRECATED - do not use
  function fluidity_sidebar_layout($sidebar='standard',$side='') {
    if (defined('TCC_NO_SIDEBAR')) { return; }  #  define in page template file
    $side = ($side) ? $side : tcc_layout('sidebar', 'left' );
    if ($side!=='none') {
      $posi = ($side=='right') ? 'pull-right' : '';
      $posi = (defined('TCC_LEFT_SIDEBAR')) ? '' : $posi;
      $posi = (defined('TCC_RIGHT_SIDEBAR')) ? 'pull-right' : $posi;
      $sidebar_class = "col-lg-4 col-md-4 col-sm-12 col-xs-12 margint1e $posi"; ?>
      <div class="<?php echo $sidebar_class; ?>" <?php microdata()->WPSideBar(); ?> role="complementary"><?php
        get_template_part('sidebar',$sidebar); ?>
      </div><?php
    }
  }
} //*/

/**
 *  @brief generates and displays sidebar html.
 *
 *  @param string css class(es) to be applied to sidebar div
 *  @param string sidebar to be used, defaults to 'standard' sidebar
 */
if ( ! function_exists( 'fluidity_sidebar' ) ) {
	#	DEPRECATED - do not use
	function fluidity_sidebar( $css = '', $sidebar = 'standard' ) {
		if ( defined( 'TCC_NO_SIDEBAR' ) ) { return; }  #  define in page template file
		$side = tcc_layout( 'sidebar', 'left' );
		defined( 'TCC_LEFT_SIDEBAR' )  or ( $side = 'left' );
		defined( 'TCC_RIGHT_SIDEBAR' ) or ( $side = 'right' );
		if ( $side !== 'none' ) {
			$slug = get_page_slug();
			$css  = apply_filters( 'fluid_sidebar_css', $css );
			$css  = apply_filters( "fluid_sidebar_css_$slug", $css );
			$css .= ( $side === 'right' ) ? ' pull-right' : '';
			$css .= ' widget-area fluid-sidebar fluid-sidebar-' . get_page_slug(); ?>
			<div class="<?php echo esc_attr( $css ); ?>" <?php microdata()->WPSideBar(); ?> role="complementary">
				<?php get_template_part( 'sidebar', $sidebar ); ?>
			</div><?php
		}
	}
}

if ( ! function_exists( 'tcc_sidebar' ) ) {
	function tcc_sidebar( $sidebar = 'standard' ) {
		$side = tcc_layout( 'sidebar', 'left' );
		$slug = get_page_slug();
		$css  = array(
			'widget-area',
			'fluid-sidebar',
			"fluid-sidebar-$side",
			"fluid-sidebar-$slug",
		);
		$css = array_merge( $css, $extra_css );
		$css = apply_filters( 'fluid_sidebar_css', $css );
		$css = apply_filters( "fluid_sidebar_css_$slug", $css );
		$css = array_map( 'esc_attr', array_unique( $css ) ); ?>
		<div class="<?php echo join( ' ', $css ); ?>" <?php microdata()->WPSideBar(); ?> role="complementary">
			<?php get_template_part( 'sidebar', $sidebar ); ?>
		</div><?php
	}
}
