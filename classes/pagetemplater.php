<?php
/*
Plugin Name: Page Template Plugin : 'Good To Be Bad'
Plugin URI: http://hbt.io/
Version: 1.0.1
Author: Harri Bell-Thomas
Author URI: http://hbt.io/
Notes: 02/06/15 - Added add_project_template() function to allow templates to be added dynamically
                  Also added paths property to keep track of each template location
       02/07/15 - Incorporated changes proposed by wpscholar on github
*/

class PageTemplater {

  private static $instance;
  protected $paths;
  protected $templates;

  public static function get_instance() {
    if (self::$instance==null) {
      self::$instance = new PageTemplater();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->templates = array();
    $this->paths     = array();
    add_filter('page_attributes_dropdown_pages_args',array($this,'register_project_templates'));
    add_filter('wp_insert_post_data',array($this,'register_project_templates'));
    add_filter('template_include',array($this,'view_project_template'));
#    $this->templates = array('templates/search=form.php'=>'Search Form (plugin)');
  }

  public function add_project_template($slug,$desc,$path) {
    if (empty($this->templates[$slug])) {
      $this->templates[$slug] = $desc;
      $this->paths[$slug]     = $path;
    }
  }

  public function register_project_templates($atts) {
#    $cache_key = 'page_templates-'.md5(get_theme_root().'/'.get_stylesheet());
#    $templates = wp_get_theme()->get_page_templates();
#    if (empty($templates)) $templates = array();
#    wp_cache_delete($cache_key,'themes');
    $theme = wp_get_theme();
    $cache_key = 'page_templates-'.md5($theme->get_theme_root().'/'.$theme->get_stylesheet());
    $templates = $theme->get_page_templates();
    $templates = array_merge($templates,$this->templates);
#    wp_cache_add($cache_key,$templates,'themes',1800);
    wp_cache_set( $cache_key, $templates, 'themes', 1800 );
    return $atts;
  }

  public function view_project_template($template) {
    global $post;
    if (empty($post)) return $template;
    $current = get_post_meta($post->ID,'_wp_page_template',true);
    if (!isset($this->templates[$current])) return $template;
    $file = $this->paths[$current].'/'.$current;
    if (file_exists($file)) {
      return $file;
    } else {
      echo $file;
    }
    return $template;
  }

}

?>
