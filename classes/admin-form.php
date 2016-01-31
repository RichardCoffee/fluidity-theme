<?php

/*
 *  classes/admin-form.php
 *
 *  copyright 2014-2015, The Creative Collective, the-creative-collective.com
 */

abstract class Basic_Admin_Form {

  protected $current   = '';
  protected $defaults  =  array();
  protected $err_func  = 'log_entry';
  protected $form      =  array();
  protected $form_opts =  array();
  protected $form_text =  array();
  protected $options;
  protected $prefix    = 'options_prefix_';
  protected $register;
  protected $render;
  protected $slug      = 'default_page_slug';
  protected $tab;
  protected $type      = 'single'; // or 'tabbed', 'multi' pending
  protected $validate;

  abstract protected function form_layout($option);
  public function description() { return ''; }

  protected function __construct() {
    $this->screen_type();
    add_action('admin_init',array($this,'load_form_page'));
  }

  public function load_form_page() {
    global $plugin_page;
    $this->form_text = $this->form_text();
    if (($plugin_page==$this->slug) || (($refer=wp_get_referer()) && (strpos($refer,$this->slug)))) {
      $this->form = $this->form_layout();
      $this->determine_option();
      $this->get_defaults();
      $this->get_form_options();
      $func = $this->register;
      $this->$func();
      add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));
#log_entry($this);
    }
  }

  public function enqueue_scripts() {
    wp_register_style('basic-form.css', get_stylesheet_directory_uri()."/css/basic-form.css", false);
    wp_register_script('basic-form.js', get_stylesheet_directory_uri()."/js/basic-form.js", array('jquery','wp-color-picker'), false, true);
    wp_enqueue_media();
    wp_enqueue_style('basic-form.css');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('basic-form.js');
  }

  /**  Form text functions  **/

  private function form_text() {
    $text = array('error'  => array('render'    => _x('ERROR: Unable to locate function %s','string - a function name','basic-form')),
                                    'subscript' => _x('ERROR: Not able to locate form data subscript:  %s','string - an array subscript','basic-form'),
                  'submit' => array('save'      => __('Save Changes','basic-form'),
                                    'object'    => __('Form','basic-form'),
                                    'reset'     => _x('Reset %s','placeholder is a noun, may be plural','basic-form'),
                                    'subject'   => __('Form','basic-form'),
                                    'restore'   => _x('Default %s options restored.','placeholder is a noun, probably singular','basic-form')),
                  'media'  => array('title'     => __('Assign/Upload Image','basic-form'),
                                    'button'    => __('Assign Image','basic-form'),
                                    'delete'    => __('Unassign Image','basic-form')));
    return apply_filters($this->slug.'_form_text',$text,$text);
  }


  /**  Register Screen functions **/

  private function screen_type() {
    $this->register = "register_{$this->type}_form";
    $this->render   = "render_{$this->type}_form";
    $this->options  = "render_{$this->type}_options";
    $this->validate = "validate_{$this->type}_form";
  }

  public function register_single_form() {
    register_setting($this->current,$this->current,array($this,$this->validate));
    foreach($this->form as $key=>$group) {
      if (is_string($group)) continue; // skip string variables
      $title = (isset($group['title']))    ? $group['title'] : '';
      $desc  = (isset($group['describe'])) ? array($this,$group['describe']) : 'description';
      add_settings_section($key,$title,array($this,$desc),$this->slug);
      foreach($group['layout'] as $item=>$data) {
        $this->register_field($this->slug,$key,$item,$data);
      }
    }
  }

  public function register_tabbed_form() {
#log_entry(debug_backtrace());
    $validater = (isset($this->form['validate'])) ? $this->form['validate'] : $this->validate;
    foreach($this->form as $key=>$section) {
      if (!((array)$section===$section)) continue; // skip string variables
      $title    = (isset($section['title']))    ? $section['title']    : '';
      $validate = (isset($section['validate'])) ? $section['validate'] : $validater;
      $describe = (isset($section['describe'])) ? $section['describe'] : 'description';
      $current  = (isset($this->form[$key]['option'])) ? $this->form[$key]['option'] : $this->prefix.$key;
      register_setting($current,$current,array($this,$validate));
#log_entry("register    group: $current");
#log_entry("register   option: $current");
#log_entry("register callback: $validate");
      add_settings_section($current,$title,array($this,$describe),$this->slug);
#log_entry("section       id: $current");
#log_entry("section    title: $title");
#log_entry("section callback: $describe");
#log_entry("section     page: {$this->slug}");
      foreach($section['layout'] as $item=>$data) {
        $this->register_field($current,$key,$item,$data);
      }
global $new_whitelist_options, $wp_settings_sections;
log_entry($new_whitelist_options);
log_entry($wp_settings_sections);
    } //*/
  }

  public function register_multi_form() {   }

  private function register_field($current,$key,$item,$data) {
#log_entry("key: $key  item: $item");
    if (is_string($data))        return; // skip string variables
    if (!isset($data['render'])) continue;
    if ($data['render']=='skip') continue;
    $itemID = "{$key}_$item";
    if ($data['render']=='array') {
      $count = max(count($data['default']),count($this->form_opts[$key][$item]));
      for ($i=0;$i<$count;$i++) {
        $itemID = "{$key}_{$item}_$i";
        $label  = "<label for='$itemID'>{$data['label']} ".($i+1)."</label>";
        $args   = array('itemID'=>$itemID,'key'=>$key,'item'=>$item,'num'=>$i);
#        if ($i+1==$count) { $args['add'] = true; }
        add_settings_field($itemID,$label,array($this,$this->options),$this->slug,$current,$args);
      }
    } else {
      $label = $this->field_label($data,$itemID);
      $args  = array('itemID'=>$itemID,'key'=>$key,'item'=>$item);
      add_settings_field($itemID,$label,array($this,$this->options),$this->slug,$current,$args);
#log_entry("field       ID: $itemID");
#log_entry("field    title: $label");
#log_entry("field callback: {$this->options}");
#log_entry("field     page: {$this->slug}");
#log_entry("field  section: $current");
    }
  }

  private function field_label($data,$ID) {
    if ($data['render']=='display') {
      $return = $data['label'];
    } else if ($data['render']=='title') {
      $return = "<span class='form-title'>{$data['label']}</span>";
    } else {
      $return = "<label for='$ID'>{$data['label']}</label>";
    }
    return $return;
  }


  /**  Data functions  **/

  private function determine_option() {
    if ($this->type=='single') {
      $this->current = $this->slug;
    } else if ($this->type=='tabbed') {
      $tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'about';
      if (isset($this->form[$tab]['option'])) { 
        $this->current = $this->form[$tab]['option'];
      } else {
        $this->current = $this->prefix.$tab ;
      }
      $this->tab = $tab;
    }
  }

  protected function get_defaults($option='about') {
    if (empty($this->form)) { $this->form = $this->form_layout(); }
    if ($this->type=='single') {
      foreach($this->form as $key=>$group) {
        if (is_string($group)) continue;
        foreach($group['layout'] as $ID=>$item) {
          if (empty($item['default'])) continue;
          $this->defaults[$key][$ID] = $item['default'];
        }
      }
    } else { // tabbed
      if (isset($this->form[$option])) {
        foreach($this->form[$option]['layout'] as $key=>$item) {
          if (empty($item['default'])) continue;
          $this->defaults[$key] = $item['default'];
        }
      } else {
        if (!empty($this->err_func)) {
          $func   = $this->err_func;
          $string = _x('ERROR: Not able to locate form data subscript:  %s','string - an array subscript','basic-form');
          $func(sprintf($string,$option));
        }
      }
    }
  } //*/

  private function get_form_options() {
    $this->form_opts = get_option($this->current);
    if (empty($this->form_opts)) {
      $this->form_opts = $this->defaults;
      add_option($this->current,$this->form_opts);
    }
    $this->form_opts = array_replace_recursive($this->defaults,$this->form_opts);
  }


  /**  Render Screen functions  **/

  public function render_single_form() { ?>
    <div class="wrap"><?php
      if (isset($this->form['title'])) { echo "<h1>{$this->form['title']}</h1>"; }
      settings_errors(); ?>
      <form method="post" action="options.php"><?php
        do_action("form_pre_display");
        do_action("form_pre_display_".$this->current);
        settings_fields($this->current);
        do_settings_sections($this->current);
        do_action("form_post_display_".$this->current);
        do_action("form_post_display");
        $this->submit_buttons(); ?>
      </form>
    </div><?php //*/

}

  public function render_tabbed_form() {
    $active_page = sanitize_key($_GET['page']); ?>
    <div class="wrap">
      <div id="icon-themes" class="icon32"></div>
      <h1 class='centered'><?php echo $this->form['title']; ?></h1><?php
      settings_errors(); ?>
      <h2 class="nav-tab-wrapper"><?php
        $refer = "admin.php?page=$active_page";
        foreach($this->form as $key=>$menu_item) {
          if (is_string($menu_item)) continue;
          $tab_css  = 'nav-tab';
          $tab_css .= ($this->tab==$key) ? ' nav-tab-active' : '';
          $tab_ref  = "$refer&tab=$key";
          echo "<a href='$tab_ref' class='$tab_css'>{$menu_item['title']}</a>";
        } ?>
      </h2>
      <form method="post" action="options.php"><?php
        do_action("basic_form_pre_display_".$this->tab);
        settings_fields($this->current);
        do_settings_sections($this->current);
        do_action("basic_form_post_display_".$this->tab);
        $this->submit_buttons($this->form[$this->tab]['title']); ?>
      </form>
    </div><?php //*/
  }

  private function render_multi_form() {
  }

  private function submit_buttons($title='') {
    $buttons = $this->form_text['submit']; ?>
    <p><?php
      submit_button($buttons['save'],'primary','submit',false); ?>
      <span style='float:right;'><?php
        $object = (empty($title)) ? $buttons['object'] : $title;
        $reset  = sprintf($buttons['reset'],$object);
        submit_button($reset,'secondary','reset',false); ?>
      </span>
    </p><?php
  }

  // $args = array('itemID'=>$itemID,'key'=>$key,'item'=>$item); // ,'num'=>$i);
  public function render_single_options($args) {
    extract($args);
    $data   = $this->form_opts;
    $layout = $this->form[$key]['layout'];
    $class  = (!empty($layout[$item]['class'])) ? "class='{$layout[$item]['class']}'" : '';
    echo "<div $class>";
    if (empty($layout[$item]['render'])) {
      echo $data[$key][$item];
    } else {
      $func  = "render_{$layout[$item]['render']}";
      $name  = $this->slug."[$key][$item]";
      $value = (isset($data[$key][$item])) ? $data[$key][$item] : '';
      if ($layout[$item]['render']=='array') {
        $name.= "[$num]";
#        if ((isset($add)) && ($add)) { $layout[$item]['add'] = true; }
        $value = (isset($data[$key][$item][$num])) ? $data[$key][$item][$num] : '';
      }
      $field = str_replace(array('[',']'),array('_',''),$name);
      $fargs = array('ID'=>$field, 'value'=>$value, 'layout'=>$layout[$item], 'name'=>$name);
      if (method_exists($this,$func)) {
        $this->$func($fargs);
      } else if (function_exists($func)) {
        $func($fargs);
      } else {
        if (!empty($this->err_func)) {
          $func = $this->err_func;
          $func(sprintf($this->form_text['error']['render'],$func)); }
      }
    }
    echo "</div>";
  }

  public function render_tabbed_options($args) {
    extract($args);
    $data   = $this->form_opts;
    $layout = $this->form[$key]['layout'];
    $class  = (!empty($layout[$itemID]['class'])) ? "class='{$layout[$itemID]['class']}'" : '';
    echo "<div $class>";
    if (empty($layout[$itemID]['render'])) {
      echo $data[$itemID];
    } else {
      $func = "render_{$layout[$itemID]['render']}";
      $name = $this->current."[$key][$item]";
      if (!isset($data[$itemID])) $data[$itemID] = '';
      $fargs = array('ID'=>$itemID, 'value'=>$data[$itemID], 'layout'=>$layout[$itemID], 'name'=>$name);
      if (method_exists($this,$func)) {
        $this->$func($fargs);
      } else if (function_exists($func)) {
        $func($fargs);
      } else {
        if (!empty($this->err_func))
          $func = $this->err_func;
          $func(sprintf($this->form_text['error']['render'],$func));
      }
    }
    echo "</div>"; //*/
  }

  public function render_multi_options($args) {
  }


  /**  Render Items functions
    *
    *
    *  $data = array('ID'=>$field, 'value'=>$value, 'layout'=>$layout[$item], 'name'=>$name);
    *
    **/

  // FIXME:  needs add/delete/sort
  private function render_array($data) {
    extract($data);
    if (!(isset($layout['type']))) { $layout['type'] = 'text'; }
    if ($layout['type']==='image') {
      $this->render_image($data);
    } else {
      $this->render_text($data);
    }
  }

  private function render_colorpicker($data) {
    extract($data);
    echo "<input type='text' value='$value' class='form-colorpicker' data-default-color='{$layout['default']}' name='$name'/>";
    if (!empty($layout['text'])) echo " <span style='vertical-align: top;'>{$layout['text']}</span>";
  }

  private function render_checkbox($data) {
    extract($data);
    $state  = checked('yes',$value,false);
    $change = (isset($layout['change'])) ? "onchange='{$layout['change']}'" : '';
    $html   = "<label><input type='checkbox' id='$ID' name='$name' value='yes' $state $change/>";
    $html  .= " <span>{$layout['text']}</span></label>";
    echo $html;
  }

  private function render_image($data) {
    extract($data);
    $media = $this->form_text['media'];
    if (isset($layout['media'])) $media = array_merge($media,$layout['media']);
    $html = "<div data-title='{$media['title']}' data-button='{$media['button']}' data-field='$ID'>";
    $html.= "  <button type='button' class='form-image'>{$media['button']}</button>";
    $html.= "  <input id='{$ID}_input' type='text' class='hidden' name='$name' value='$value' />";
    $html.= "  <div class='form-image-container".((empty($value)) ? " hidden'" : "'")."><img id='{$ID}_img' src='$value'></div>";
    $html.= "  <button type='button' class='form-image-delete".((empty($value)) ? " hidden'" : "'").">{$media['delete']}</button>";
    $html.= "</div>";
    echo $html;
  }

  private function render_select($data) {
    extract($data);
    if (empty($layout['source'])) return;
    $source_func = $layout['source'];
    echo "<select id='$ID' name='$name'>";
    if (is_array($source_func)) {
      foreach($source_func as $key=>$text) {
        $select = ($key==$value) ? "selected='selected'" : '';
        echo "<option value='$key' $select> $text </option>";
      }
    } else if (method_exists($this,$source_func)) {
      $this->$source_func($value);
    } else if (function_exists($source_func)) {
      $source_func($value);
    }
    echo '</select>';
    if (!empty($layout['text'])) echo "<span class=''> ".esc_attr($layout['text'])."</span>";
  }

  private function render_text($data) {
    extract($data);
    $value = sanitize_text_field($value);
    $class = (isset($layout['large'])) ? 'large-text' : 'regular-text';
    $quote = (strpos($value,"'")===false) ? "'" : '"'; // needed in case the string contains a single quote - FIXME
    echo "<input type='text' id='$ID' class='$class' name='$name' value=$quote{$value}$quote />";
    if (!empty($layout['text'])) echo esc_attr($layout['text']);
  }


  /**  Validate functions  **/

  public function validate_single_form($input) {
    $form   = $this->form;
    $output = $this->defaults;
    if (isset($_POST['reset'])) {
      $object = (isset($this->form['title'])) ? $this->form['title'] : $this->form_test['submit']['object'];
      $string = sprintf($this->form_text['submit']['restore'],$object);
      add_settings_error($this->slug,'restore_defaults',$string,'updated fade');
      return $output;
    }
    foreach($input as $key=>$data) {
      if (!((array)$data==$data)) continue;
      foreach($data as $ID=>$subdata) {
        $item = $form[$key]['layout'][$ID];
        if ($item['render']==='array') {
          $item['render'] = (isset($item['type'])) ? $item['type'] : 'text';
          $vals = array();
          foreach($subdata as $indiv) {
            $valid_func = $this->determine_validate($item);
            $vals[] = $this->do_validate_function($indiv,$valid_func);
          }
          $output[$key][$ID] = $vals;
        } else {
          $valid_func = $this->determine_validate($item);
          $output[$key][$ID] = $this->do_validate_function($subdata,$valid_func);
        }
      }
    }
    // check for required fields
    foreach($this->defaults as $key=>$data) {
      if (!((array)$data==$data)) continue;
      foreach($data as $ID=>$subdata) {
        if (!isset($form[$key]['layout'][$ID]['require'])) { continue; }
        if (empty($output[$key][$ID])) {
          $output[$key][$ID] = $subdata;
        }
      }
    }
    return apply_filters($this->slug.'_validate_settings',$output,$input);
  }

  public function validate_tabbed_form($input) {
    $option = sanitize_key($_POST['tab']);
    $output = $this->defaults;
    if (isset($_POST['reset'])) {
      $object = (isset($this->form[$option]['title'])) ? $this->form[$option]['title'] : $this->form_test['submit']['object'];
      $string = sprintf($this->form_text['submit']['restore'],$object);
      add_settings_error('creatom','restore_defaults',$string,'updated fade');
      return $output;
    }
    foreach($input as $key=>$data) {
      if ((array)$data==$data) {
        foreach($data as $ID=>$subdata) {
          $func = $this->determine_validate($this->form[$key]['layout'][$ID]);
          $output[$key][$ID] = $this->do_validate_function($subdata,$func);
        }
      } else {
#        $valid_func = $this->determine_validate($form[$key]);
#        $output[$key] = $this->do_validate_function($data,$valid_func);
      }
    }
    return apply_filters($this->current.'_validate_settings',$output,$input);
  }

  private function determine_validate($item=array()) { // FIXME: re: sanitize_callback()
    $defs = array('render'=>'non_existing_render_type');
    $item = array_merge($defs,$item);
    $func = 'validate_';
    $func.= (isset($item['validate'])) ? $item['validate'] : $item['render'];
    return $func;
  }

  private function do_validate_function($input,$func) {
    if (method_exists($this,$func)) {
      $output = $this->$func($input);
    } else if (function_exists($func)) {
      $output = $func($input);
    } else { // FIXME:  test for data type
      $output = $this->validate_text($input);
    }
    return $output;
  }

  private function validate_colorpicker($input) {
    return (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|',$input)) ? $input : '';
  }

  private function validate_image($input) {
    return esc_url_raw(strip_tags(stripslashes($input)));
  }

  private function validate_post_content($input) {
    return wp_kses_post($input);
  }

  private function validate_radio($input) {
    return sanitize_key($input);
  }

  private function validate_select($input) {
    return sanitize_file_name($input);
  }

  protected function validate_text($input) {
    return strip_tags(stripslashes($input));
  }

  private function validate_url($input) {
    return esc_url_raw(strip_tags(stripslashes($input)));
  }

}

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
