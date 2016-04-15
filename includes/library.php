<?php

// Use bootstrap's clearfix
if (!function_exists('tcc_apply_clearfix')) {
  function tcc_apply_clearfix($args) {
    $defs = array('lg'=>0,'md'=>0,'sm'=>0,'xs'=>0);
    $args = wp_parse_args($args,$defs);
    if (empty($args['cnt'])) return;
    extract($args);  #  $defs array + $cnt
    if ($lg && ($cnt%(intval((12/$lg)))==0)) echo "<div class='clearfix visible-lg-block'></div>";
    if ($md && ($cnt%(intval((12/$md)))==0)) echo "<div class='clearfix visible-md-block'></div>";
    if ($sm && ($cnt%(intval((12/$sm)))==0)) echo "<div class='clearfix visible-sm-block'></div>";
    if ($xs && ($cnt%(intval((12/$xs)))==0)) echo "<div class='clearfix visible-xs-block'></div>";
  }
}

if (!function_exists('tcc_browser_body_class')) {
  // http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/
  function tcc_browser_body_class($classes) { // FIXME:  Ummm, no.  check user-agent string instead
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    if($is_lynx)       $classes[] = 'lynx';
    elseif($is_gecko)  $classes[] = 'gecko';
    elseif($is_opera)  $classes[] = 'opera';
    elseif($is_NS4)    $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE)     $classes[] = 'ie';
    else               $classes[] = 'unknown';
    if($is_iphone)     $classes[] = 'iphone';
    return $classes;
  }
  add_filter('body_class','tcc_browser_body_class');
}

if (!function_exists('fluid_color_scheme')) {
  function fluid_color_scheme() {
    $color = tcc_color_scheme();
    if (file_exists(get_template_directory() . "/css/colors/$color.css")) { return $color; }
    if (file_exists(get_stylesheet_directory()."/css/colors/$color.css")) { return $color; }
    return '';
  }
}

if (!function_exists('container_type')) {
  function container_type($location='post') {
    if ($location=='fluid-header') return "container-fluid no-padding";
    return (tcc_layout('width')=='narrow') ? 'container' : 'container-fluid';
  }
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

/*if (!function_exists('fluid_get_post_terms')) {
  function fluid_get_post_terms($post_id,$taxonomy,$args) {
    $terms = array();
    if ($post_id) {
      if (is_string($taxonomy) && ($taxonomy=='all')) {
        $post = get_post($post_id);
      }
      foreach((array)$taxonomy as $tax) {
        $taxterms = wp_get_post_terms($post_id,$tax,$args);
      }
    }
    return $terms;
  }
} //*/

if (!function_exists('wp_menu_id_by_name')) {
  // http://wordpress.stackexchange.com/questions/104301/get-menu-id-using-its-name
  function wp_menu_id_by_name($name) {
    $menus = get_terms('nav_menu');
    foreach ($menus as $menu) {
      if($name===$menu->name) {
        return $menu->term_id;
      }
    }
    return false;
  }
}

#  https://developer.wordpress.org/themes/basics/template-hierarchy/
if (!function_exists('author_role_template')) {
  function author_role_template( $templates = '' ) {
    $author = get_queried_object();
    $role = $author->roles[0];
    if ( ! is_array( $templates ) && ! empty( $templates ) ) {
      $templates = locate_template( array( "author-$role.php", $templates ), false );
    } elseif ( empty( $templates ) ) {
      $templates = locate_template( "author-$role.php", false );
    } else {
      $new_template = locate_template( array( "author-$role.php" ) );
      if ( ! empty( $new_template ) ) {
        array_unshift( $templates, $new_template );
      }
    }
    return $templates;
  }
  add_filter( 'author_template', 'author_role_template' );
}

#  get term name string
if (!function_exists('get_term_name')) {
  function get_term_name($tax,$slug) {
    $term = get_term_by('slug',$slug,$tax);
    if ($term) return $term->name;
    return '';
  }
}

#  https://codex.wordpress.org/Using_Gravatars
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

if (!function_exists('fluid_edit_post_link')) {
  #  can only be used inside The Loop
  function fluid_edit_post_link($separator=' ') {
    $title  = the_title( '<span class="screen-reader-text">"', '"</span>', false );
    $string = sprintf( esc_attr_x( 'Edit %s', 'Name of current post', 'tcc-fluid' ), $title );
    edit_post_link( '{'.$string.'}', $separator.'<span class="edit-link">', '</span>' );
  }
}

if (!function_exists('fluid_navigation')) {
  function fluid_navigation($suffix='above') {
    global $wp_query;
    if ($wp_query->max_num_pages>1) {
      $older = esc_html__('Older posts','tcc-fluid');
      $newer = esc_html__('Newer posts','tcc-fluid'); ?>
      <div id="nav-<?php echo $suffix; ?>" class="navigation">
        <h2 class="screen-reader-text"><?php
          esc_html_e( 'Post Navigation', 'tcc-fluid' ); ?>
        </h2>
        <div class="nav-previous pull-left"><?php
          next_posts_link('<span class="meta-nav">&larr;</span> '.$older); ?>
        </div>
        <div class="nav-next pull-right"><?php
          previous_posts_link($newer.' <span class="meta-nav">&rarr;</span>'); ?>
        </div>
      </div><?php
    }
  }
}

if (!function_exists('fluid_thumbnail')) {
  #  can only be used inside The Loop
  function fluid_thumbnail() {
    $css = (tcc_layout('sidebar')=='none') ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-8 col-md-8 col-sm-12 col-xs-12'; ?>
    <div class='<?php echo $css; ?> logo'><?php
       the_post_thumbnail(); ?>
    </div><?php
  }
}

if (!function_exists('get_term_name')) {
  #  get term name string
  function get_term_name($tax,$slug) {
    $term = get_term_by('slug',$slug,$tax);
    if ($term) return $term->name;
    return '';
  }
}

if (!function_exists('get_valid_gravatar')) {
  #  https://codex.wordpress.org/Using_Gravatars
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
}

if (!function_exists('next_post_exists')) {
  #  can only be used within The Loop
  function next_post_exists() {
    global $wp_query;
    if ( $wp_query->current_post + 1 < $wp_query->post_count ) {
      return true;
    }
    return false;
  }
}

#  http://www.tammyhartdesigns.com/tutorials/wordpress-how-to-determine-if-a-certain-page-exists
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

if (!function_exists('sanitize_array')) {
  function sanitize_array($array,$method='title') {
    $output = array();
    $func   = "sanitize_$method";
    if ((array)$array==$array && function_exists($func)) {
      foreach($array as $key=>$data) {
        if ((array)$data==$data) {
          $output[$key] = sanitize_array($data,$method); // recurse
        } elseif ((string)$data==$data) {
          $output[$key] = $func($data);
        } else { $output[$key] = $data; }
      }
    } else { $output = $array; }
    return $output;
  }
}


/*  Non-wordPress specific */

if (!function_exists('array_insert_after')) {
  #  http://eosrei.net/comment/287
  function array_insert_after($array, $key, $new_key, $new_value) {
    if (array_key_exists($key, $array)) {
      $new = array();
      foreach ($array as $k => $value) {
        $new[$k] = $value;
        if ($k === $key) {
          $new[$new_key] = $new_value;
        }
      }
      return $new;
    }
    return $array;
  }
}

/*  Debugging functions  */

#  https://docs.dev4press.com/tutorial/wordpress/debug-wordpress-rewrite-rules-matching/
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
    if (WP_DEBUG) {
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
      #return $hooks;
    }
  }
  #add_action('wp_footer','list_filter_hooks');
}

#  generate log entry, with comment
if (!function_exists('log_entry')) {
  function log_entry() {
    if (WP_DEBUG) {
      foreach (func_get_args() as $message) {
        if (is_array($message) || is_object($message)) {
          error_log(print_r($message, true));
        } else {
          error_log($message);
        }
      }
    }
  }
}

#  show data inline
if (!function_exists('showme')) {
  function showme($title,$data) {
    if (WP_DEBUG) { ?>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

#  show string inline
if (!function_exists('tellme')) {
  function tellme($string) {
    if (WP_DEBUG) {
      echo "<p>$string</p>";
    }
  }
}

if (!function_exists('who_am_i')) {
  //  This function is for debugging purposes only
  function who_am_i($file='') {
    if (WP_DEBUG)  {
      static $flag = ''; // give capability to turn this off via a flag file
      if (empty($flag)) $flag = (file_exists(WP_CONTENT_DIR.'/who_am_i.flg')) ? 'yes' : 'no';
      if ($flag=='yes') {
        if (empty($file)) {
          $trace = debug_backtrace();
          $file  = $trace[0]['file'];
        }
        $display = $file;
        if ($pos=strpos($file,'wp-content')) {
          $display = substr($file,$pos+10);
        }
        echo "<p>$display</p>";
      }
    }
  }
}

#$perm_struct = get_option('permalink_structure');
#log_entry("permalink structure: $perm_struct");
