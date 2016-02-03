<?php

abstract class TCC_Custom_Post_Type {

  protected $type     = ''; // 'custom_post_type_name'
  protected $label    = ''; // _x('Custom Post Type','singular form','textdomain')
  protected $plural   = ''; // _x('Custom Post Types','plural form','textdomain')
  protected $descrip  = ''; // __('Custom Post Type Title','textdomain')

  protected $columns    = null; // array('remove'=>array()','add'=>array())
#  protected $comments   = null; // or true
  protected $edit_other = 'edit_others_posts';
  protected $extra      = array();
  protected $icon       = '';
  protected $nodelete   = array();
  protected $position   = 6;
  protected $rewrite    = array(); // array('slug'=>$this->type));
  protected $sidebars   = array();
  protected $supports   = array('title','editor','author','thumbnail','revisions','comments');
  protected $tax_list   = array();
  protected $taxonomies = array('post_tag','category');
  protected $tax_keep   = array();
  protected $template   = false; // array('single'=>WP_PLUGIN_DIR.'/plugin_dir/templates/single-<custom_post_type>.php')
  private static $types = array('posts');

  private static function translated_text() {
    return array('add'    => _x('Add New %s', 'placeheader string will be singular form','tcc-fluid'),
                 'all'    => _x('All %s',     'placeholder string will be plural form',  'tcc-fluid'),
                 'edit'   => _x('Edit %s',    'placeholder string will be singular form','tcc-fluid'),
                 'new'    => _x('New %s',     'placeholder string will be singular form','tcc-fluid'),
                 'search' => _x('Search %s',  'placeholder string will be plural form',  'tcc-fluid'));
  }

  public function __construct($data) {
    if (!post_type_exists($data['type'])) {
      foreach($data as $prop=>$value) {
        if (property_exists($this,$prop))
          $this->{$prop} = $value;
        else
          $this->extra[$prop] = $value;
      }
      add_action('init',         array($this,'create_post_type'));
      add_filter('pre_get_posts',array($this,'pre_get_posts'),5); // run before other filters
      if (isset($this->columns)) { $this->setup_columns(); }
/*
      if (isset($this->comments)) {
        add_filter('comments_open',array($this,'comments_limit'),10,2);
        add_filter('pings_open',   array($this,'comments_limit'),10,2);
      } //*/

      if ($this->sidebars) {
        add_filter('tcc_register_sidebars',array($this,'custom_post_sidebars')); }
      if ($this->template) {
        add_filter('template_include',array($this,'assign_template')); }
    }
  }

  // origin: http://php.net/manual/en/language.oop5.overloading.php#object.unset
  public static function __callStatic($name,$arguments) {
    if (($name=='get_tax_list') && (self::$instance)) {
      return self::$instance->get_tax_list(); }
    return null;
  }

  // origin: http://php.net/manual/en/language.oop5.overloading.php#object.unset
  public function __get($name) {
    if (property_exists($this,$name)) {
      return $this->$name; }
    $trace = debug_backtrace();
    log_entry('Error:  invalid property',$trace);
    return null;
  }

  // origin: http://php.net/manual/en/language.oop5.overloading.php#object.unset
  public function __isset($name) {
    return isset($this->$name);
  }


  /* Create Post Type functions */

  public function create_post_type() {
    if (empty($this->rewrite) || empty($this->rewrite['slug'])) { $this->rewrite['slug'] = $this->type; }
    $args = array (
        'label'             => $this->plural,
        'labels'            => $this->post_type_labels(),
        'description'       => $this->descrip,
        'public'            => true,
        'show_in_admin_bar' => false,
        'menu_position'     => $this->position,
        'menu_icon'         => $this->icon,
        'capability_type'   => 'post',
        'map_meta_cap'      => true,
        'hierarchical'      => false,
        'query_var'         => false,
        'supports'          => $this->supports,
        'taxonomies'        => $this->taxonomies,
        'has_archive'       => $this->type,
        'rewrite'           => $this->rewrite);
    register_post_type($this->type,$args);
    do_action('tcc_custom_post_'.$this->type);
  }

  private function post_type_labels() {
    $phrases = self::translated_text();
    $arr = array (
      'name'          => $this->plural,
      'singular_name' => $this->label,
      'add_new'       => sprintf($phrases['add'],   $this->label),
      'add_new_item'  => sprintf($phrases['add'],   $this->label),
      'edit'          => sprintf(_x('Edit %s',     'placeholder will be in plural form',  'tcc-fluid'),$this->plural),
      'edit_item'     => sprintf($phrases['edit'],  $this->label),
      'new_item'      => sprintf($phrases['new'],   $this->label),
      'all_items'     => sprintf($phrases['all'],   $this->plural),
      'view'          => sprintf(_x('View %s',     'placeholder will be in plural form',  'tcc-fluid'),$this->plural),
      'view_item'     => sprintf(_x('View %s',     'placeholder will be in singular form','tcc-fluid'),$this->label),
      'items_archive' => sprintf(_x('%s Archive',  'placeholder will be in singular form','tcc-fluid'),$this->label),
      'search_items'  => sprintf($phrases['search'],$this->plural),
      'not_found'     => sprintf(_x('No %s found', 'placeholder will be in plural form',  'tcc-fluid'),$this->plural),
      'not_found_in_trash' => sprintf(_x('No %s found in trash','placeholder will be in plural form','tcc-fluid'),$this->plural));
    return $arr;
  }


  /* Taxonomy functions */

  protected function taxonomy_labels($single,$plural) {
    $phrases = self::translated_text();
    return array('name'              => $plural,
                 'singular_name'     => $single,
                 'search_items'      => sprintf($phrases['search'],$plural),
                 'all_items'         => sprintf($phrases['all'],   $plural),
                 'edit_item'         => sprintf($phrases['edit'],  $single),
                 'update_item'       => sprintf(_x('Update %s','placeholder will be in singular form','tcc-fluid'),$single),
                 'add_new_item'      => sprintf($phrases['add'],   $single),
                 'new_item_name'     => sprintf($phrases['new'],   $single),
                 'menu_name'         => $plural);
  }

  protected function register_taxonomy($args) {
    $defs = array('tax'=>'fix-me','single'=>'One Fix Me','plural'=>'Many Fix Mes','admin'=>false,'submenu'=>false,'nodelete'=>false);
    $args = wp_parse_args($args,$defs);
    extract($args);
    if (!isset($rewrite)) { $rewrite = $tax; }
    $taxi = array('hierarchical' => false,
                  'labels'       => $this->taxonomy_labels($single,$plural),
                  'rewrite'      => array('slug'=>$rewrite),
                  'show_admin_column'=>$admin);
    register_taxonomy($tax,$this->type,$taxi);
    if (taxonomy_exists($tax)) {
      if (!in_array($tax,$this->tax_list)) { $this->tax_list[] = $tax; }
      register_taxonomy_for_object_type($tax,$this->type);
      $func = "default_$tax";
      if ($func) {
        $current = get_terms($tax,'hide_empty=0');
        if (empty($current)) {
          $defs = $this->$func();
          foreach($defs as $each) {
            wp_insert_term($each,$tax); }
        }
      }
      if (($submenu) && (method_exists($this,$submenu))) {
        add_filter('wp_get_nav_menu_items',array($this,$submenu)); }
      if ($nodelete) {
        $this->nodelete[] = $tax;
        add_action('admin_enqueue_scripts',array($this,'stop_term_deletion'));
      }
    }
  }

  public function stop_term_deletion() {
    $screen = get_current_screen();
    if (($screen->base=='edit-tags') && (in_array($screen->taxonomy,$this->nodelete))) {
      $keep_list = array();
      if (!empty($this->tax_keep[$screen->taxonomy])) {
        foreach($this->tax_keep[$screen->taxonomy] as $term) {
          $keep_list[] = get_term_by('name',$term,$screen->taxonomy)->term_id;
        }
      }
      $term_list = get_terms($screen->taxonomy,'hide_empty=1');
      if ($term_list) {
        foreach($term_list as $term) {
          $keep_list[] = $term->term_id; }
     }
     if ($keep_list) {
        wp_register_script('tax_nodelete',plugins_url('../js/tax_nodelete.js',__FILE__),array('jquery'),false,true);
        wp_localize_script('tax_nodelete','term_list',$keep_list);
        wp_enqueue_script('tax_nodelete');
      }
    }
  }

  public function taxonomy_menu_dropdown($taxonomy,$args='hide_empty=1') {
    $output = array();
    $taxon  = get_taxonomies("name=$taxonomy",'objects');
#    $terms  = get_terms($taxonomy,$args);
#    $site   = get_bloginfo('url');
#    foreach($terms as $term){
#      $tax    = $term->taxonomy; // FIXME: get taxonomy rewrite slug
#      $slug   = $term->slug;
#      $link   = "$site/$tax/$slug";
#      $output[$link] = $term->name;
#    }
    return $output;
  }

  public function get_tax_list() {
    return $this->tax_list;
  }


  /* Post Column functions */

  private function setup_columns() {
    if (isset($this->columns['remove'])) {
      add_filter("manage_edit-{$type}_columns",array($this,'remove_custom_post_columns')); }
    if (isset($data['columns']['add'])) {
      add_filter("manage_edit-{$type}_columns",array($this,'add_custom_post_columns'));
      add_filter("manage_edit-{$type}_sortable_columns",array($this,'add_custom_post_columns'));
      if (isset($data['columns']['content'])) {
        if (is_callable(array($this,$this->columns['content']))) {
          add_action('manage_posts_custom_column',array($this,$this->columns['content']),10,2);
        } else { log_entry('columns[content] not callable',$this); }
      }
    }
  }

  public function add_custom_post_columns($columns) {
    foreach($this->columns['add'] as $key=>$col) {
      if (!isset($columns[$key])) $columns[$key] = $col; }
    return $columns;
  } //*/

  // http://codex.wordpress.org/Function_Reference/locate_template
  // https://wordpress.org/support/topic/stylesheetpath-in-plugin
  public function assign_template($template) {
    $post_id = get_the_ID();
    if ($post_id) {
      $mytype = get_post_type($post_id);
      if ($mytype && ($this->type==$mytype)) {
        if ((is_single()) && (isset($this->template['single']))) {
          $name  = basename($this->template['single']);
          $maybe = locate_template(array($name));
          $template = ($maybe) ? $maybe : $this->template['single'];
        }
      }
    }
    do_action('tcc_assign_template_'.$this->type);
    return $template;
  } //*/

/*
  public function comments_limit($open,$post_id) {
    $mytype = get_post_type($post_id);
    if ($this->type==$mytype) {
      if (is_singular($mytype)) {
        if ((isset($this->comments)) && ($this->comments)) {
          if (is_bool($this->comments)) {
            $open = $this->comments;
          } else {
#            $postime = get_the_time('U', $post_id);
             log_entry('WARNING: Numeric values for custom_post_type->comments is not yet supported.');
          }
        }
      }
    }
    return $open;
  } //*/

  public function custom_post_sidebars($sidebars) {
    $defaults = array('before_widget' => '<div class="panel panel-primary">', // bootstrap css classes
                      'before_title'  => '<div class="panel-heading"><h3 class="panel-title">',
                      'after_title'   => '</h3></div><div class="panel-body">',
                      'after_widget'  => '</div></div>');
    foreach($this->sidebars as $sidebar) {
      if (empty($sidebar['id']) || empty($sidebar['name'])) continue;
      $add_sidebar = array_merge($defaults,$sidebar);
      $sidebars[]  = $add_sidebar;
    }
    return $sidebars;
  } //*/


  // https://wordpress.org/support/topic/custom-post-type-posts-not-displayed
  public function pre_get_posts($query) {
    if (!is_admin()) {
      if ($query->is_main_query()) {
        if ((!$query->is_page()) || (is_feed())) {
          $check = $query->get('post_type');
          if (empty($check)) {
            $query->set('post_type',array('post',$this->type));
          } else if (!((array)$check==$check)) {
            if ($check!==$this->type) $query->set('post_type',array($check,$this->type));
          } else if (!in_array($this->type,$check)) {
            $check[] = $this->type;
            $query->set('post_type',$check);
          }
        }
      }
    }
    return $query;
  }

  public function remove_custom_post_columns($columns) {
    foreach($this->columns['remove'] as $no_col) {
      if (isset($columns[$no_col])) { unset($columns[$no_col]); } }
    return $columns;
  } //*/

  protected function remove_meta_boxes() {
    if (!current_user_can($this->edit_other)) { 
      remove_meta_box('authordiv',$this->type,'normal');
    }
  }

}

?>
