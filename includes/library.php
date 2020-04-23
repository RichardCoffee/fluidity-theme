<?php

/**
 * Fluidity function library
 *
 * @package Fluidity
 * @subpackage Library
 */

/**
 *  array_column() introduced in PHP 7.0.0
 *
 * @since 20200315
 * @param array  $input   Array to get the column from.
 * @param string $column  Key to retrieve.
 * @return array          Contains the column requested or an empty array.
 */
if ( ! function_exists( 'array_column' ) ) {
	function array_column( array $input, $column ) {
		$result = array();
		foreach( $input as $item ) {
			if ( array_key_exists( $column, $item ) ) {
				$result[] = $item[ $column ];
			}
		}
		return $result;
	}
}

/**
 *  array_key_first() introduced in PHP 7.3.0
 *
 * @since 20200315
 * @param array $arr  Input array.
 * @return string     First key of the array.
 */
if ( ! function_exists( 'array_key_first' ) ) {
	function array_key_first( array $arr ) {
		foreach( $arr as $key => $item ) return $key;
		return null;
	}
}

/**
 *  array_key_last() introduced in PHP 7.3.0
 *
 * @since 20200315
 * @param array $arr  Input array.
 * @return string     Last key of the array.
 */
if ( ! function_exists( 'array_key_last' ) ) {
	function array_key_last( array $arr ) {
		return array_key_first( array_reverse( $arr, true ) );
	}
}

/**
 *  Returns the key after the needle, or false otherwise.
 *
 * @since 20200315
 * @param string $needle  Key in the search array.
 * @param array  $search  Array to get the next key from.
 * @param bool   $strict  Whether to make a strict type comparison.
 * @return string|bool    The key following the needle, or boolean false.
 */
if ( ! function_exists( 'array_key_next' ) ) {
	function array_key_next( $needle, $search, $strict = false ) {
		if ( empty( $needle ) ) return false;
		if ( empty( $search ) ) return false;
		if ( ! is_array( $search ) ) return false;
		$keys = array_keys( $search );
		$spot = array_search( $needle, $keys, $strict );
		if ( $spot === false ) return false;
		$spot = ( $spot + 1 === count( $keys ) ) ? 0 : $spot + 1;
		return $keys[ $spot ];
	}
}

/**
 * Remove a key/value pair from an associative array, using the key.
 *
 * @since 20170227
 * @param string $needle    Key to be deleted.
 * @param array  $haystack  Associative array
 * @return array
 */
if ( ! function_exists( 'array_remove_value' ) ) {
	function array_remove_value( $needle, $haystack ) {
		if ( $needle && is_string( $needle ) && $haystack && is_array( $haystack ) ) {
			if( ( $key = array_search( $needle, $haystack ) ) !== false ) {
				unset( $haystack[ $key ] );
			}
		}
		return $haystack;
	}
}

/**
 *  Replace a key in an array
 *
 * @since 20200315
 * @param array  $arr  Array that needs a key replaced
 * @param string $old  Key to be replaced.
 * @param string $new  New key value.
 * @return array       Array with replaced key.
 */
if ( ! function_exists( 'array_key_replace' ) ) {
	function array_key_replace( array $arr, $old, $new ) {
		if ( ! array_key_exists( $old, $arr ) ) return $array;
		$keys = array_keys( $arr );
		$pos  = array_search( $old, $keys );
		if ( $pos === false ) return $arr;
		$keys[ $pos ] = $new;
		return array_combine( $keys, $arr );
	}
}

/**
 * checks to see if a particular filter function has already been registered
 *
 * @since 20180425
 * @param string $filter name of the filter
 * @param string|array $function the callable to check for
 * @param integer $priority priority to be assigned to the filter
 * @param integer $number_of_arguments the number of arguments the callable expects to recieve
 */
if ( ! function_exists( 'check_filter' ) ) {
	function check_filter( $filter, $function, $priority = 10, $number_of_arguments = 1 ) {
		if ( has_filter( $filter, $function ) === false ) {
			add_filter( $filter, $function, $priority, $number_of_arguments );
		}
	}
}

/**
 * Returns a css class to be added to the main div
 *
 * @todo re-examine the reasons why this function exists and see if it needs to be removed or changed
 *
 * @since 1.0.0
 *
 * @param string $location
 * @return string css class
 */
if ( ! function_exists( 'container_type' ) ) {
	function container_type( $location = 'post' ) {
		$css = 'container-fluid';
		if ( $location === 'header' ) {
			$css .= " nopad";
		} else if ( get_theme_mod( 'behavior_screen_width', 'narrow' ) === 'narrow' ) {
			$css = 'container';
		}
		/**
		 * Filters the container css class
		 *
		 * @since 1.0.0
		 *
		 * @param string $css the css to be filtered
		 * @param string $location
		 */
		$css = apply_filters( 'fluid_container_type', $css, $location );
		/**
		 * Filters the container css class for a specific location
		 *
		 * @since 1.0.0
		 *
		 * @param string $css the css to be filtered
		 */
		return apply_filters( "fluid_{$location}_container_type", $css );
	}
}

/**
 * Convert user meta data into a flat object
 *
 * @todo find a wordpress function that does this, if it exists
 *
 * @since 1.0.0
 *
 * @param int $userID
 * @return object $out
 */
if ( ! function_exists( 'convert_user_meta' ) ) {
	function convert_user_meta( $userID ) {
		$wp_d = get_userdata( $userID );
		$out  = $_wp_d->data;
		$data = get_user_meta( $userID );
		foreach( $data as $key => $meta ) {
			if ( ! isset( $out->$key ) ) {
				$out->$key = $meta[0];
			}
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

/**
 * Echo an escaped attribute string
 *
 * @since 1.0.0
 *
 * @param string $string
 */
if ( ! function_exists('e_esc_attr') ) {
	function e_esc_attr( $string ) {
		echo esc_attr( $string );
	}
}

/**
 * Echo an escaped html string
 *
 * @since 1.0.0
 *
 * @param string $string
 */
if ( ! function_exists('e_esc_html') ) {
	function e_esc_html( $string ) {
		echo esc_html( $string );
	}
}

/**
 * Echo a translated escaped attribute string, with context string
 *
 * @since 1.0.0
 *
 * @param string $text
 * @param string $context
 * @param string $domain
 */
if ( ! function_exists( 'esc_attr_ex' ) ) {
	function esc_attr_ex( $text, $context, $domain = 'default' ) {
		$translation = translate_with_gettext_context( $text, $context, $domain );
		echo esc_attr( $translation );
	}
}

/**
 * Echo a translated escaped html string, with context string
 *
 * @since 1.0.0
 *
 * @param string $text
 * @param string $context
 * @param string $domain
 */
if ( ! function_exists( 'esc_html_ex' ) ) {
	function esc_html_ex( $text, $context, $domain = 'default' ) {
		$translation = translate_with_gettext_context( $text, $context, $domain );
		echo esc_html( $translation );
	}
}

/**
 * Return a translated escaped html string, with numeric context
 *
 * @since 1.0.0
 *
 * @param string $single
 * @param string $plural
 * @param int $number
 * @param string $domain
 * @return string
 */
if ( ! function_exists( 'esc_html_n' ) ) {
	function esc_html_n( $single, $plural, $number, $domain = 'default' ) {
		$translations = get_translations_for_domain( $domain );
		$translation  = $translations->translate_plural( $single, $plural, $number );
		return esc_html( apply_filters( 'ngettext', $translation, $single, $plural, $number, $domain ) );
	}
}

/**
 * Return a translated escaped html string, with both numeric and conceptional context
 *
 * @since 1.0.0
 *
 * @param string $single
 * @param string $plural
 * @param int $number
 * @param string $context
 * @param string $domain
 */
if ( ! function_exists( 'esc_html_nx' ) ) {
	function esc_html_nx( $single, $plural, $number, $context, $domain = 'default' ) {
		$translations = get_translations_for_domain( $domain );
		$translation  = $translations->translate_plural( $single, $plural, $number, $context );
		return esc_html( apply_filters( 'ngettext_with_context', $translation, $single, $plural, $number, $context, $domain ) );
	}
}

/**
 * Echo a translated escaped html string, with both numeric and conceptional context
 *
 * @param string $single
 * @param string $plural
 * @param int $number
 * @param string $context
 * @param string $domain
 */
if ( ! function_exists( 'esc_html_enx' ) ) {
	function esc_html_enx( $single, $plural, $number, $context, $domain = 'default' ) {
		echo esc_html_nx( $single, $plural, $number, $context, $domain );
	}
}

#  https://developer.wordpress.org/themes/basics/template-hierarchy/
if (!function_exists('author_role_template')) {
// TODO: move this into an author base class re: Agents
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

/**
 * get term name
 *
 * @param string $tax
 * @param string $slug
 * @return string
 */
if ( ! function_exists( 'get_term_name' ) ) {
	function get_term_name( $tax, $slug ) {
		$term = get_term_by( 'slug', $slug, $tax );
		if ( $term ) {
			return $term->name;
		}
		return '';
	}
}

/**
 * get user gravator
 *
 * @link https://codex.wordpress.org/Using_Gravatars
 * @param string $email
 * @param int $size
 * @return string
 */
if ( ! function_exists( 'get_valid_gravatar' ) ) {
	function get_valid_gravatar( $email, $size=96 ) {
		// Craft a potential url and test its headers
		$hash = md5( strtolower( trim( $email ) ) );
		$uri  = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
		$headers = @get_headers( $uri );
		if ( ! preg_match( "|200|", $headers[0] ) ) {
			$avatar = FALSE;
		} else {
			$avatar = get_avatar( $email, $size );
		}
		return $avatar;
	}
}

/**
 * get number of widgets in a sidebar
 *
 * @since 20180430
 * @link https://halgatewood.com/wordpress-function-get-widget-count-of-a-sidebar
 * @link https://wordpress.stackexchange.com/questions/54162/get-number-of-widgets-in-sidebar
 * @param string $sidebar_id
 * @return integer
 */
if ( ! function_exists( 'get_widget_count' ) ) {
	function get_widget_count( $sidebar_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		return (int) count( (array) $sidebars_widgets[ $sidebar_id ] );
	}
}

/**
 *  check for ajax
 *
 * @link http://stackoverflow.com/questions/14348470/is-ajax-in-wordpress
 * @return bool
 */
if ( ! function_exists( 'is_ajax' ) ) {
	function is_ajax() {
		return ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? true : false;
	}
}

/**
 *  check if an array is an assocative array
 *
 * @since 20180410
 * @link https://stackoverflow.com/questions/5996749/determine-whether-an-array-is-associative-hash-or-not
 * @param array $array
 * @return bool
 */
if ( ! function_exists( 'is_assoc' ) ) {
	function is_assoc( array $array ) {
		// Keys of the array
		$keys = array_keys($array);
		// If the array keys of the keys match the keys, then the array must
		// not be associative (e.g. the keys array looked like {0:0, 1:1...}).
		return array_keys( $keys ) !== $keys;
	}
}

if ( ! function_exists( 'is_rest' ) ) {
	/**
	 * Checks if the current request is a WP REST API request.
	 *
	 * Case #1: After WP_REST_Request initialisation
	 * Case #2: Support "plain" permalink settings
	 * Case #3: URL Path begins with wp-json/ (your REST prefix)
	 *          Also supports WP installations in subfolders
	 *
	 * @author matzeeable
	 * @return boolean
	 * @link https://wordpress.stackexchange.com/questions/221202/does-something-like-is-rest-exist
	 */
	function is_rest() {
		$prefix = rest_get_url_prefix( );
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST // (#1)
			|| isset( $_GET['rest_route'] ) // (#2)
			&& strpos( trim( $_GET['rest_route'], '\\/' ), $prefix , 0 ) === 0)
				return true;

		// (#3)
		$rest_url = wp_parse_url( site_url( $prefix ) );
		$current_url = wp_parse_url( add_query_arg( array( ) ) );
		return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	}
}


/**
 * sanitize an array, using a sanitize_* function
 *
 * @param array $array
 * @param string $method
 * @return array
 */
if ( ! function_exists( 'sanitize_array' ) ) {
	function sanitize_array( $array, $method = 'title' ) {
		$output = array();
		$func   = "sanitize_$method";
		if ( (array)$array == $array && function_exists( $func ) ) {
			foreach( $array as $key => $data ) {
				if ( (array)$data == $data ) {
					$output[ $key ] = sanitize_array( $data, $method ); // recurse
				} elseif ( (string)$data == $data ) {
					$output[ $key ] = $func( $data );
				} else {
					$output[ $key ] = $data;
				}
			}
		} else {
			$output = $array;
		}
		return $output;
	}
}


/*  Non-WordPress specific */

/**
 * insert a key/value pair into an array after a specific key
 *
 * @param array $array
 * @param string $key
 * @param string $new_key
 * @param mixed $new_value
 * @return array
 * @link http://eosrei.net/comment/287
 */
if ( ! function_exists( 'array_insert_after' ) ) {
	function array_insert_after( array $array, $key, $new_key, $new_value ) {
		if ( array_key_exists( $key, $array ) ) {
			$new = array();
			foreach ( $array as $k => $value ) {
				$new[ $k ] = $value;
				if ( $k === $key ) {
					$new[ $new_key ] = $new_value;
				}
			}
			return $new;
		}
		return $array;
	}
}

/**
 * decode an incoming json string
 *
 * @param string $incoming
 * @return mixed
 */
function js_decode( $incoming ) {
	$return = $incoming;
	if ( is_string( $return ) ) {
		$object = json_decode( $incoming );
		if ( json_last_error() === 0 ) {
			$return = $object;
		} else {
			$return = json_last_error_msg();
		}
	}
	return $return;
}

/**
 * return an error message describing the most recent json error
 *
 * @link http://php.net/manual/en/function.json-last-error-msg.php
 * @staticvar array $errors
 * @return string
 */
if ( ! function_exists( 'json_last_error_msg' ) ) {
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
		return array_key_exists( $error, $errors ) ? "JSON error: {$errors[$error]}" : "Unknown JSON error ({$error})";
	}
}

/**
 * return consistent settings for json encoding
 *
 * @return int
 */
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

if ( ! function_exists( 'is_a_debugger' ) ) {
	function is_a_debugger() {
		$user = wp_get_current_user();
		$list = apply_filters( 'fluid_debugger_list', [ 1 ] );
		if ( $list && in_array( $user->ID, (array) $list ) ) {
			return true;
		} else if ( in_array( "administrator", $user->roles ) ) {
			return true;
		}
		return false;
	}
}

#  http://stackoverflow.com/questions/5224209/wordpress-how-do-i-get-all-the-registered-functions-for-the-content-filter
if ( ! function_exists( 'list_filter_hooks' ) ) {
	function list_filter_hooks( $hook = '' ) {
		$hooks = array();
		if ( WP_DEBUG ) {
			global $wp_filter;
			if ( isset( $wp_filter[$hook]->callbacks ) ) {
				array_walk( $wp_filter[$hook]->callbacks, function( $callbacks, $priority ) use ( &$hooks ) {
					foreach ( $callbacks as $id => $callback )
						$hooks[] = array_merge( [ 'id' => $id, 'priority' => $priority ], $callback );
				});
			} else {
				return [];
			}
			foreach( $hooks as &$item ) {
				// skip if callback does not exist
				if ( !is_callable( $item['function'] ) ) continue;
				// function name as string or static class method eg. 'Foo::Bar'
				if ( is_string( $item['function'] ) ) {
					$ref = strpos( $item['function'], '::' )
						? new ReflectionClass( strstr( $item['function'], '::', true ) )
						: new ReflectionFunction( $item['function'] );
					$item['file'] = $ref->getFileName();
					$item['line'] = get_class( $ref ) == 'ReflectionFunction'
						? $ref->getStartLine()
						: $ref->getMethod( substr( $item['function'], strpos( $item['function'], '::' ) + 2 ) )->getStartLine();
				// array( object, method ), array( string object, method ), array( string object, string 'parent::method' )
				} elseif ( is_array( $item['function'] ) ) {
					$ref = new ReflectionClass( $item['function'][0] );
					// $item['function'][0] is a reference to existing object
					$item['function'] = array(
						is_object( $item['function'][0] )
							? get_class( $item['function'][0] )
							: $item['function'][0],
						$item['function'][1]
					);
					$item['file'] = $ref->getFileName();
					$item['line'] = strpos( $item['function'][1], '::' )
						? $ref->getParentClass()->getMethod( substr( $item['function'][1], strpos( $item['function'][1], '::' ) + 2 ) )->getStartLine()
						: $ref->getMethod( $item['function'][1] )->getStartLine();
				// closures
				} elseif ( is_callable( $item['function'] ) ) {
					$ref = new ReflectionFunction( $item['function'] );
					$item['function'] = get_class( $item['function'] );
					$item['file'] = $ref->getFileName();
					$item['line'] = $ref->getStartLine();
				}
			}
		}
		return $hooks;
	}
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
		if (WP_DEBUG && is_a_debugger() ) {
			if ( empty( $data ) && is_array( $title ) ) {
				$data  = $title;
				$title = 'Show Me Data';
			} ?>
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
    if (WP_DEBUG && is_a_debugger()) {
      echo "<p>$string</p>";
    }
  }
}

if ( ! function_exists( 'who_am_i' ) ) {
	//  This function is for debugging purposes only
	function who_am_i( $pos=0 ) {
		static $status;
		if ( empty( $status ) ) {
			$status = ( file_exists( WP_CONTENT_DIR . '/who_am_i.flg' ) ) ? 'on' : tcc_settings( 'where', 'off' );
		}
		if ( ( $status === 'on' ) && WP_DEBUG && current_user_can( 'edit_theme_options' ) ) {
			$trace = debug_backtrace();
			$show  = $trace[ $pos ]['file'];
			if ( $pos = strpos( $show, 'wp-content' ) ) {
				$show = substr( $show, $pos + 10 );
			}
			$slug = get_page_slug();
			echo "<p>$slug : $show</p>";
		} else {
			$static = 'off';
		}
	}
}


#$perm_struct = get_option('permalink_structure');
#fluid()->log("permalink structure: $perm_struct");
