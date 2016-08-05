<?php

/*
 *  classes/admin-form.php
 *
 *  copyright 2014-2016, The Creative Collective, the-creative-collective.com
 */

abstract class Basic_Admin_Form {

  protected $current   = '';
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
    add_action('admin_init',         array($this,'load_form_page'));
    add_action('customize_register', array($this,'customize_register'));
    if (defined('THEME_OPTION_DEFAULT')) { $this->tab = THEME_OPTION_DEFAULT; }
  }

  public function load_form_page() {
    global $plugin_page;
    if (($plugin_page==$this->slug) || (($refer=wp_get_referer()) && (strpos($refer,$this->slug)))) {
      if (isset($_GET['tab']))  $this->tab = sanitize_key($_GET['tab']);
      if (isset($_POST['tab'])) $this->tab = sanitize_key($_POST['tab']);
      $this->form_text = $this->form_text();
      $this->form = $this->form_layout();
      $this->determine_option();
      $this->get_form_options();
      $func = $this->register;
      $this->$func();
      add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));
    }
  }

  public function customize_register($wp_customize) {
    $this->form_text = $this->form_text();
    $this->form = $this->form_layout();
    do_action('fluid-customizer',$wp_customize,$this);
  }

  public function enqueue_scripts() {
    wp_register_style('admin-form.css', get_template_directory_uri()."/css/admin-form.css", false);
    wp_register_script('admin-form.js', get_template_directory_uri()."/js/admin-form.js", array('jquery','wp-color-picker'), false, true);
    wp_enqueue_media();
    wp_enqueue_style('admin-form.css');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('admin-form.js');
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

  public function register_tabbed_form() {
    $validater = (isset($this->form['validate'])) ? $this->form['validate'] : $this->validate;
    foreach($this->form as $key=>$section) {
      if (!((array)$section===$section)) continue; // skip string variabler
      if (!($section['option']===$this->current)) continue;
      $validate = (isset($section['validate'])) ? $section['validate'] : $validater;
      $current  = (isset($this->form[$key]['option'])) ? $this->form[$key]['option'] : $this->prefix.$key;
      #register_setting($this->slug,$current,array($this,$validate));
      register_setting($current,$current,array($this,$validate));
      $title    = (isset($section['title']))    ? $section['title']    : '';
      $describe = (isset($section['describe'])) ? $section['describe'] : 'description';
      $describe = (is_array($describe)) ? $describe : array($this,$describe);
      #add_settings_section($current,$title,$describe,$this->slug);
      add_settings_section($current,$title,$describe,$current);
      foreach($section['layout'] as $item=>$data) {
        $this->register_field($current,$key,$item,$data);
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
      #add_settings_field($itemID,$label,array($this,$this->options),$this->slug,$option,$args);
      add_settings_field($itemID,$label,array($this,$this->options),$option,$option,$args);
#    }
  }

  private function field_label($ID,$data) {
    $html = "<span";
    if ($data['render']=='display') {
      $html.= (isset($data['help'])) ? " title='{$data['help']}'>" : ">";
      $html.= $data['label'];
      $html.= "</span>";
    } elseif ($data['render']=='title') {
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


  /**  Customizer  **/

  public function customizer_settings($wp_customize,$base) {
    if ($base && $this->form[$base]) {
      $layout = $this->form[$base];
log_entry('customizer',"base: $base",$layout,$wp_customize);
      foreach($layout as $key=>$option) {
        if (!isset($option['default'])) continue;
        if ($option['render']==='skip') continue;
        $name = "tcc_options_{$base}[$key]";
        $settings = array('default'    => $option['default'],
                          'type'       => 'option',
                          'capability' => 'edit_theme_options',
                          'sanitize_callback' => $this->sanitize_callback($option));
        $wp_customize->add_setting($name,$settings);
        #  default WordPress sections
        $wp_sections = array('title_tagline','colors','header_image','background_image','nav','static_front_page');
        $section  = (in_array($base,$wp_sections)) ? $base : "fluid_$base";
        $controls = array('label'    => $option['label'], // FIXME: use $this->field_label($ID,$option) instead, what would $ID be?
                          'section'  => $section,
                          'settings' => $name);
        switch($option['render']) {
          case "checkbox":
            $controls['type'] = 'checkbox';
            break;
          case "colorpicker":
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,$name,$controls));
            $name = false;
            break;
          case "image": // FIXME: does not work as advertised
            //$controls['type'] = 'image';
            if (isset($option['context'])) $controls['context'] = $option['context'];
tcc_log_entry($controls);
            $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,$name,$controls));
            break;
          case "radio":
            $controls['type']    = 'radio';
            $controls['choices'] = $option['source'];
            break;
          case "select":
            if (!is_array($option['source'])) continue; // FIXME: this action leaves an orphaned setting with no control
            $controls['type']    = 'select';
            $controls['choices'] = $option['source'];
            break;
          default:
            tcc_log_entry("WARNING:  switch case needed in customizer_settings for {$option['render']}");
            $name = false;
        }
        if ($name) $wp_customize->add_control($name,$controls);
      }
    }
  }

  private function sanitize_callback($option) {
    $valid_func = "validate_{$option['render']}";
    if (method_exists($this,$valid_func)) {
      $retval = array($this,$valid_func);
    } else if (function_exists($valid_func)) {
      $retval = $valid_func;
    } else {
      $retval = 'wp_kses_post';
    }
    return $retval;
  }


  /**  Data functions  **/

  private function determine_option() {
    if ($this->type=='single') {
      $this->current = $this->slug;
    } elseif ($this->type=='tabbed') {
      if (isset($this->form[$this->tab]['option'])) {
        $this->current = $this->form[$this->tab]['option'];
      } else {
        $this->current = $this->prefix.$this->tab;
      }
    }
  }

  protected function get_defaults($option) {
    if (empty($this->form)) { $this->form = $this->form_layout(); }
    $defaults = array();
    if ($this->type=='single') {
      foreach($this->form as $key=>$group) {
        if (is_string($group)) continue;
        foreach($group['layout'] as $ID=>$item) {
          if (empty($item['default'])) continue;
          $defaults[$key][$ID] = $item['default'];
        }
      }
    } else {  #  tabbed page
      if (isset($this->form[$option])) {
        foreach($this->form[$option]['layout'] as $key=>$item) {
          if (empty($item['default'])) continue;
          $defaults[$key] = $item['default'];
        }
      } else {
        if (!empty($this->err_func)) {
          $func = $this->err_func;
          $func(sprintf($this->form_text['error']['subscript'],$option),debug_backtrace());
        }
      }
    }
    return $defaults;
  } //*/

  private function get_form_options() {
    $this->form_opts = get_option($this->current);
    if (empty($this->form_opts)) {
      $option = explode('_',$this->current); // FIXME: explode for tabbed, what about single?
      $this->form_opts = $this->get_defaults($option[2]);
      add_option($this->current,$this->form_opts);
    }
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
        settings_fields($current); #$this->slug); #$this->current);
        do_settings_sections($current); #$this->slug); #$this->current);
        do_action("fluid_post_display_".$this->tab);
        $this->submit_buttons($this->form[$this->tab]['title']); ?>
      </form>
    </div><?php //*/
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
    extract($args);  #  array( 'key'=>$key, 'item'=>$item, 'num'=>$i);
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
        #if ((isset($add)) && ($add)) { $layout[$item]['add'] = true; }
        $value = (isset($data[$key][$item][$num])) ? $data[$key][$item][$num] : '';
      }
      $field = str_replace(array('[',']'),array('_',''),$name);
      $fargs = array('ID'=>$field, 'value'=>$value, 'layout'=>$layout[$item], 'name'=>$name);
      if (method_exists($this,$func)) {
        $this->$func($fargs);
      } elseif (function_exists($func)) {
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
    extract($args);  #  $args = array( 'key' => {group-slug}, 'item' => {item-slug})
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
      } elseif (function_exists($func)) {
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
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    if (!(isset($layout['type']))) { $layout['type'] = 'text'; }
    if ($layout['type']==='image') {
      $this->render_image($data);
    } else {
      $this->render_text($data);
    }
  }

  private function render_checkbox($data) {
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    $html = "<label>";
    $html.= "<input type='checkbox' id='$ID' name='$name' value='yes' ";
    $html.= checked('yes',$value,false);
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}'" : "";
    $html.= "/> <span>{$layout['text']}</span></label>";
    echo $html;
  }

  private function render_colorpicker($data) {
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    $html = "<input type='text' value='$value' class='form-colorpicker'";
    $html.= " data-default-color='{$layout['default']}' name='$name' />";
    if (!empty($layout['text'])) $html.= " <span style='vertical-align: top;'>{$layout['text']}</span>";
    echo $html;
  }

  private function render_display($data) {
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    if (!empty($value)) echo $value;
    if (!empty($layout['text'])) echo " <span>{$layout['text']}</span>";
  }

  private function render_font($data) {
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
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
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
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
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
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
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    if (empty($layout['source'])) return;
    $source_func = $layout['source'];
    if (!empty($layout['text'])) echo "<div class='form-select-text'> ".esc_attr($layout['text'])."</div>";
    $html = "<select id='$ID' name='$name'";
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}'>" : ">";
    echo $html;
    if (is_array($source_func)) {
      foreach($source_func as $key=>$text) {
        $select = ($key==$value) ? "selected='selected'" : '';
        echo "<option value='$key' $select> $text </option>";
      }
    } elseif (method_exists($this,$source_func)) {
      $this->$source_func($value);
    } elseif (function_exists($source_func)) {
      $source_func($value);
    }
    echo '</select>';
  }

  private function render_text($data) {
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    $html = (!empty($layout['text']))  ? "<p> ".esc_attr($layout['text'])."</p>" : "";
    $html.= "<input type='text' id='$ID' class='";
    $html.= (isset($layout['class']))  ? $layout['class']."'" : "regular-text'";
    $html.= " name='$name' value='".esc_attr(sanitize_text_field($value))."'";
    $html.= (isset($layout['help']))   ? " title='{$layout['help']}'"         : "";
    $html.= (isset($layout['place']))  ? " placeholder='{$layout['place']}'"  : "";
    $html.= (isset($layout['change'])) ? " onchange='{$layout['change']}' />" : "/>";
    echo $html;
  }

  private function render_title($data) {
    extract($data);  #  array('ID'=>$item, 'value'=>$data[$item], 'layout'=>$layout[$item], 'name'=>$name)
    if (!empty($layout['text'])) {
      $data['layout']['text'] = "<b>{$layout['text']}</b>"; }
    $this->render_display($data);
  }

  /**  Validate functions  **/

  public function validate_single_form($input) {
    $form   = $this->form;
    $output = $this->get_defaults();
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
    foreach($output as $key=>$data) {
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
    $output = $this->get_defaults($option);
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
    } elseif (function_exists($func)) {
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
