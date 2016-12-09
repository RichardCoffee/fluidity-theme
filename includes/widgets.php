<?php

function tcc_register_widgets() {
  require_once('../classes/widgets.php');
  register_widget('TCC_Address_Widget');
  register_widget('TCC_Login_Widget');
  register_widget('TCC_Logo_Widget');
  register_widget('TCC_Search_Widget');
  do_action('tcc_register_widgets');
}
add_action('widgets_init','tcc_register_widgets');

#add_action('sidebar_admin_setup','fluid_
