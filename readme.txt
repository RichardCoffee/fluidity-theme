




    Actions                      Description                                      Function                      File
==========================   ==============================================    ========================   =====================
 tcc_custom_css               insert css into inline stylesheet                 tcc_custom_colors          includes/misc.php
 tcc_enqueue                  enqueue front-end styles/scripts                  fluid_enqueue              functions.php
 tcc_navbar_signout           insert navbar html before signout button          tcc_login_form             includes/login.php
 tcc_register_widgets         register widgets extending TCC_Basic_Widget       register_fluid_widgets     classes/widgets.php
 tcc_widget_class_loaded      load widgets extending TCC_Basic_Widget           n/a                        classes/widgets.php
 tcc_widget_signout           insert widget html before signout button          tcc_login_form             includes/login.php

   Filters
========================     ==============================================    ========================   =====================
 fluid_title                  post title - this function controls the length    fluid_title                includes/misc.php
 tcc_login_username           placeholder for user name                         tcc_login_form             includes/login.php
 tcc_login_userpass           placeholder for user password                     tcc_login_form             includes/login.php
 tcc_register_sidebars        sidebar array                                     register_fluid_sidebars    includes/sidebars.php
