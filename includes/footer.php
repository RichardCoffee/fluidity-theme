<?php

/*
 *  includes/footer.php
 *
 */

add_filter ( "fluid_footer_container_type", function ( $css ) { return "container-fluid port-opaque"; } );


#  Uses earliest published post to generate copyright date
if ( ! function_exists( 'fluid_copyright_dates' ) ) {
	function fluid_copyright_dates() {
		global $wpdb;
		$output = '';
		$select = "SELECT YEAR(min(post_date_gmt)) AS firstdate, YEAR(max(post_date_gmt)) AS lastdate FROM $wpdb->posts WHERE post_status = 'publish'";
		$copyright_dates = $wpdb->get_results( $select );
		if ( $copyright_dates ) {
			$copyright = $copyright_dates[0];
			if ( $copyright->firstdate === $copyright->lastdate ) {
				$output = "&copy; <span itemprop='copyrightYear'>{$copyright->firstdate}</span>";
			} else {
				$output = "&copy; {$copyright->firstdate}-<span itemprop='copyrightYear'>{$copyright->lastdate}</span>";
			}
		}
		return $output;
	}
}
/*
if (!function_exists('fluid_footer_autohide')) { // FIXME
  function fluid_footer_autohide() {
    if (wp_script_is('jquery','done')) { ?>
<script type="text/javascript">
  jQuery(document).ready(function() {
    var wpHeight  = (jQuery('#wpadminbar')) ? jQuery('#wpadminbar').outerHeight() : 0;
console.log('wpadminbar: '+wpHeight);
    var hdrHeight = jQuery('#fluid-header').outerHeight();
console.log('header: '+hdrHeight);
    if (jQuery('#wpadminbar')) {
      jQuery('.header-fixed,.header-hide').css({top:wpHeight});
    }
    jQuery('.header-fixed,.header-hide').next().css({"padding-top":(hdrHeight+wpHeight)+'px'});
  });
</script><?php
    }
  }
  #add_action( 'wp_footer', 'fluid_footer_autohide', 99 );
} //*/

if ( ! function_exists( 'tcc_copyright' ) ) {
	function tcc_copyright( $banner = true ) { ?>

		<span class="pull-left">
			<?php do_action( 'tcc_copyright_left' ); ?>
		</span>

		<span class="pull-right">
			<?php do_action( 'tcc_copyright_right' ); ?>
		</span>

		<p id="fluidity-copyright" class="text-center"><?php
			$format = _x( 'Copyright %1$s %2$s, All rights reserved.', '1: numeric year  2: site name', 'tcc-fluid' );
			$title  = apply_filters( 'fluid_copyright_name', microdata()->get_bloginfo( 'name' ) );
			echo wp_kses ( sprintf( $format, fluid_copyright_dates(), $title ), fluid()->kses() );
#			fluid()->fawe( 'fab fa-php' ); ?>
		</p><?php
	}
}
/*
if ( ! function_exists( 'tcc_site_link' ) ) {
	function tcc_site_link() {
		$ittrs = array(
			'class' => 'tcc-icon',
			'alt'   => 'RTC',
			'src'   => get_theme_file_uri( 'icons/tcc-btn.png' ),
		);
		$image = fluid()->get_tag( 'img', $ittrs );
		$data  = array(
			'href'  => 'Theme URI',
			'title' => 'Theme Name',
		);
		$attrs = get_file_data( FLUIDITY_HOME . 'style.css', $data );
		$attrs['target'] = 'rtc_github';
		fluid()->element( 'a', $attrs, $image, true );
	}
	add_action( 'tcc_copyright_left', 'tcc_site_link' );
} //*/
/*
if ( ! function_exists( 'tcc_xfn_link' ) ) {
	function tcc_xfn_link() {
		$ittrs = array(
			'alt' => 'XFN',
			'src' => get_theme_file_uri( 'icons/xfn-btn.gif' ),
		);
		$image = fluid->get_tag( 'img', $ittrs );
		$attrs = array(
			'href'   => 'http://gmpg.org/xfn/',
			'target' => 'gmpg_org_xfn',
		);
		fluid()->element( 'a', $attrs, $image, true );
	}
	add_action( 'tcc_copyright_right', 'tcc_xfn_link' );
	add_action( 'fluid_header_links', function() {
		$attrs = array(
			'rel'  => 'profile',
			'href' => 'http://gmpg.org/xfn/11',
		);
		fluid()->tag( 'link', $attrs );
	} );
} //*/

if ( ! function_exists( 'fluid_hidden_site_link' ) ) {
	function fluid_hidden_site_link() {
		fluid()->element( 'a', [ 'href' => 'rtcenterprises.net' ] );
	}
	add_action( 'tcc_copyright_right', 'fluid_hidden_site_link' );
}
