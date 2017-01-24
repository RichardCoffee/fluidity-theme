<?php

if (!function_exists('tcc_register_widgets')) {
	function tcc_register_widgets() {
		$widgets = aaply_filters( 'tcc_register_widgets_list', array('Address','Login','Logo','Search') );
		foreach($widgets as $widget) {
			register_widget("TCC_Widgets_$widget");
		}
	}
	add_action('widgets_init','tcc_register_widgets');
}
