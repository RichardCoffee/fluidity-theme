<?php

if (!WP_DEBUG) { // Source?  What does this do?
  add_filter('login_errors',create_function('$a',"return null;"));
}

if (!function_exists('tcc_admin_login_redirect')) {
  function tcc_admin_login_redirect($redirect_to,$request,$user) {
    if (get_class($user)=='WP_Error') return $redirect_to;
    if (!in_array("administrator",$user->roles)) return home_url();
    return $redirect_to;
  }
  add_filter("login_redirect","tcc_admin_login_redirect",10,3);
}

if (!function_exists('tcc_dashboard_logo') && function_exists('tcc_option')) {
  // http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
  function tcc_dashboard_logo() {
    $logo = tcc_option('logo');
    if ($logo) {
     echo "<style type='text/css'>
       #header-logo { background-image: url($logo) !important; }
     </style>";
    }
  }
  add_action('admin_head','tcc_dashboard_logo');
}

if (!function_exists('tcc_login_css')) {
  function tcc_login_css() {
    echo '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/loginpage/login-styles.css" />;';
    // http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
    $logo = tcc_design('logo');
    if ($logo) {
      echo "<style type='text/css'> h1 a { background-image:url($logo) !important; }</style>";
    }
  }
  add_action('login_head','tcc_login_css');
}

if (!function_exists('tcc_login_header_url')) {
  function tcc_login_header_url() {
    return home_url();
  }
  add_filter('login_headerurl','tcc_login_header_url' );
}

if (!function_exists('tcc_login_header_title')) {
  function tcc_login_header_title() {
    return '';
  }
  add_filter('login_headertitle','tcc_login_header_title');
}

// Why is this double enclosed? init vs login_footer?
if (!function_exists('tcc_remember_me')) {
  function tcc_remember_me() {
    if (!function_exists('tcc_remember_me_checked')) {
      function tcc_remember_me_checked() {
        echo "<script>document.getElementById('rememberme').checked = true;</script>";
      }
      add_filter('login_footer','tcc_remember_me_checked');
    }
  }
  add_action('init','tcc_remember_me');
}

// Source? FIXME:  what is this for, and why do we need it?  Is there a better way?
if (!function_exists('remove_lostpassword_text')) {
  function remove_lostpassword_text($text) {
    if ($text == 'Lost your password?')
      $text = '';
    return $text;
  }
  add_filter('gettext','remove_lostpassword_text');
}

if (!function_exists('tcc_login_form')) {
  function tcc_login_form($navbar=false,$right=false) {
    $uname   = apply_filters('tcc_login_username',__('Username','tcc-theme'));
    $upass   = apply_filters('tcc_login_userpass',__('Password','tcc-theme'));
    $signin  = __('Sign In',       'tcc-theme');
    $signout = __('Sign Out',      'tcc-theme');
    $lost    = __('Lost Password', 'tcc-theme');
    $color   = tcc_color_scheme();
    if ($navbar) {
      $align     = ($right) ? " navbar-right" : "";
      $formclass = "navbar-form$align";
    } else {
      $formclass = "login-form";
    }
    if (is_user_logged_in()) { ?>
      <form class="<?php echo $formclass; ?>" action="<?php #echo wp_logout_url(home_url()); ?>" method="post">
        <div style="text-align:center;"><?php
          $action = ($navbar) ? 'tcc_navbar_signout' : 'tcc_widget_signout';
          do_action($action);
          $out  = wp_logout_url(home_url());
          $html = "<a class='btn btn-$color' href='$out'";
          $html.= " title='$signout'> $signout <i class='fa fa-sign-out'></i></a>";
          echo $html; ?>
        </div>
      </form><?php
    } else { ?>
      <form id="loginform" class="<?php echo $formclass; ?>" name="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post">
        <div class='form-group'>
          <label class="sr-only" for="log"><?php echo $uname; ?></label>
          <input type="text" name="log" id="log" class="form-control" placeholder="<?php echo $uname; ?>" required>
        </div>
        <div class='form-group'>
          <label class="sr-only" for="pwd"><?php echo $upass; ?></label>
          <input type="password" name="pwd" id="pwd" class="form-control" placeholder="<?php echo $upass; ?>" required>
        </div>
        <div class="checkbox<?php echo ($navbar) ? ' hidden' : ''; ?>">
          <label>
            <input type="checkbox" id="rememberme" name="rememberme" value="forever"> Remember me
          </label>
        </div><?php
        if (get_page_by_title('Lost Password'))
          echo "<a href='".wp_lostpassword_url(home_url() )." title='$lost'>$lost</a>"; ?>
        <button type="submit" id="wp-submit" class="btn btn-<?php echo $color; ?>" name="wp=submit"><i class="fa fa-sign-in"></i> <?php echo $signin; ?> </button>
      </form><?php
    }
  }
}
