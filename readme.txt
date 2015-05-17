




    Actions                      Description                                      Function                      File
==========================   ==============================================    ========================   ============================
 tcc_custom_css               insert css via inline stylesheet                  tcc_custom_colors          includes/misc.php

 tcc_enqueue                  enqueue front-end styles/scripts                  fluid_enqueue              functions.php

 tcc_footer                   footer body                                       n/a                        footer.php

 tcc_header_body_content      control what goes into the header body            n/a                        template_parts/header.php
                                used by fluidity_header_body

 tcc_header_bottom_menubar    bottom menubar of header                          n/a                        template_parts/header.php
                                used by fluidity_main_menubar

 tcc_header_top_menubar       top menubar of header                             n/a                        template_parts/header.php

 tcc_left_header_body         left side of header body                          fluidity_header_body       includes/header.php
                                used by fluidity_header_logo

 tcc_main_header_body         used as replacement for left/right body           fluidity_header_body       includes/header.php

 tcc_navbar_signout           insert navbar html before signout button          tcc_login_form             includes/login.php

 tcc_register_widgets         register widgets extending TCC_Basic_Widget       register_fluid_widgets     classes/widgets.php

 tcc_right_header_body        right side of header body                         fluidity_header_body       includes/header.php

 tcc_top_left_menubar         left side of top menu bar                         fluidity_top_menubar       includes/header.php

 tcc_top_right_menubar        right side of top menu bar                        fluidity_top_menubar       includes/header.php
                                used by fluidity_header_bar_login

 tcc_widget_class_loaded      load widgets extending TCC_Basic_Widget           n/a                        classes/widgets.php

 tcc_widget_signout           insert widget html before signout button          tcc_login_form             includes/login.php


   Filters                       Description                                      Function                      File
========================     ==============================================    ========================   ============================
 tcc_author_posts_title       main title for author's posts                     n/a                        author.php
 tcc_bottom_menu              footer menu below copyright                       n/a                        template_parts/footer.php
 tcc_header_body              control the header body layout                    fluidity_header_body       includes/header.php
 tcc_login_username           placeholder for user name                         tcc_login_form             includes/login.php
 tcc_login_userpass           placeholder for user password                     tcc_login_form             includes/login.php
 tcc_register_sidebars        sidebar array                                     register_fluid_sidebars    includes/sidebars.php
 the_title                    as per WordPress Codex                            fluid_title                includes/misc.php
 widget_title                 widget title                                      pre_widget                 classes/widgets.php
