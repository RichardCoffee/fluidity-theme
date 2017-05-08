<?php

/*
 *  includes/footer.php
 *
 */

#  Uses earliest published post to generate copyright date
if ( ! function_exists( 'fluid_copyright_dates' ) ) {
	function fluid_copyright_dates() {
		global $wpdb;
		$output = '';
		$select = "SELECT YEAR(min(post_date_gmt)) AS firstdate, YEAR(max(post_date_gmt)) AS lastdate FROM $wpdb->posts WHERE post_status = 'publish'";
		$copyright_dates = $wpdb->get_results( $select );
		if ( $copyright_dates ) {
			$copyright = $copyright_dates[0];
			$output = "&copy; <span itemprop='copyrightYear'>{$copyright->firstdate}</span>";
			if ( $copyright->firstdate !== $copyright->lastdate ) {
				$output .= '-' . $copyright->lastdate;
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
	function tcc_copyright( $banner=true ) { ?>

		<span class="pull-left">
			<?php do_action( 'tcc_copyright_left' ); ?>
		</span>

		<span class="pull-right">
			<?php do_action( 'tcc_copyright_right' ); ?>
		</span>

		<p id="fluidity-copyright" class="text-center"><?php
			$format = esc_html_x( 'Copyright %1$s %2$s, All rights reserved.', '1: numeric year  2: site name', 'tcc-fluid' );
			$title  = apply_filters( 'tcc_copyright_name', microdata()->get_bloginfo( 'name' ) );
			echo sprintf( $format, fluid_copyright_dates(),$title ); ?>
		</p><?php
	}
}

if ( ! function_exists( 'tcc_footer_menu' ) ) {
	function tcc_footer_menu() {
		$foot_menu = array();
		if ( page_exists( 'terms' ) )           $foot_menu['terms']           = __( 'Terms & Conditions', 'tcc-fluid' );
		if ( page_exists( 'terms-of-use' ) )    $foot_menu['terms-of-use']    = __( 'Terms & Conditions', 'tcc-fluid' );
		if ( page_exists( 'conditions' ) )      $foot_menu['conditions']      = __( 'Terms & Conditions', 'tcc-fluid' );
		if ( page_exists( 'privacy' ) )         $foot_menu['privacy']         = __( 'Privacy Policy',     'tcc-fluid' );
		if ( page_exists( 'privacy-policy' ) )  $foot_menu['privacy-policy']  = __( 'Privacy Policy',     'tcc-fluid' );
		if ( page_exists( 'security' ) )        $foot_menu['security']        = __( 'Security Policy',    'tcc-fluid' );
		if ( page_exists( 'security-policy' ) ) $foot_menu['security-policy'] = __( 'Security Policy',    'tcc-fluid' );
		$foot_menu = apply_filters( 'tcc_footer_menu', $foot_menu );
		if ($foot_menu) {
			$menu   = array();
			foreach( $foot_menu as $index => $text ) {
				$menu[] = '<a class="tcc-footer-menu-item" href="/' . $index . '/">&nbsp;' . esc_html( $text ) . '&nbsp; </a>';
			} ?>
			<span class="tcc-footer-menu" <?php microdata()->SiteNavigationElement(); ?>>
				<?php echo implode( '&nbsp;|&nbsp;', $menu ); ?>
			</span><?php
		}
	}
}


if ( ! function_exists( 'tcc_site_link' ) ) {
	function tcc_site_link() {
		$attrs = get_file_data( FLUIDITY_HOME . 'style.css', array( 'href' => 'Theme URI' ) );
		$attrs['title'] = __( 'Theme by The Creative Collective', 'tcc-fluid' );
		$attrs['target'] = 'the_creative_collective'; ?>
		<a <?php fluid_library()->appy_attrs( $attrs ); ?>>
			<img alt="TCC" src="<?php echo get_template_directory_uri(); ?>/icons/tcc-btn.png" class="tcc-icon">
		</a><?php
	}
	add_action( 'tcc_copyright_left', 'tcc_site_link' );
}

if (!function_exists('tcc_xfn_link')) {
	function tcc_xfn_link() { ?>
		<a href="http://gmpg.org/xfn/" target="gmpg_org_xfn">
			<img alt="XFN" src="<?php echo get_template_directory_uri(); ?>/icons/xfn-btn.gif">
		</a><?php
	}
	add_action('tcc_copyright_right','tcc_xfn_link');
}
