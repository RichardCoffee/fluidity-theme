<?php

abstract class Basic_Admin_Form {

  protected $current   = '';
  protected $defaults  =  array();
  protected $err_func  =  null;
  protected $form      =  array();
  protected $form_opts =  array();
  protected $form_text =  array();
  protected $options   = 'render_single_options';
  protected $prefix    = 'options_prefix_';
  protected $register  = 'register_single_form';
  protected $render    = 'render_single_form';
  protected $slug      = 'default_page_slug';
  protected $type      = 'single'; // or 'tabbed', 'multi' pending
  protected $validate  = 'validate_single_form';

  abstract protected function form_layout($option);
  public function description() { return ''; }

  protected function __construct() {
    add_action('admin_init',array($this,'load_form_page'));
  }

  public function load_form_page() {
    global $plugin_page;
    if (($plugin_page==$this->slug) || (($refer=wp_get_referer()) && (strpos($refer,$this->slug)))) {
      $this->form_text = $this->form_text();
      $this->form      = $this->form_layout();
      $this->current   = $this->determine_option();
      $this->get_defaults();
      $this->get_form_options();
      $this->screen_type();
      $this->register();
      add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));
    }
  }

  public function enqueue_scripts() {
    $base_url = plugin_dir_url(__FILE__).'..';
    wp_register_style('basic-form.css', "$base_url/css/basic-form.css", false);
    wp_register_script('basic-form.js', "$base_url/js/basic-form.js", array('jquery','wp-color-picker'), false, true);
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
      foreach($group['layout'] as $itemkey=>$item) {
        if (is_string($item)) continue; // skip string variables
        if (!isset($item['render'])) continue;
        $name = "{$key}_$itemkey";
        $args = array('key'=>$key,'item'=>$itemkey);
        $this->register_field($item,$name,$key,$args);
      }
    }
  }

  public function register_tabbed_form() {
    $validater = (isset($this->form['validate'])) ? $this->form['validate'] : $this->validate;
    foreach($this->form as $key=>$section) {
      if (!((array)$section===$section)) continue; // skip string variables
      $option   = $this->determine_option($key);
      $title    = (isset($section['title']))    ? $section['title']    : '';
      $validate = (isset($section['validate'])) ? $section['validate'] : $validater;
      $describe = (isset($section['describe'])) ? $section['describe'] : 'description';
      $slug     = (isset($section['slug']))     ? $section['slug']     : $this->slug;
      register_setting($option,$option,array($this,$validate));
      add_settings_section($option,$title,array($this,$describe),$slug);
      foreach($section['layout'] as $itemID=>$item) {
        $args = array('key'=>$key,'item'=>$itemID);
        $this->register_field($item,$itemID,$option,$args);
      }
    } //*/
  }

  public function register_multi_form() {   }

  private function register_field($item,$itemID,$key,$args) {
    static $larr = array('display','skip');
    if (!((array)$item===$item)) return; // skip string variables
    if (!isset($item['render'])) return;
    if ($item['render']=='skip') return;
    $label = (in_array($item['render'],$larr)) ? $item['label'] : "<label for='$itemID'>{$item['label']}</label>";
    $label = ($item['render']=='title') ? "<span class='form-title'>{$item['label']}</span>" : $label;
    add_settings_field($itemID,$label,array($this,$this->options),$this->slug,$key,$args);
  }


  /**  Data functions  **/

  private function determine_option($current='') {
    $option = (empty($current)) ? $this->slug : $this->prefix.$option ;
    if (isset($this->form[$option]['option'])) { $option = $this->form[$option]['option'];
    return $option;
  }

  protected function get_defaults() {
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
        if (!empty($this->err_func))
          $this->err_func(sprintf($this->form_text['error']['subscript'],$option));
      }
    }
  }

  private function get_form_options($option) {
    $this->form_opts = get_option($this->current);
    if (empty($this->form_opts)) {
      $this->form_opts = $this->defaults;
      add_option($this->current,$this->form_opts);
    }
    $this->form_opts = array_replace_recursive($this->defaults,$this->form_opts);
  }


  /**  Render Screen functions  **/

  public function render_single_form() {
    $form = $this->form_layout(); ?>
    <div class="wrap"><?php
      if (isset($form['title'])) {
        echo "<h2>{$form['title']}</h2>";
      }
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
    $active_page = sanitize_key($_GET['page']);
    $active_tab  = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'design';
    if (!isset($this->form[$active_tab])) { $active_tab = 'about'; } ?>
    <div class="wrap">
      <div id="icon-themes" class="icon32"></div>
      <h2><?php echo $this->form['title']; ?></h2><?php
      settings_errors(); ?>
      <h2 class="nav-tab-wrapper"><?php
        $refer = "admin.php?page=$active_page";
        foreach($this->form as $key=>$menu_item) {
          if (is_string($menu_item)) continue;
          $tab_css  = 'nav-tab';
          $tab_css .= ($active_tab==$key) ? ' nav-tab-active' : '';
          $tab_ref  = "$refer&tab=$key";
          echo "<a href='$tab_ref' class='$tab_css'>{$menu_item['title']}</a>";
        } ?>
      </h2>
      <form method="post" action="options.php"><?php
        $section = $this->determine_option($active_tab);
        do_action("basic_form_pre_display_$active_tab");
        settings_fields($section);
        do_settings_sections($section);
        do_action("basic_form_post_display_$active_tab");
        $this->submit_buttons($this->form[$active_tab]['title']); ?>
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

  public function render_single_options($args) {
    static $data;
    $option = $this->determine_option();
    if (empty($data)) $data = $this->get_form_options($option);
    extract($args);
    $layout = $this->form[$key]['layout'];
    $class  = (!empty($layout[$item]['class'])) ? "class='{$layout[$item]['class']}'" : '';
    echo "<div $class>";
    if (empty($layout[$item]['render'])) {
      echo $data[$key][$item];
    } else {
      $render_func = "render_{$layout[$item]['render']}";
      $itemID      = "{$key}_$item";
      $item_name   = $option."[$key][$item]";
      $value       = (isset($data[$key][$item])) ? $data[$key][$item] : '';
      if ($layout[$item]['render']=='array') {
        $itemID    .= "_$num";
        $item_name .= "[$num]";
        $value      = (isset($data[$key][$item][$num])) ? $data[$key][$item][$num] : '';
      }
      $render_arr  = array('ID'=>$itemID, 'value'=>$value, 'layout'=>$layout[$item], 'name'=>$item_name);
      if (method_exists($this,$render_func)) {
        $this->$render_func($render_arr);
      } else if (function_exists($render_func)) {
        $render_func($render_arr);
      } else {
        if (!empty($this->err_func))
          $this->err_func(sprintf($this->form_text['error']['render'],$render_func));
      }
    }
    echo "</div>";
  }

  public function render_tabbed_options($args) {
    extract($args);
    $option = $this->determine_option($key);
    $data   = get_form_options($key);
    $layout = $this->form[$key];
    $class  = (!empty($layout[$itemID]['class'])) ? "class='{$layout[$itemID]['class']}'" : '';
    echo "<div $class>";
    if (empty($layout[$itemID]['render'])) {
      echo $data[$itemID];
    } else {
      $render_func = "render_{$layout[$itemID]['render']}";
      if (!isset($data[$itemID])) $data[$itemID] = '';
      $render_arr  = array('ID'=>$itemID, 'value'=>$data[$itemID], 'layout'=>$layout[$itemID], 'name'=>"$option[$itemID]");
      if (method_exists($this,$render_func)) {
        $this->$render_func($render_arr);
      } else if (function_exists($render_func)) {
        $render_func($render_arr);
      } else {
        if (!empty($this->err_func))
          $this->err_func(sprintf($this->form_text['error']['render'],$render_func));
      }
    }
    echo "</div>"; //*/
  }

  public function render_multi_options($args) {
  }


  /**  Render Items functions  **/

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
    $field = str_replace(array('[',']'),array('_',''),$name);
    $html = "<div data-title='{$media['title']}' data-button='{$media['button']}' data-field='$field'>";
    $html.= "  <button type='button' class='form-image'>{$media['button']}</button>";
    $html.= "  <input id='{$field}_input' type='text' class='hidden' name='$name' value='$value' />";
    $html.= "  <div class='form-image-container".((empty($value)) ? " hidden'" : "'")."><img id='{$field}_img' src='$value'></div>";
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
        $select = ($key==$data['value']) ? "selected='selected'" : '';
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
    echo "<input type='text' id='$ID' class='$class' name='$name' value='$value'/> ";
    if (!empty($layout['text'])) echo esc_attr($layout['text']);
  }


  /**  Validate functions  **/

  public function validate_settings($input) {
    if (isset($_POST['tab'])) {
      $option = sanitize_key($_POST['tab']);
      $output = $this->defaults[$key];
    } else {
      $output = $this->defaults;
    }
    if (isset($_POST['reset'])) {
      add_settings_error('creatom','restore_defaults',__('Default values restored','creatom'),'updated fade');
      return $output;
    }
    foreach($input as $key=>$data) {
      if ((array)$data==$data) {
        foreach($data as $ID=>$subdata) {
          $valid_func = $this->determine_validate($this->form[$key]['layout'][$ID]);
          $output[$key][$ID] = $this->do_validate_function($subdata,$valid_func);
        }
      } else {
#        $valid_func = $this->determine_validate($form[$key]);
#        $output[$key] = $this->do_validate_function($data,$valid_func);
      }
    }
    return apply_filters('basic_form_validate_settings',$output,$input);
  }

  private function determine_validate($item=array()) { // FIXME: re: sanitize_callback()
    $defs  = array('render'=>'non_existing_function_name');
    $item  = array_merge($defs,$item);
    $func  = 'validate_';
    $func .= (isset($item['validate'])) ? $item['validate'] : $item['render'];
    return $func;
  }

  private function do_validate_function($input,$func) {
    if (method_exists($this,$func)) {
      $output = $this->$func($input);
    } else if (function_exists($func)) {
      $output = $func($input);
    } else {
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
