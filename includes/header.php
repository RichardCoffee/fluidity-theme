<?php

/*
 *  includes/header.php
 *
 */

if (!function_exists('tcc_browser_body_class')) {
  // http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
  function tcc_browser_body_class( array $classes ) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    if     ($is_lynx)   $classes[] = 'lynx';
    elseif ($is_gecko)  $classes[] = 'gecko';
    elseif ($is_opera)  $classes[] = 'opera';
    elseif ($is_NS4)    $classes[] = 'ns4';
    elseif ($is_safari) $classes[] = 'safari';
    elseif ($is_chrome) $classes[] = 'chrome';
    elseif ($is_IE)     $classes[] = 'ie';
    else                $classes[] = 'unknown';
    if     ($is_iphone) $classes[] = 'iphone';
    return $classes;
  }
  add_filter('body_class','tcc_browser_body_class');
}

if ( ! function_exists( 'tcc_custom_css' ) ) {
	function tcc_custom_css() { ?>
		<style id="tcc-custom-css"><?php
			do_action( 'tcc_custom_css' ); ?>
		</style><?php
	}
}

if ( ! function_exists( 'fluid_assign_default_header' ) ) {
	function fluid_assign_default_header() {
		if ( ! has_action( 'tcc_header_body_content' ) ) {
			add_action( 'tcc_header_body_content', 'fluid_default_header' );
		}
	}
	add_action( 'wp_loaded', 'fluid_assign_default_header' );
}

// Limit length of title string
if (!function_exists('fluid_browser_title')) {
  function fluid_browser_title( $title ) {
    if (!is_feed()) {
      $test = get_bloginfo('name');
      if (empty($title)) {
        $title = $test;
      } else {
        $spot = strpos($title,$test);
        if ($spot) {
          $title = substr($title,0,$spot);
        }
        $title.= ($test) ? " $test" : '';
      }
    }
    return $title;
  }
  add_filter('wp_title','fluid_browser_title',10,2); // FIXME:  wp_title to be deprecated
}

if ( ! function_exists( 'fluid_default_header' ) ) {
	function fluid_default_header() { ?>
		<div class="row margint1e marginb1e">
			<div class="col-lg-1  col-md-1  hidden-sm hidden-xs"></div>
			<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
				<?php get_template_part('template-parts/menu'); ?>
			</div>
			<div class="col-lg-1  col-md-1  hidden-sm hidden-xs"></div>
		</div><?php
	}
}

/**
 * put in theme header links
 *
 * @since 20180418
 */
if ( ! function_exists( 'fluid_header_links' ) ) {
	function fluid_header_links() {
		// <link rel="icon" href="<?php echo get_theme_file_uri( 'favicon.ico' ); ? >" type="image/x-icon" />
		$attrs = array(
			'rel'  => 'icon',
			'href' =>  get_theme_file_uri( 'favicon.ico' ),
			'type' => 'image/x-icon'
		);
		fluid()->element( 'link', $attrs );
		// <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ? >" />
		$attrs = array(
			'rel'  => 'pingback',
			'href' =>  get_bloginfo( 'pingback_url' )
		);
		fluid()->element( 'link', $attrs );
		// <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Open+Sans+Condensed:300,300i,700|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i' type='text/css'>
		// <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
	}
	add_action( 'fluid_header_links', 'fluid_header_links' );
}

if ( ! function_exists( 'fluid_header_logo' ) ) {
	function fluid_header_logo( $html = '', $blog_id = 1 ) {
		$echo = ( doing_filter( 'get_custom_logo' ) ) ? false : true;  #  for backward compatibility
		if ( ! is_customize_preview() ) {
			ob_start(); ?>
			<div class="pointer" itemprop="logo" <?php microdata()->ImageObject(); ?>><?php
#				if ( function_exists( 'jetpack_the_site_logo' ) ) {
#					jetpack_the_site_logo();
#				} else {
					$attrs = array(
						'class'    => 'custom-logo-link',
						'href'     => home_url( '/' ),
						'rel'      => 'home',
						'itemprop' => 'url', // 'relatedLink',
					); ?>
					<a <?php fluid()->apply_attrs( $attrs ); ?>><?php
						$logo_id = get_theme_mod( 'custom_logo' );
						if ( $logo_id ) {
							$size  = apply_filters( 'fluid_header_logo_size', 'full' );
							$class = apply_filters( 'fluid_header_logo_class', array( 'centered', 'img-responsive', "attachment-$size", 'hidden-xs' ) );
							$logo  = wp_get_attachment_image_src( $custom_logo_id , $size );
							$attrs = array(
								'class'     => $class,
								'data-size' => $size,
								'src'       => $logo[0],
								'alt'       => get_bloginfo( 'name' ),
								'title'     => get_bloginfo( 'name' ),
								'itemprop'  => 'logo',
							);
							fluid()->element( 'img', $attrs );
						} else {
							bloginfo( 'name' );
						} ?>
					</a><?php
#				} ?>
			</div><?php
			$html = ob_get_clean();
		}
		if ( $echo ) { echo $html; } else { return $html; }
	}
	add_filter( 'get_custom_logo', 'fluid_header_logo', 20, 2 );
}

if ( ! function_exists( 'fluid_menubar_print_button' ) ) {
	function fluid_menubar_print_button() {
		if ( is_single() || is_page() ) {
			$attrs = array(
				'class'   => 'btn btn-fluidity',
				'onclick' => 'print();',
				'title'   => __( 'Print this page using your browser print function.', 'tcc-fluid' ),
				'aria-labelledby' => 'print-button-text'
			);
#			$attrs = apply_filters( 'fluid_print_button_attrs', $attrs ); ?>
			<span class="hidden fluid-print-button">
				<button <?php fluid()->apply_attrs( $attrs ); ?>>
					<?php fluid()->fawe( 'fa-print' ); ?>
					<span id="print-button-text" class="hidden-xs">&nbsp;
						<?php esc_html_e('Print','tcc-fluid'); ?>
					</span>
				</button>
			</span><?php
		}
	}
}

if ( ! function_exists( 'fluid_schema_page_check' ) ) {
	function fluid_schema_page_check() {
		if ( is_single() ) {
			microdata()->ItemPage();
		}
		do_action( 'fluid_schema_page_check' );
	}
}

/**
 *  show/hide admin bar on front end
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/show_admin_bar
 * @parameter bool $show
 */
if ( ! function_exists( 'fluid_show_admin_bar' ) ) {
	function fluid_show_admin_bar( $show ) {
		# TODO: add theme_mod setting - plugin territory
		return ( current_user_can( 'administrator' ) ) ? $show : false;
	}
	add_filter( 'show_admin_bar' , 'fluid_show_admin_bar' );
}
#add_filter( 'show_admin_bar', '__return_false' );

