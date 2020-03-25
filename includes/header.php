<?php
/**
 *  includes/header.php
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */
/**
 *  add browser string to body class
 *
 * @since 20150515
 * @link http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
 * @param array $classes
 * @return array
 */
if (!function_exists('fluid_browser_body_class')) {
  function fluid_browser_body_class( array $classes ) {
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
  add_filter('body_class','fluid_browser_body_class');
}

/**
 *  provides for placing all custom css within single stylesheet
 *
 * @since 20160807
 */
if ( ! function_exists( 'fluid_custom_css' ) ) {
	function fluid_custom_css() {
		fluid()->tag( 'style', [ 'id' => 'fluid-custom-css' ] );
			do_action( 'fluid_custom_css' );
			do_action( 'tcc_custom_css' );
		echo '</style>';
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

/**
 *  will show color scheme logo if available
 *
 * @since 20150511
 * @param string $html
 * @param int $blog_id
 * @return string
 */
if ( ! function_exists( 'fluid_header_logo' ) ) {
	function fluid_header_logo( $html = '', $blog_id = 1 ) {
		$echo = ( doing_filter( 'get_custom_logo' ) ) ? false : true;  #  allows for separate use
#		if ( ! is_customize_preview() ) {
			$scheme   = fluid_color()->color_scheme();
			$div_css  = apply_filters( 'fluid_logo_div_css', 'pointer' );
			$div_atts = array_merge( [ 'class' => $div_css ], microdata()->ImageObject( true ) );
			ob_start(); ?>
			<div <?php fluid()->apply_attrs( $div_atts ); ?>><?php
				if ( $scheme === 'none' ) {
					echo $html;
				} else {
					$attrs = array(
						'class'    => 'custom-logo-link',
						'href'     => home_url( '/' ),
						'rel'      => 'home',
						'itemprop' => 'url', // 'relatedLink',
					); ?>
					<a <?php fluid()->apply_attrs( $attrs ); ?>><?php
						$logo_id = get_theme_mod( "color_scheme_logo_$scheme", false );
						$logo_id = ( $logo_id ) ? $logo_id : get_theme_mod( 'custom_logo' );
						if ( $logo_id ) {
							$size  = apply_filters( 'fluid_header_logo_size', 'full' );
							$class = apply_filters( 'fluid_header_logo_class', [ 'custom-logo', 'centered', 'img-responsive', "attachment-$size", 'hidden-xs' ], $size );
							$logo  = wp_get_attachment_image_src( $logo_id , $size );
							$alt   = get_bloginfo( 'name', 'display'  );
							$attrs = array(
								'class'     => $class,
								'data-size' => $size,
								'src'       => $logo[0],
								'alt'       => $alt,
								'title'     => $alt,
								'itemprop'  => 'logo',
							);
							fluid()->element( 'img', $attrs );
						} else {
							bloginfo( 'name' );
						} ?>
					</a><?php
				} ?>
			</div><?php
			$html = ob_get_clean();
#		}
		if ( $echo ) { echo $html; } else { return $html; }
	}
	add_filter( 'get_custom_logo', 'fluid_header_logo', 20, 2 );
}

/**
 *  show print button
 *
 * @since 20160830
 */
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

/**
 *  insert schema microdata for single pages
 *
 * @since 20180624
 */
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
 * @since 20180706
 * @link  https://codex.wordpress.org/Plugin_API/Filter_Reference/show_admin_bar
 * @param bool $show
 */
if ( ! function_exists( 'fluid_show_admin_bar' ) ) {
	function fluid_show_admin_bar( $show ) {
		# TODO: add theme_mod setting - plugin territory
		return ( current_user_can( 'publish_posts' ) ) ? $show : false;
	}
	add_filter( 'show_admin_bar' , 'fluid_show_admin_bar' );
}
#add_filter( 'show_admin_bar', '__return_false' );
