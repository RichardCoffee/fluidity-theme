<?php

if (TCC_USE_GALLERYVIEW===true) {

	if (!function_exists('tcc_register_galleryview')) {
		function tcc_register_galleryview() {
			wp_register_style('tcc-galleryview-css', get_theme_file_uri('galleryview/css/jquery.galleryview-3.0-dev.css'), null, '3.0');
			wp_register_style('tcc-gv-css',          get_theme_file_uri('css/galleryview.css'), null, FLUIDITY_VERSION);
			wp_register_script('tcc-galleryview-js', get_theme_file_uri('galleryview/js/jquery.galleryview-3.0-dev.js'), array('jquery'),'3.0',true);
			wp_register_script('tcc-gv-easing',      get_theme_file_uri('galleryview/js/jquery.easing.1.3.js'), array('jquery','tcc-galleryview-js'),'1.3',true);
			wp_register_script('tcc-gv-timers',      get_theme_file_uri('galleryview/js/jquery.timers-1.2.js'), array('jquery','tcc-galleryview-js'),'1.2',true);
			wp_register_script('tcc-gv-load',        get_theme_file_uri('js/galleryview.js'), array('tcc-gv-easing','tcc-gv-timers'), FLUIDITY_VERSION, true);
		}
		add_action('tcc_after_enqueue','tcc_register_galleryview');
	}

	if (!function_exists('tcc_enqueue_galleryview')) {
		function tcc_enqueue_galleryview($query) {
$str1 = 'is_admin: ';
$str1.= (is_admin()) ? 'true' : 'false';
$str2 = 'main query: ';
$str2.= ($query->is_main_query()) ? 'true' : 'false';
$str3 = 'is_single: ';
$str3.= (is_single()) ? 'true' : 'false';
$str4 = 'query->is_single: ';
$str4.= ($query->is_single) ? 'true' : 'false';
$str5 = 'is_singular: ';
$str5.= (is_singular()) ? 'true' : 'false';
$str6 = 'query->is_singular: ';
$str6.= ($query->is_singular) ? 'true' : 'false';
log_entry($str1,$str2,$str3,$str4,$str5,$str6);
			if (!is_admin() && $query->is_singular) {
				wp_enqueue_style('tcc-galleryview-css');
				wp_enqueue_style('tcc-gv-css');
				wp_enqueue_script('tcc-gv-load');
			}
		}
		add_filter('pre_get_posts','tcc_enqueue_galleryview');
	}

}
