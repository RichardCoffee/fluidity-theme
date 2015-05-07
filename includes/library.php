<?php

// Use bootstrap's clearfix
if (!function_exists('apply_clearfix')) {
  function apply_clearfix($args) {
    $defs = array('lg'=>0,'md'=>0,'sm'=>0,'xs'=>0);
    $args = wp_parse_args($args,$defs);
    if (empty($args['cnt'])) return;
    extract($args);
    if ($lg && ($cnt%(intval((12/$lg)))==0)) echo "<div class='clearfix visible-lg-block'></div>";
    if ($md && ($cnt%(intval((12/$md)))==0)) echo "<div class='clearfix visible-md-block'></div>";
    if ($sm && ($cnt%(intval((12/$sm)))==0)) echo "<div class='clearfix visible-sm-block'></div>";
    if ($xs && ($cnt%(intval((12/$xs)))==0)) echo "<div class='clearfix visible-xs-block'></div>";
  }
}

// Limit length of title string
if (!function_exists('browser_title')) {
  function browser_title($title,$sep) {
    if (is_feed()) return $title;
    $test = get_bloginfo('name');
    $spot = strpos($title,$test);
    if ($spot) {
      $new = substr($title,0,$spot);
      $title = $new.' > '.$test;
    }
    return $title;
  }
  add_filter('wp_title','browser_title',10,2);
}

// convert user data to flat object
if (!function_exists('convert_user_meta')) {
  function convert_user_meta($ID) {
    $data = get_user_meta($ID);
    $out  = new stdClass;
    foreach($data as $key=>$meta) {
      $out->$key = $meta[0];
    }
    return $out;
  }
}

// get term name string
if (!function_exists('get_term_name')) {
  function get_term_name($tax,$slug) {
    $term = get_term_by('slug',$slug,$tax);
    if ($term) return $term->name;
    return '';
  }
}

if (!function_exists('load_sidebar')) {
  function load_sidebar($sidebars) {
    foreach($sidebars as $sidebar) {
      if (is_active_sidebar($sidebar)) {
        if (dynamic_sidebar($sidebar)) return true;
      }
    }
    return false;
  }
}

// https://codex.wordpress.org/Using_Gravatars
function get_valid_gravatar($email,$size=96) {
  // Craft a potential url and test its headers
  $hash = md5(strtolower(trim($email)));
  $uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
  $headers = @get_headers($uri);
  if (!preg_match("|200|", $headers[0])) {
    $avatar = FALSE;
  } else {
    $avatar = get_avatar($email,$size);
  }
  return $avatar;
}

// http://www.tammyhartdesigns.com/tutorials/wordpress-how-to-determine-if-a-certain-page-exists
if (!function_exists('page_exists')) {
  function page_exists($search='') {
    $pages = get_pages();
    foreach ($pages as $page) {
      if ($page->post_name==$search)
        return true;
    }
    return false;
  }
}

//  Uses earliest published post to generate copyright date
if (!function_exists('site_copyright_dates')) {
  function site_copyright_dates() {
    global $wpdb;
    $output = '';
    $select = "SELECT YEAR(min(post_date_gmt)) AS firstdate, YEAR(max(post_date_gmt)) AS lastdate FROM $wpdb->posts WHERE post_status = 'publish'";
    $copyright_dates = $wpdb->get_results($select);
    if($copyright_dates) {
      $output = "&copy; ".$copyright_dates[0]->firstdate;
      if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
        $output .= '-'.$copyright_dates[0]->lastdate;
      }
    }
    return $output;
  }
}

/*  Debugging functions  */

// https://docs.dev4press.com/tutorial/wordpress/debug-wordpress-rewrite-rules-matching/
if (!function_exists('debug_rewrite_rules')) {
  function debug_rewrite_rules() {
    global $wp_rewrite;
    echo '<div>';
    if (!empty($wp_rewrite->rules)) {
      echo '<h5>Rewrite Rules</h5>';
      echo '<table><thead><tr>';
      echo '<td>Rule</td><td>Rewrite</td>';
      echo '</tr></thead><tbody>';
      foreach ($wp_rewrite->rules as $name => $value) {
        echo '<tr><td>'.$name.'</td><td>'.$value.'</td></tr>';
      }
      echo '</tbody></table>';
    } else {
      echo 'No rules defined.';
    }
    echo '</div>';
  }
}

// source unknown
if (!function_exists('list_filter_hooks')) {
  function list_filter_hooks( $hook = '' ) {
    global $wp_filter;
    $hooks = isset( $wp_filter[$hook] ) ? $wp_filter[$hook] : array();
    $hooks = call_user_func_array( 'array_merge', $hooks );
    foreach( $hooks as &$item ) {
      // function name as string or static class method eg. 'Foo::Bar'
      if ( is_string( $item['function'] ) ) {
        $ref = strpos( $item['function'], '::' ) ? new ReflectionClass( strstr( $item['function'], '::', true ) ) : new ReflectionFunction( $item['function'] );
        $item['file'] = $ref->getFileName();
        $item['line'] = get_class( $ref ) == 'ReflectionFunction' ? $ref->getStartLine()
                  : $ref->getMethod( substr( $item['function'], strpos( $item['function'], '::' ) + 2 ) )->getStartLine();
      // array( object, method ), array( string object, method ), array( string object, string 'parent::method' )
      } elseif ( is_array( $item['function'] ) ) {
        $ref = new ReflectionClass( $item['function'][0] );
        // $item['function'][0] is a reference to existing object
        $item['function'] = array(
                  is_object($item['function'][0]) ? get_class($item['function'][0]) : $item['function'][0],
                  $item['function'][1] );
        $item['file'] = $ref->getFileName();
        $item['line'] = strpos( $item['function'][1], '::' )
                  ? $ref->getParentClass()->getMethod( substr( $item['function'][1], strpos( $item['function'][1], '::' ) + 2 ) )->getStartLine()
                  : $ref->getMethod( $item['function'][1] )->getStartLine();
      // closures
      } elseif (is_callable( $item['function'])) {
        $ref = new ReflectionFunction($item['function']);
        $item['function'] = get_class($item['function']);
        $item['file']     = $ref->getFileName();
        $item['line']     = $ref->getStartLine();
      }
    }
    log_entry($hooks);
  #  return $hooks;
  }
  #add_action('wp_footer','tcc_list_hooks');
}

// generate log entry, with comment
if (!function_exists('log_entry')) {
  function log_entry($message,$mess2='') {
    if (WP_DEBUG) {
      if (is_array($message) || is_object($message)) {
        error_log(print_r($message, true));
      } else {
        error_log($message);
      }
      if ($mess2) log_entry($mess2);
    }
  }
}

//  show data inline
if (!function_exists('showme')) {
  function showme($title,$data) {
    if (WP_DEBUG) { ?>
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading" data-collapse="1">
            <h3 class="panel-title"><?php
              echo $title; ?>
            </h3>
          </div>
          <div class="panel-body">
            <pre><?php
              print_r($data); ?>
            </pre>
          </div>
        </div>
      </div><?php
    }
  }
}

// another log entry function
if (!function_exists('tcc_log_entry')) {
  function tcc_log_entry($message,$mess2='') {
    if (WP_DEBUG) {
      if (is_array($message) || is_object($message)) {
        error_log(print_r($message, true));
      } else {
        error_log($message);
      }
      if ($mess2) tcc_log_entry($mess2);
    }
  }
} else {
  // track file load order
  log_entry('pre-existing tcc_log_entry_function'.who_am_i(__FILE__));
}

if (!function_exists('who_am_i')) {
  //  This function is for debugging purposes only
  function who_am_i($file) {
    static $flag = ''; // give capability to turn this off via a flag file
    if (empty($flag)) $flag = (file_exists(WP_CONTENT_DIR.'/who_am_i.flg')) ? 'yes' : 'no';
    if (WP_DEBUG && ($flag=='yes')) {
      $display = $file;
      $pos = strpos($file,'wp-content');
      if ($pos) {
        $display = substr($file,$pos+10);
      }
      echo "<p>$display</p>";
    }
  }
}
