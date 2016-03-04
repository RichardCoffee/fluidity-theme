<?php

/*
 *  tcc-fluidity/includes/footer.php
 *
 */

//  Uses earliest published post to generate copyright date
if (!function_exists('site_copyright_dates')) {
  function site_copyright_dates() {
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
