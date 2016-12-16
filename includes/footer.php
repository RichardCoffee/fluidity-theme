<?php

/*
 *  includes/footer.php
 *
 */

#  Uses earliest published post to generate copyright date
if (!function_exists('fluid_copyright_dates')) {
  function fluid_copyright_dates() {
    global $wpdb;
    $output = '';
    $select = "SELECT YEAR(min(post_date_gmt)) AS firstdate, YEAR(max(post_date_gmt)) AS lastdate FROM $wpdb->posts WHERE post_status = 'publish'";
    $copyright_dates = $wpdb->get_results($select);
    if($copyright_dates) {
      $output = "&copy; <span itemprop='copyrightYear'>{$copyright_dates[0]->firstdate}</span>";
      if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
        $output .= '-'.$copyright_dates[0]->lastdate;
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

if (!function_exists('tcc_copyright')) {
	function tcc_copyright($banner=true) { ?>

		<span class="pull-left">
			<a href="http://the-creative-collective.com" target="the_creative_collective">
				<img alt="TCC" src="<?php echo get_template_directory_uri(); ?>/icons/tcc-btn.png" class="tcc-icon">
			</a>
		</span>

		<span class="pull-right">
			<a href="http://gmpg.org/xfn/" target="gmpg_org_xfn">
				<img alt="XFN Friendly" src="<?php echo get_template_directory_uri(); ?>/icons/xfn-btn.gif">
			</a>
		</span>

		<p class="text-center"><?php
			$format = esc_html_x('Copyright %1$s %2$s, All rights reserved.','First string will be a year, Second string is the site name','tcc-fluid');
			$title  = apply_filters('tcc_copyright_name',microdata()->get_bloginfo('name'));
			echo sprintf($format,fluid_copyright_dates(),$title); ?>
		</p>

<?php } }
