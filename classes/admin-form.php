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
  protected $hook_suffix;
  protected $options;
  protected $prefix    = 'options_prefix_';
  protected $register;
  protected $render;
  protected $slug      = 'default_page_slug';
  public    $tab       = 'about';
  protected $type      = 'single'; # two values: single, tabbed
  protected $validate;

  abstract protected function form_layout($option);
  public function description() { return ''; }

  protected function __construct() {
    $this->screen_type();
    add_action('admin_init',array($this,'load_form_page'));
  }

  public function load_form_page() {
    global $plugin_page;
    if ($plugin_page===$this->slug) {
//log_entry("hook_suffix: ".$this->hook_suffix);
      if (isset($_GET['tab'])) $this->tab = sanitize_key($_GET['tab']);
//log_entry("tab: ".$this->tab);
      $this->form_text = $this->form_text();
      if (($plugin_page==$this->slug) || (($refer=wp_get_referer()) && (strpos($refer,$this->slug)))) {
        $this->form = $this->form_layout();
        $this->determine_option();
        $this->get_defaults();
        $this->get_form_options();
        $func = $this->register;
        $this->$func();
        add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));
      }
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
    $text = array('error'  => array('render'    => _x('ERROR: Unable to locate function %s','string - a function name','tcc-fluid'),
                                    'subscript' => _x('ERROR: Not able to locate form data subscript:  %s','placeholder will be an ASCII character string','tcc-fluid')),
                  'submit' => array('save'      => __('Save Changes','tcc-fluid'),
                                    'object'    => __('Form','tcc-fluid'),
                                    'reset'     => _x('Reset %s','placeholder is a noun, may be plural','tcc-fluid'),
                                    'subject'   => __('Form','tcc-fluid'),
                                    'restore'   => _x('Default %s options restored.','placeholder is a noun, probably singular','tcc-fluid')),
                  'media'  => array('title'     => __('Assign/Upload Image','tcc-fluid'),
                                    'button'    => __('Assign Image','tcc-fluid'),
                                    'delete'    => __('Unassign Image','tcc-fluid')));
    return apply_filters('form_text_'.$this->slug,$text,$text);
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

/*  public function register_tabbed_form() {
    $validater = (isset($this->form['validate'])) ? $this->form['validate'] : $this->validate;
    $larr = array('display','skip');
    foreach($this->form as $key=>$data) {
      if (!((array)$data===$data)) continue;  #  skip string variables
      if (!$this->tab===$key) continue;
      $option   = (isset($data['option']))   ? $data['option']   : $this->prefix.$key; #  also used for the group and section
      $validate = (isset($data['validate'])) ? $data['validate'] : $validater;
      register_setting($option,$option,array($this,$validate));
      $title    = (isset($data['title']))    ? $data['title']    : '';
      $describe = (isset($data['describe'])) ? $data['describe'] : 'description';
      $describe = (is_array($describe)) ? $describe : array($this,$describe);
      add_settings_section($option,$title,$describe,$this->slug);
      foreach($data['layout'] as $itemID=>$item) {
        $this->register_field($option,$key,$itemID,$item);
#        if (!isset($item['render'])) continue;
#        if ($item['render']=='skip') continue;
#        $label = (in_array($item['render'],$larr)) ? $item['label'] : "<label for='$itemID'>{$item['label']}</label>";
#        $label = ($item['render']=='title') ? "<span class='tcc-title'>{$item['label']}</span>" : $label;
#        add_settings_field($itemID, $label, array(__CLASS__,'render_options'), $option, $option, array($key,$itemID));
      }
    }
  } //*/

  public function register_tabbed_form() {
    $validater = (isset($this->form['validate'])) ? $this->form['validate'] : $this->validate;
    foreach($this->form as $key=>$section) {
      if (!((array)$section===$section)) continue; // skip string variables
      if (!($section['option']===$this->current)) continue;
      $validate = (isset($section['validate'])) ? $section['validate'] : $validater;
      $current  = (isset($this->form[$key]['option'])) ? $this->form[$key]['option'] : $this->prefix.$key;
      register_setting($this->slug,$this->slug,array($this,$validate));
      $title    = (isset($section['title']))    ? $section['title']    : '';
      $describe = (isset($section['describe'])) ? $section['describe'] : 'description';
      $describe = (is_array($describe)) ? $describe : array($this,$describe);
      #add_settings_section($current,$title,$describe,$this->slug);
      add_settings_section($this->slug,$title,$describe,$this->slug);
      foreach($section['layout'] as $item=>$data) {
        #$this->register_field($current,$key,$item,$data);
        $this->register_field($this->slug,$key,$item,$data);
      }
    }
  } //*/

  private function register_field($option,$key,$itemID,$data) {
    if (is_string($data))        return; // skip string variables
    if (!isset($data['render'])) return;
    if ($data['render']=='skip') return;
/*    if ($data['render']=='array') {
      $count = max(count($data['default']),count($this->form_opts[$key][$itemID]));
      for ($i=0;$i<$count;$i++) {
        $label  = "<label for='$itemID'>{$data['label']} ".($i+1)."</label>";
        $args   = array('key'=>$key,'item'=>$itemID,'num'=>$i);
#        if ($i+1==$count) { $args['add'] = true; }
        add_settings_field("{$item}_$i",$label,array($this,$this->options),$this->slug,$current,$args);
      }
    } else { //*/
      $label = $this->field_label($itemID,$data);
      $args  = array('key'=>$key,'item'=>$itemID);
      add_settings_field($itemID,$label,array($this,$this->options),$this->slug,$option,$args);
      #add_settings_field($itemID,$label,array($this,$this->options),$option,$option,$args);
#    }
  }

  private function field_label($ID,$data) {
    $html = "<span";
    if ($data['render']=='display') {
      $html.= (isset($data['help'])) ? " title='{$data['help']}'>" : ">";
      $html.= $data['label'];
      $html.= "</span>";
    } else if ($data['render']=='title') {
      $html.= " class='form-title'";
      $html.= (isset($data['help'])) ? " title='{$data['help']}'>" : ">";
      $html.= $data['label'];
      $html.= "</span>";
    } else {
      $html = "<label for='$ID'";
      $html.= (isset($data['help'])) ? " title='{$data['help']}'>" : ">";
      $html.= $data['label'];
      $html.= "</label>";
    }
    return $html;
  }


  /**  Data functions  **/

  private function determine_option() {
    if ($this->type=='single') {
      $this->current = $this->slug;
    } else if ($this->type=='tabbed') {
      if (isset($this->form[$this->tab]['option'])) {
        $this->current = $this->form[$this->tab]['option'];
      } else {
        $this->current = $this->prefix.$this->tab;
      }
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
          $func(sprintf($this->form_text['error']['subscript'],$option));
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
      <form method="post" action="options.php">
        <input type='hidden' name='tab' value='<?php echo $this->tab; ?>'><?php
        $current  = (isset($this->form[$this->tab]['option'])) ? $this->form[$this->tab]['option'] : $this->prefix.$this->tab;
        do_action("fluid_pre_display_".$this->tab);
        settings_fields($this->slug); #$this->current);
        do_settings_sections($this->slug); #$this->current);
        do_action("fluid_post_display_".$this->tab);
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

  // $args = array('key'=>$key,'item'=>$item); // ,'num'=>$i);
  public function render_single_options($args) {
    extract($args);
    $data   = $this->form_opts;
    $layout = $this->form[$key]['layout'];
    $class  = (!empty($layout[$item]['divcss'])) ? "class='{$layout[$item]['divcss']}'" : '';
    echo "<div $class>";
    if (empty($layout[$item]['render'])) {
      echo $data[$key][$item];
    } else {
      $func  = "render_{$layout[$item]['render']}";
      $name  = $this->slug."[$item]";
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
    $html   = "<div";
    $html  .= (!empty($layout[$item]['divcss'])) ? " class='{$layout[$item]['divcss']}'" : "";
    $html  .= (isset($layout[$item]['help'])) ? " title='{$layout[$item]['help']}'>" : ">";
    echo $html;
    if (empty($layout[$item]['render'])) {
      echo $data[$item];
    } else {
      $func = "render_{$layout[$item]['render']}";
      $name = $this->current."[$item]";
      if (!isset($data[$item])) $data[$item] = (empty($layout[$item]['default'])) ? '' : $layout[$item]['default'];
      $fargs = array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name);
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

  private function render_checkbox($data) {
    extract($data);
    $html = "<label>";
    $html.= "<input type='checkbox' id='$ID' name='$name' value='yes' ";
    $html.= checked('yes',$value,false);
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}'" : "";
    $html.= "/> <span>{$layout['text']}</span></label>";
    echo $html;
  }

  private function render_colorpicker($data) {
    extract($data);
    $html = "<input type='text' value='$value' class='form-colorpicker'";
    $html.= " data-default-color='{$layout['default']}' name='$name' />";
    if (!empty($layout['text'])) $html.= " <span style='vertical-align: top;'>{$layout['text']}</span>";
    echo $html;
  }

  private function render_display($data) {
    extract($data);
    if (!empty($value)) echo $value;
    if (!empty($layout['text'])) echo " <span>{$layout['text']}</span>";
  }

  private function render_font($data) {
    extract($data);
    $html = "<select id='$ID' name='$name'";
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}'>" : ">";
    foreach($layout['source'] as $key=>$text) {
      $html.= "<option value='$key'";
      $html.= ($key===$value) ? " selected='selected'" : '';
      $html.= "> $key </option>";
    }
    $html.= '</select>';
    $html.= (!empty($data['layout']['text'])) ? "<span class=''> {$data['layout']['text']}</span>" : '';
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

  private function render_radio($data) {
    extract($data);
    if (empty($layout['source'])) return;
    $uniq = uniqid();
    if (isset($layout['text'])) echo "<div id='$uniq'>".esc_attr($layout['text'])."</div>";
    foreach($layout['source'] as $key=>$text) {
      $html = "<div><label>";
      $html.= "<input type='radio' name='$name' value='$key'";
      $html.= ($value==$key) ? " checked='yes'" : "";
      $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}'" : "";
      $html.= (isset($layout['text']))   ? " aria-describedby='$uniq'"       : "";
      $html.= "> $text</label></div>";
      echo $html;
    }
    if (isset($layout['postext'])) echo "<div>{$layout['postext']}</div>";
  }

  private function render_select($data) {
    extract($data);
    if (empty($layout['source'])) return;
    $source_func = $layout['source'];
    $html = "<select id='$ID' name='$name'";
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}'>" : ">";
    echo $html;
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
    $html = "<input type='text' id='$ID' class='";
    $html.= (isset($layout['class'])) ? $layout['class'] : 'regular-text';
    $html.= "' name='$name' value='".esc_attr(sanitize_text_field($value))."'";
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}' />" : "/>";
    $html.= (!empty($layout['text']))  ? "<span class=''> ".esc_attr($layout['text'])."</span>" : '';
    echo $html;
  }

  private function render_title($data) {
    extract($data);
    if (!empty($layout['text']))
      $layout['text'] = "<strong>{$layout['text']}</strong>";
    $this->render_display($data);
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
            $vals[] = $this->do_validate_function($indiv,$item);
          }
          $output[$key][$ID] = $vals;
        } else {
          $output[$key][$ID] = $this->do_validate_function($subdata,$item);
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
#log_entry('_GET',$_GET);
#log_entry('_POST',$_POST);
#log_entry('form',$this->form);
#log_entry('input',$input);
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
          $output[$key][$ID] = $this->do_validate_function($subdata,$this->form[$option]['layout'][$key]);
        }
      } else {
        $output[$key] = $this->do_validate_function($data,$this->form[$option]['layout'][$key]);
      }
    }
#log_entry('output',$output);
    return apply_filters($this->current.'_validate_settings',$output,$input);
  }

  private function do_validate_function($input,$item) {
    if (empty($item['render'])) $item['render'] = 'non_existing_render_type';
    $func = 'validate_';
    $func.= (isset($item['validate'])) ? $item['validate'] : $item['render'];
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
