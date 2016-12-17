<?php

if (!function_exists('tcc_register_widgets')) {
   function tcc_register_widgets() {
      register_widget('TCC_Widgets_Address');
      register_widget('TCC_Widgets_Login');
      register_widget('TCC_Widgets_Logo');
      register_widget('TCC_Widgets_Search');
   }
   add_action('widgets_init','tcc_register_widgets');
}
/*
function fluid_sidebar_admin_setup() {
  log_entry('in action');
}
add_action('sidebar_admin_setup','fluid_sidebar_admin_setup'); //*/
/*
function fluid_widgets_admin_page() {
  echo '<p class="fluid-testing">widgets_admin_page</p>';
}
add_action('widgets_admin_page','fluid_widgets_admin_page'); //*/
/*
function fluid_sidebar_admin_page() {
  echo '<p class="fluid-testing">sidebar_admin_page</p>';
}
add_action('sidebar_admin_page','fluid_sidebar_admin_page'); //*/

