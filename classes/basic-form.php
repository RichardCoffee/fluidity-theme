<?php

abstract class Basic_Admin_Form {

  protected $defaults  =  array();
  protected $err_func  =  null;
  protected $form      =  array();
  protected $options   = 'render_single_options';
  protected $prefix    = 'options_prefix_';
  protected $register  = 'register_single_form';
  protected $render    = 'render_single_form';
  protected $slug      = 'default_page_slug';
  protected $form_text =  array();
  protected $type      = 'single';
  protected $validate  = 'validate_single_form';

  abstract protected function form_layout($option);
  public function description() { return ''; }

  private function form_text() {
    $text = array('error'  => array('render'    => __('ERROR: Unable to locate function %s','tcc-fluid')),
                                    'subscript' => __('ERROR: Not able to locate form data subscript:  %s','tcc-fluid'),
                  'submit' => array('save'      => __('Save Changes','tcc-fluid'),
                                    'object'    => __('Form','tcc-fluid'),
                                    'reset'     => _x('Reset %s','placeholder is a noun, may be plural','tcc-fluid'),
                                    'subject'   => __('Form','tcc-fluid'),
                                    'restore'   => _x('Default %s options restored.','placeholder is a noun, probably singular','tcc-fluid')));
    return apply_filters('basic_form_text',$text,$text);
  }

  protected function __construct() {
    $this->screen_type();
    $this->form_text = $this->form_text();
    $this->form      = $this->form_layout();
    $this->defaults  = $this->get_defaults();
    $option = $this->determine_option();
    $curr   = get_option($option);
    if (!$curr) {
      $defs = $this->defaults;
      add_option($option,$defs);
    }
    add_action('admin_init',array($this,$this->register));
  }

  private function screen_type() {
    $type = $this->type;
    $this->register = "register_{$type}_form";
    $this->render   = "render_{$type}_form";
    $this->options  = "render_{$type}_options";
    $this->validate = "validate_{$type}_form";
  }


  /**  Register Screen functions **/

  public function register_single_form() {
    $option   = $this->determine_option();
    $data     = $this->get_form_options($option);
    register_setting($option,$option,array($this,$this->validate));
    foreach($this->form as $key=>$group) {
      if (is_string($group)) continue; // skip string variables
      $title = (isset($group['title']))    ? $group['title']    : '';
      $desc  = (isset($group['describe'])) ? $group['describe'] : 'description';
      add_settings_section($key,$title,array($this,$desc),$this->slug);
      foreach($group['layout'] as $itemID=>$item) {
        if (is_string($item)) continue; // skip string variables
        if (!isset($item['render'])) continue;
        $name = "{$key}_$itemID";
        $args = array('key'=>$key,'item'=>$itemID);
        $this->register_field($item,$name,$key,$args);
      }
    }
  }

  public function register_tabbed_form() {
    $validate = (isset($this->form['validate'])) ? $this->form['validate'] : $this->validate;
    foreach($this->form as $key=>$section) {
      if (!((array)$section===$section)) continue; // skip string variables
      $option   = $this->determine_option($key);
      $title    = (isset($section['title']))    ? $section['title']    : '';
      $validate = (isset($section['validate'])) ? $section['validate'] : $validate;
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

  private function determine_option($option='') {
    $option = (empty($option)) ? $this->slug : $this->prefix.$option ;
    $option = (isset($this->form[$option]['option'])) ? $this->form[$option]['option'] : $option;
    return $option;
  } //*/

  protected function get_defaults($option='') {
    $defs = array();
    if (empty($option)) { // single
      foreach($this->form as $key=>$group) {
        if (is_string($group)) continue;
        foreach($group['layout'] as $ID=>$item) {
          if (empty($item['default'])) continue;
          $defs[$key][$ID] = $item['default'];
        }
      }
    } else { // tabbed
      if (isset($this->form[$option])) {
        foreach($this->form[$option]['layout'] as $key=>$item) {
          if (empty($item['default'])) continue;
          $defs[$key] = $item['default'];
        }
      } else {
        if (!empty($this->err_func))
          $this->err_func(sprintf($this->form_text['error']['subscript'],$option));
      }
    }
    return $defs;
  }

  private function get_form_options($option) {
    $data = get_option($option);
    if (empty($data)) $data = $this->defaults;
    $data = array_replace_recursive($this->defaults,$data);
    return $data;
  }


  /**  Render Screen functions  **/

  public function render_single_form() {
    $form   = $this->form_layout();
    $option = $this->determine_option(); ?>
    <div class="wrap"><?php
      if (isset($form['title'])) {
        echo "<h2>{$form['title']}</h2>";
      }
      settings_errors(); ?>
      <form method="post" action="options.php"><?php
        do_action("form_pre_display");
        do_action("form_pre_display_$option");
        settings_fields($option);
        do_settings_sections($option);
        do_action("form_post_display_$option");
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
        $section = determine_option($active_tab);
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
        $reset = sprintf($buttons['reset'],$object);
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
    $option = determine_option($key);
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
