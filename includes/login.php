<?php

if (!WP_DEBUG) { // Source?
  add_filter('login_errors',create_function('$a',"return null;"));
}

if (!function_exists('tcc_login_page_redirect')) {
	#	http://www.hongkiat.com/blog/wordpress-custom-loginpage/
	function tcc_login_page_redirect() {
		if (has_page('Login')) {
			$login_page  = home_url( '/login/' );
			$page_viewed = basename($_SERVER['REQUEST_URI']);
			if( $page_viewed === "wp-login.php" && $_SERVER['REQUEST_METHOD'] === 'GET') {
				wp_safe_redirect($login_page);
				exit;
			}
		}
	}
	add_action('init','tcc_login_page_redirect');
}

if (!function_exists('tcc_login_failed')) {
	function tcc_login_failed() {
		if (has_page('Login')) {
			$login_page  = home_url( '/login/' );
			wp_safe_redirect( $login_page . '?login=failed' );
			exit;
		}
	}
	add_action( 'wp_login_failed', 'tcc_login_failed' );
}

if (!function_exists('tcc_authenticate_user')) {
	function tcc_authenticate_user( $user, $username, $password ) {
		if (has_page('Login')) {
			$login_page  = home_url( '/login/' );
			if( $username == "" || $password == "" ) {
				wp_redirect( $login_page . "?login=empty" );
				exit;
			}
		}
	}
	add_filter( 'authenticate', 'tcc_authenticate_user', 1, 3);
}

if (!function_exists('tcc_get_login_form_defaults')) {
	function tcc_get_login_form_defaults() {
		$defaults = array();
		add_filter('login_form_defaults', function($args) use (&$defaults) {
			$defaults = $args;
			return $args;
		}, 1000);	#	We want to go last here
		wp_login_form( array( 'echo' => false ) );
		return $defaults;
	}
}
/*
$redirect = home_url( add_query_arg( '_', false ) ); ?>
Alternately:  global $wp; home_url(add_query_arg(array(),$wp->request)); ?>
Or:           home_url( add_query_arg( NULL, NULL ) ); ?>
Or:           global $wp; $location = add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) ); ?>
Multi-site:   $parts = parse_url( home_url() ); $current_uri = "{$parts['scheme']}://{$parts['host']}" . add_query_arg( NULL, NULL ); ?> */

if (!function_exists('tcc_login_redirect')) {
	#	https://www.longren.io/wordpress-tip-redirect-to-previous-page-after-login/
	if ( (isset($_GET['action']) && $_GET['action'] != 'logout') || (isset($_POST['login_location']) && !empty($_POST['login_location'])) ) {
		function tcc_login_redirect( $redirect_to, $request, $user ) {
			if (!$user)                       { return home_url(); }
			if (!is_object($user))            { log_entry('user var is not an object',$user,'dump');  return $redirect_to; }
			if (get_class($user)=='WP_Error') { return $redirect_to; }
			$location = (isset($_POST['login_location'])) ? esc_url_raw($_POST['login_location']) : esc_url_raw($_SERVER['HTTP_REFERER']);
log_entry('redirect_to:  '.$redirect_to,'request:  '.$request,$user,'wp_get_referer:  '.wp_get_referer(),'location:  '.$location);
			wp_safe_redirect( apply_filters( 'tcc_login_redirect', $location, $request, $user ) );
			exit;
		}
		add_filter('login_redirect', 'tcc_login_redirect', 10, 3);
	}
} //*/

if (!function_exists('tcc_admin_login_redirect')) {
	function tcc_admin_login_redirect($redirect_to,$request,$user) {
log_entry('redirect_to:  '.$redirect_to,'request:  '.$request,$user,'wp_get_referer:  '.wp_get_referer());
		if (!$user)                       { return home_url(); }
		if (!is_object($user))            { log_entry('user var is not an object',$user,'dump');  return $redirect_to; }
		if (get_class($user)=='WP_Error') { return $redirect_to; }
#		$from = wp_get_referer();
##		if (!(strpos($from,'wp-admin')===false)) return $from;
##		if (!in_array("administrator",$user->roles)) return home_url();
#		$user_id = get_current_user_id();
#		if ($user_id===1) { return $from; }
#		return home_url();
		return $redirect_to;
	}
	add_filter("tcc_login_redirect","tcc_admin_login_redirect",10,3);
} //*/

if (!function_exists('tcc_dashboard_logo') && function_exists('tcc_option')) {
  # http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
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

if (!function_exists('tcc_get_login_form_defaults')) {
	function tcc_get_login_form_defaults() {
		$defaults = array();
		add_filter('login_form_defaults', function($args) use (&$defaults) {
			$defaults = $args;
			return $args;
		}, 1000);
		wp_login_form( array( 'echo' => false ) );
		return $defaults;
	}
}

if (!function_exists('tcc_login_css')) {
  function tcc_login_css() {
    # http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
    $logo = tcc_design('logo'); // FIXME: this needs to be done some other way - get logo from customizer?
    if ($logo) {
      echo "<style type='text/css'> h1 a { background-image:url($logo) !important; }</style>";
    }
  }
#  add_action('login_head','tcc_login_css');
}

if (!function_exists('tcc_login_header_url')) {
  function tcc_login_header_url() {
    return home_url();
  }
#  add_filter('login_headerurl','tcc_login_header_url' );
}

if (!function_exists('tcc_login_header_title')) {
  function tcc_login_header_title() {
    return '';
  }
  add_filter('login_headertitle','tcc_login_header_title');
}

// FIXME:  Why is this double enclosed? init vs login_footer?
if (!function_exists('tcc_remember_me')) {
  function tcc_remember_me() {
    if (!function_exists('tcc_remember_me_checked')) {
      function tcc_remember_me_checked() {
        echo "<script>document.getElementById('rememberme').checked = true;</script>";
      }
      add_filter('login_footer','tcc_remember_me_checked');
    }
  }
#  add_action('init','tcc_remember_me');
}

if (!function_exists('tcc_login_form')) {
	function tcc_login_form( $args = array() ) {
		if (is_user_logged_in()) {
			$navbar  = false;
			extract($args,EXTR_IF_EXISTS);
			$signout = apply_filters('tcc_signout_text', esc_html__('Sign Out', 'tcc-fluid')); ?>
			<form class="<?php #echo $formclass; ?>" action="<?php #echo wp_logout_url(home_url()); ?>" method="post">
				<div class="text-center"><?php
					$action = ($navbar) ? 'tcc_navbar_signout' : 'tcc_widget_signout';
					do_action($action);
					$out  = wp_logout_url(home_url());
					$html = "<a class='btn btn-fluidity' href='$out'";
					$html.= " title='$signout'> $signout&nbsp;&nbsp;<i class='fa fa-sign-out'></i></a>";
					echo $html; ?>
				</div>
			</form><?php
		} else {
			$navbar = false;
			$right  = false;
			extract($args,EXTR_IF_EXISTS);
			$defaults = tcc_get_login_form_defaults();
			#	array mainly taken from wp-includes/general-template.php
			$tcc_form = array('echo'           => false,
			                  'redirect'       => home_url( add_query_arg( NULL, NULL ) ),
			                  'form_id'        => uniqid("login_form_"),
			                  'label_username' => apply_filters( 'tcc_login_username', __( 'Username or Email Address' ) ),
			                  'label_password' => apply_filters( 'tcc_login_userpass', __( 'Password' ) ),
#			                  'label_remember' => __( 'Remember Me' ),
			                  'label_log_in'   => apply_filters( 'tcc_signin_text',    __('Sign In',       'tcc-fluid') ),
			                  'label_lost'     => apply_filters( 'tcc_lostpw_text',    __('Lost Password', 'tcc-fluid') ),
			                  'id_username'    => uniqid("user_login_"),
			                  'id_password'    => uniqid("user_pass_"),
			                  'id_remember'    => uniqid("rememberme_"),
			                  'id_submit'      => uniqid("wp-submit_"),
#			                  'remember'       => true,
#			                  'value_username' => '',
#			                  'value_remember' => false,
			);
			$defaults = array_merge($defaults,$tcc_form);
			$args = wp_parse_args( $args, $defaults );
			extract($args);
			$remember  = ($navbar)  ? false : $remember;
			$formclass = (!$navbar) ? "login-form" : 'navbar-form navbar-login-form'.(($right) ? ' navbar-right' : ''); ?>
			<form id="<?php echo esc_attr($form_id); ?>" class="<?php echo esc_attr($formclass); ?>"
			      name="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post">
				<div class='form-group login-username'>
					<label class="sr-only" for="<?php echo esc_attr($id_username); ?>"><?php echo esc_html($label_username); ?></label>
					<input type="text" name="log" id="<?php echo esc_attr($id_username); ?>" class="form-control"
						placeholder="<?php echo esc_html($label_username); ?>" required>
				</div>
				<div class='form-group login-password'>
					<label class="sr-only" for="<?php echo esc_attr($id_password); ?>"><?php echo esc_html($label_password); ?></label>
					<input type="password" name="pwd" id="<?php echo esc_attr($id_password); ?>"
					       class="form-control" placeholder="<?php echo esc_attr($label_password); ?>" required>
				</div><?php
				if ($remember) { ?>
					<div class="checkbox login-remember">
						<label>
							<input type="checkbox" id="<?php echo esc_attr($id_remember); ?>" name="rememberme" value="forever" <?php checked($value_remember,true); ?>>&nbsp;
							<?php echo esc_html($label_remember); ?>
						</label>
					</div><?php
				} ?>
				<div class="form-group login-submit">
					<button type="submit" id="<?php echo esc_attr($id_submit); ?>" class="btn btn-fluidity" name="wp-submit"><i class="fa fa-sign-in"></i>&nbsp;
						<?php echo esc_html($label_log_in); ?>
					</button>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url_raw($redirect); ?>" />
				</div><?php //*/
#				if (get_page_by_title('Lost Password')) {
				if ( !empty( $label_lost ) && ( $lost_url=wp_lostpassword_url( home_url() ) ) ) {
					$tooltip = __('You can request a new password via this link.','tcc-fluid'); ?>
					<a class="lost-password pull-right" href="<?php echo esc_url_raw($lost_url); ?>" title="<?php echo esc_attr($tooltip); ?>">
						<small>
							<?php echo esc_html($label_lost); ?>
						</small>
					</a><?php
				} ?>
			</form><?php
		}
	}
}

if (!function_exists('tcc_login_form_defaults')) {
	function tcc_login_form_defaults( $args=array() ) {
		$args['redirect']       = apply_filters( 'tcc_login_redirect_to', home_url( add_query_arg( NULL, NULL ) ) );
		$args['form_id']        = uniqid( 'login_form_' );
		$args['label_username'] = apply_filters( 'tcc_login_username', __( 'Username or Email Address' ) );
		$args['label_password'] = apply_filters( 'tcc_login_password', __( 'Password' ) );
#		$args['label_remember'] = __( 'Remember Me' );
		$args['label_log_in']   = apply_filters( 'tcc_log_in_text',    __( 'Sign In',       'tcc-fluid' ) );
		$args['label_lostpw']   = apply_filters( 'tcc_lostpw_text',    __( 'Lost Password', 'tcc-fluid' ) );
		$args['id_username']    = uniqid( 'user_login_' );
		$args['id_password']    = uniqid( 'user_pass_'  );
		$args['id_remember']    = uniqid( 'rememberme_' );
		$args['id_submit']      = uniqid( 'wp-submit_'  );
#		$args['remember']       = true;
#		$args['value_username'] = '';
#		$args['value_remember'] = false;
		return $args;
	}
	add_action('login_form_defaults','tcc_login_form_defaults');
}

if (!function_exists('tcc_logout_url')) {
  #  force redirect for logout url
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
	#	Note:  I saw some posts that recommend changing this via gettext.  I would regard that as bad practice.
	function tcc_admin_howdy( WP_Admin_Bar $wp_admin_bar ) {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			/* Add the "My Account" menu */
			$current = wp_get_current_user();
			$profile = get_edit_profile_url( $user_id );
			$avatar  = get_avatar( $user_id, 28 );
			$text    = ($user_id===1) ? __('Your Royal Highness','tcc-fluid') : tcc_holiday_greeting();
			$howdy   = sprintf( _x('%1$s, %2$s','text greeting, user name','tcc-fluid'), $text, $current->display_name );
			$class   = (empty($avatar)) ? '' : 'with-avatar';
			$args    = array('id'     => 'my-account',
			                 'parent' => 'top-secondary',
			                 'title'  => esc_html($howdy) . $avatar,
			                 'href'   => $profile,
			                 'meta'   => array( 'class' => $class,
			                                    'title' => esc_html__('My Account','tcc-fluid') ) );
			$wp_admin_bar->add_menu( $args );
		}
	}
	add_action('admin_bar_menu', 'tcc_admin_howdy', 11 );
}
