<?php

if (!WP_DEBUG) { // Source?
  add_filter('login_errors',create_function('$a',"return null;"));
}

if (!function_exists('tcc_admin_login_redirect')) {
  function tcc_admin_login_redirect($redirect_to,$request,$user) {
    if (get_class($user)=='WP_Error') return $redirect_to;
    $from = wp_get_referer();
#log_entry("referrer: $from");
#    if (!(strpos($from,'wp-admin')===false)) return $from;
#    if (!in_array("administrator",$user->roles)) return home_url();
    return home_url();
    return $redirect_to;
  }
  add_filter("login_redirect","tcc_admin_login_redirect",10,3);
}

if (!function_exists('tcc_dashboard_logo') && function_exists('tcc_option')) {
  // http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
  function tcc_dashboard_logo() {
    $logo = tcc_design('logo');
    if ($logo) {
      $dash = "<style type='text/css'>";
      $dash.= "  #header-logo { background-image: url($logo) !important; }";
      $dash.= "</style>";
      echo $dash;
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
/*
// Source? FIXME:  what is this for, and why do we need it?  Is there a better way?
if (!function_exists('remove_lostpassword_text')) {
  function remove_lostpassword_text($text) {
    if ($text == 'Lost your password?')
      $text = '';
    return $text;
  }
  add_filter('gettext','remove_lostpassword_text');
} //*/

if (!function_exists('tcc_login_form')) {
  function tcc_login_form($navbar=false,$right=false) {
    if (is_user_logged_in()) {
      $signout = apply_filters('tcc_signout_text', esc_html__('Sign Out', 'tcc-fluid')); ?>
      <form class="<?php #echo $formclass; ?>" action="<?php #echo wp_logout_url(home_url()); ?>" method="post">
        <div class="text-center;"><?php
          $action = ($navbar) ? 'tcc_navbar_signout' : 'tcc_widget_signout';
          do_action($action);
          $out  = wp_logout_url(home_url());
          $html = "<a class='btn btn-fluidity' href='$out'";
          $html.= " title='$signout'> $signout <i class='fa fa-sign-out'></i></a>";
          echo $html; ?>
        </div>
      </form><?php
    } else {
    	$uname  = apply_filters('tcc_login_username',esc_html__('Username',      'tcc-fluid'));
      $upass  = apply_filters('tcc_login_userpass',esc_html__('Password',      'tcc-fluid'));
      $signin = apply_filters('tcc_signin_text',   esc_html__('Sign In',       'tcc-fluid'));
      $lost   = apply_filters('tcc_lostpw_text',   esc_html__('Lost Password', 'tcc-fluid'));
      $formclass = ($navbar) ? "login-form" : 'navbar-form'.(($right) ? ' navbar-right' : '').' navbar-fluidity'; ?>
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
        <button type="submit" id="wp-submit" class="btn btn-fluidity" name="wp=submit"><i class="fa fa-sign-in"></i> <?php echo $signin; ?> </button>
      </form><?php
    }
  }
}

if (!function_exists('tcc_logout_url')) {
  function tcc_logout_url($url, $redirect) {
    #log_entry(0,"logout filter 1 - url: $url");
    $site = get_option('siteurl');
    $pos  = strpos($url,'?');
    if ($pos===false) {
      $url  .= "?redirect_to=".urlencode($site);
    } else {
      $base  = substr($url,0,$pos);
      parse_str(htmlspecialchars_decode(substr($url,$pos+1)),$parms);
      $parms['redirect_to'] = $site;
      $opts  = http_build_query($parms,'tcc_');
      $url   = $base.'?'.htmlspecialchars($opts);
    }
    #log_entry(0,"logout filter 2 - url: $url");
    return $url;
  }
  add_filter('logout_url', 'tcc_logout_url', 10, 2);
}
