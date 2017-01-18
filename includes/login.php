<?php

if (!WP_DEBUG) { // Source?
  add_filter('login_errors',create_function('$a',"return null;"));
}

if (!function_exists('tcc_admin_login_redirect')) {
	function tcc_admin_login_redirect($redirect_to,$request,$user) {
		if (!$user)                       { return home_url(); }
		if (!is_object($user))            { log_entry('user var is not an object',$user,'dump');  return $redirect_to; }
		if (get_class($user)=='WP_Error') { return $redirect_to; }
		$from = wp_get_referer();
#    if (!(strpos($from,'wp-admin')===false)) return $from;
#    if (!in_array("administrator",$user->roles)) return home_url();
		$user_id = get_current_user_id();
		if ($user_id===1) { return $redirect_to; }
		return home_url();
#		return $redirect_to;
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
        <div class="text-center"><?php
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
      $formclass = (!$navbar) ? "login-form" : 'navbar-form'.(($right) ? ' navbar-right' : ''); ?>
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
        </div>
        <button type="submit" id="wp-submit" class="btn btn-fluidity" name="wp-submit"><i class="fa fa-sign-in"></i> <?php echo $signin; ?> </button><?php
        if (get_page_by_title('Lost Password'))
          $tooltip = __('Request new password','tcc-fluid');
          echo "<a class='lost-password pull-right' href='".wp_lostpassword_url(home_url() )."' title='$tooltip'><small>$lost</small></a>"; ?>
      </form><?php
    }
  }
}

if (!function_exists('tcc_logout_url')) {
  function tcc_logout_url($url, $redirect) {
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
    return $url;
  }
  add_filter('logout_url', 'tcc_logout_url', 10, 2);
}

if (!function_exists('tcc_admin_howdy')) {
	#	http://www.wpbeginner.com/wp-tutorials/how-to-change-the-howdy-text-in-wordpress-3-3-admin-bar/
	#	https://premium.wpmudev.org/forums/topic/change-howdy-manually
	#	http://www.hongkiat.com/blog/wordpress-howdy-customized/
	function tcc_admin_howdy( WP_Admin_Bar $wp_admin_bar ) {
		$user_id      = get_current_user_id();
		if ( 0 != $user_id ) {
if (!function_exists('tcc_holiday_greeting')) {
require_once('misc.php'); // FIXME:  wtf?
log_entry('tcc_holiday_greeting missing');
}
if (!function_exists('tcc_holiday_greeting')) { return; }
			/* Add the "My Account" menu */
			$current = wp_get_current_user();
			$profile = get_edit_profile_url( $user_id );
			$avatar  = get_avatar( $user_id, 28 );
			$text    = ($user_id===1) ? __('Your Royal Highness','tcc-fluid') : tcc_holiday_greeting();
			$howdy   = sprintf( _x('%1$s, %2$s','greetings text, user name','tcc-fluid'), $text, $current->display_name );
			$class   = empty( $avatar ) ? '' : 'with-avatar';
			$args    = array('id'     => 'my-account',
			                 'parent' => 'top-secondary',
			                 'title'  => $howdy . $avatar,
			                 'href'   => $profile,
			                 'meta'   => array( 'class' => $class,
			                                    'title' => __('My Account','tcc-fluid') ) );
			$wp_admin_bar->add_menu( $args );
		}
	}
	add_action('admin_bar_menu', 'tcc_admin_howdy', 11 );
}
