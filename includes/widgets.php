<?php

if (!function_exists('tcc_register_widgets')) {
	function tcc_register_widgets() {
		$widgets = array('Address','Login','Logo','Search');
		register_widget('TCC_Widgets_Address');
		register_widget('TCC_Widgets_Login');
		register_widget('TCC_Widgets_Logo');
		register_widget('TCC_Widgets_Search');
	}
	add_action('widgets_init','tcc_register_widgets');
}
