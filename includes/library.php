<?php

// Use bootstrap's clearfix
if (!function_exists('tcc_apply_clearfix')) {
  function tcc_apply_clearfix( $args ) {
    $defs = array('lg'=>0,'md'=>0,'sm'=>0,'xs'=>0,'cnt'=>0);
    $args = wp_parse_args($args,$defs);
    if (empty($args['cnt'])) return;
    extract($defs);
    extract($args,EXTR_IF_EXISTS);
    if ($lg && ($cnt%(intval((12/$lg)))==0)) echo "<div class='clearfix visible-lg-block'></div>";
    if ($md && ($cnt%(intval((12/$md)))==0)) echo "<div class='clearfix visible-md-block'></div>";
    if ($sm && ($cnt%(intval((12/$sm)))==0)) echo "<div class='clearfix visible-sm-block'></div>";
    if ($xs && ($cnt%(intval((12/$xs)))==0)) echo "<div class='clearfix visible-xs-block'></div>";
  }
}

if (!function_exists('tcc_bootstrap_css')) {
	function tcc_bootstrap_css( $args ) {
		$defs = array('lg'=>0,'md'=>0,'sm'=>0,'xs'=>0);
		$args = wp_parse_args($args,$defs);
		extract($defs);
		extract($args,EXTR_IF_EXISTS);
		$css = ($lg) ? " col-lg-$lg" : '';
		$css.= ($md) ? " col-md-$md" : '';
		$css.= ($sm) ? " col-sm-$sm" : '';
		$css.= ($xs) ? " col-xs-$xs" : '';
		return $css;
	}
}

if (!function_exists('container_type')) {
  function container_type( $location='post' ) {
    $css = 'container-fluid';
    if ($location=='header') {
      $css.= " nopad";
    } else if (tcc_layout('width')=='narrow') {
      $css = 'container';
    }
    $css = apply_filters('fluid_container_type',$css,$location);
    return apply_filters("fluid_{$location}_container_type",$css);
  }
}

// convert user data to flat object
if (!function_exists('convert_user_meta')) {
  function convert_user_meta( $ID ) {
    $wp_d = get_userdata($ID);
    $out  = $_wp_d->data;
    $data = get_user_meta($ID);
    foreach($data as $key=>$meta) {
      if (!isset($out->$key)) { $out->$key = $meta[0]; }
    }
    return $out;
  }
}
/*
if (!function_exists('fluid_get_post_terms')) {
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
  function wp_menu_id_by_name( $name ) {
    $menus = get_terms('nav_menu');
    foreach ($menus as $menu) {
      if($name===$menu->name) {
        return $menu->term_id;
      }
    }
    return false;
  }
}

if (!function_exists('esc_attr_ex')) {
	function esc_attr_ex( $text, $context, $domain = 'default' ) {
		echo esc_attr_x( $text, $context, $domain );
	}
}

if (!function_exists('esc_html_ex')) {
	function esc_html_ex( $text, $context, $domain = 'default' ) {
		echo esc_html_x( $text, $context, $domain );
	}
}

#  https://developer.wordpress.org/themes/basics/template-hierarchy/
if (!function_exists('author_role_template')) {
  function author_role_template( $templates ) {
    $author = get_queried_object();
    if ($author && isset($author->roles)) {
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
    }
    return $templates;
  }
  add_filter( 'author_template', 'author_role_template' );
}

#	get featured image url
#	needs postID if used outside of loop
if (!function_exists('get_featured_url')) {
	function get_featured_url( $postID=null ) {
		$imgURL = null; # 'invalid post ID passed to get_featured_url';
		// FIXME: postID should be able to be the post object
		$postID = (intval($postID,10)>0) ? intval($postID,10) : null;
      if ($postID && has_post_thumbnail($postID)) {
         $imgID  = get_post_thumbnail_id($postID);
         $imgURL = wp_get_attachment_url($imgID);
      }
		return $imgURL;
	}
}

#  get term name string
if (!function_exists('get_term_name')) {
  function get_term_name( $tax, $slug ) {
    $term = get_term_by('slug',$slug,$tax);
    if ($term) return $term->name;
    return '';
  }
}

if (!function_exists('get_valid_gravatar')) {
  #  https://codex.wordpress.org/Using_Gravatars
  function get_valid_gravatar( $email, $size=96 ) {
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

if (!function_exists('in_action')) {
	function in_action() {
		$trace = debug_backTrace();
		$type  = '';
		foreach( $trace as $step ) {
			if ( isset( $step['function'] ) && ( $step['function'] === 'do_action' ) && ( ! isset( $step['class'] ) ) ) {
				return $step['args'][0];
			}
		}
		return false;
	}
}

#	http://stackoverflow.com/questions/14348470/is-ajax-in-wordpress
if (!function_exists('is_ajax')) {
	function is_ajax() {
		return (defined('DOING_AJAX') && DOING_AJAX) ? true : false;
	}
}

if (!function_exists('sanitize_array')) {
  function sanitize_array( $array, $method='title' ) {
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


/*  Non-WordPress specific */

if (!function_exists('array_insert_after')) {
  #  http://eosrei.net/comment/287
  function array_insert_after( $array, $key, $new_key, $new_value) {
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

function js_decode($incoming) {
  $return = $incoming;
  if (is_string($return)) {
    $object = json_decode($incoming);
    if (json_last_error() === 0) {
      $return = $object;
    } else {
      $return = json_last_error_msg();
    }
  }
  return $return;
}

// http://php.net/manual/en/function.json-last-error-msg.php
if (!function_exists('json_last_error_msg')) {
  function json_last_error_msg() {
    static $errors = array(
      JSON_ERROR_NONE             => null,
      JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
      JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
      JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
      JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
      JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded',
      JSON_ERROR_RECURSION        => 'Contains recursion references that cannot be encoded',
      JSON_ERROR_INF_OR_NAN       => 'Contains a value of NAN or INF, which cannot be encoded',
      JSON_ERROR_UNSUPPORTED_TYPE => 'Contains a value of an unsupported type'
    );
    $error = json_last_error();
    return array_key_exists($error, $errors) ? "JSON error: {$errors[$error]}" : "Unknown JSON error ({$error})";
  }
}

function json_options() {
  return JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE;
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

#  http://stackoverflow.com/questions/5224209/wordpress-how-do-i-get-all-the-registered-functions-for-the-content-filter
if (!function_exists('list_filter_hooks')) {
  function list_filter_hooks( $hook = '' ) {
    if (WP_DEBUG) {
      global $wp_filter;
log_entry($wp_filter);
return;
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

if (!function_exists('list_template_conditions')) {
  function list_template_conditions() {
    $conds = array('is_embed'   => is_embed(),
      'embed'      => get_embed_template(),
      'is_404'     => is_404(),
      '404'        => get_404_template(),
      'is_search'  => is_search(),
      'search'     => get_search_template(),
      'is_front'   => is_front_page(),
#      'front'      => get_front_page_template(),
      'is_home'    => is_home(),
      'home'       => get_home_template(),
      'is_postype' => is_post_type_archive(),
      'postype'    => get_post_type_archive_template(),
      'is_tax'     => is_tax(),
#      'tax'        => get_taxonomy_template(),
      'is_attach'  => is_attachment(),
#      'attach'     => get_attachment_template(),
      'is_sing'    => is_single(),
      'single'     => get_single_template(),
      'is_page'    => is_page(),
      'page_templ' => get_page_template(),
      'is_singu'   => is_singular(),
#      'singular'   => get_singular_template(),
      'is_cat'     => is_category(),
      'category'   => get_category_template(),
      'is_tag'     => is_tag(),
#      'tag_templ'  => get_tag_template(),
      'is_author'  => is_author(),
      'author'     => get_author_template(),
      'is_date'    => is_date(),
#      'date_templ' => get_date_template(),
      'is_archive' => is_archive(),
      'archive'    => get_archive_template(),
      'is_paged'   => is_paged(),
#      'paged_templ'=> get_paged_template(),
      'index_templ'=> get_index_template());
    return $conds;
  }
}

#  show data inline
if (!function_exists('showme')) {
  function showme( $title, $data ) {
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
  function tellme( $string ) {
    if (WP_DEBUG) {
      echo "<p>$string</p>";
    }
  }
}

if (!function_exists('who_am_i')) {
  //  This function is for debugging purposes only
  function who_am_i( $pos=0 ) {
    if (WP_DEBUG)  {
      #static $flag = ''; // give capability to turn this off via a flag file
      #if (empty($flag)) $flag = (file_exists(WP_CONTENT_DIR.'/who_am_i.flg')) ? 'yes' : 'no';
      #if ($flag=='yes') {  #  FIXME:  make this a theme option
      static $status;
      if (empty($status)) { $status = tcc_settings('where'); }
      if ($status==='on') {
        $trace = debug_backtrace();
        $show  = $trace[$pos]['file'];
        if ($pos=strpos($show,'wp-content')) {
          $show = substr($show,$pos+10);
        }
        echo "<p>$show</p>";
      }
    }
  }
}

#$perm_struct = get_option('permalink_structure');
#log_entry("permalink structure: $perm_struct");
