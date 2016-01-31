<?php

/*  
 *  File:   classes/form-fields.php
 *
 */

class Form_Field {

  protected $callback = 'input';
  protected $click;
  protected $css      = '';
  protected $echo     = true;
  protected $label; // label css
  protected $name;
  protected $placeholder;
  protected $post_id;
  protected $text     = '';

  public function __construct($args) {
    foreach($args as $key=>$value) {
      $this->$key = $value;
    }
    if ((empty($this->placeholder)) && (!empty($this->text))) {
      $this->placeholder = $this->text; }
  }

  public function input() {
    $html = '';
    if ($this->text) {
      $html.= "<label";
      $html.= ($this->label) ? " class='{$this->label}'" : "";
      $html.= " for='{$this->name}'>{$this->text}</label>";
    }
    $html.= "<input id='{$this->name}' type='text' class='{$this->css}' name='{$this->name}'";
    $html.= "value='{$this->value}' placeholder='{$this->placeholder}' />";
    echo $html;
  }

}

class Admin_Field extends Form_Field {

  protected $clean   = 'esc_attr';
  protected $default = '';
  protected $group;

  public function __construct($args) {
    parent::__construct($args);
    $clean = $this->clean;
    $value = $clean(get_option($this->name));
    if (empty($value)) $value = $clean($this->default);
    add_filter('admin_init',array(&$this,'register_field'),9);
  }

  public function register_field() {
#global $new_whitelist_options, $wp_settings_sections,$wp_settings_fields;
    if (!empty($this->group)) {
      register_setting($this->group,$this->name,$this->clean);
      $label = "<label for='{$this->name}'>{$this->text}</label>";
      add_settings_field($this->name,$label,array(&$this,$this->callback),$this->group);
    }
#log_entry($new_whitelist_options);
#log_entry($wp_settings_sections);
#log_entry($wp_settings_fields);
  }

  public function input() {
    $clean = $this->clean;
    $value = $clean(get_option($this->name));
    if (empty($value)) $value = $clean($this->default);
    echo "<input type='text' id='{$this->name}' class='{$this->css}' name='{$this->name}' value='$value' />";
  }

}

class Meta_Field extends Form_Field {

  public function __construct($args) {
    parent::__construct($args);
    $this->value = get_post_meta($this->post_id,$this->name,true);
  }

  public function input() {
    $html = '';
    if ($this->text) {
      $html.= "<label";
      $html.= ($this->label) ? " class='{$this->label}'" : "";
      $html.= " for='{$this->name}'>{$this->text}</label>";
    }
    $html.= "<input id='{$this->name}' type='text' class='{$this->css}' name='{$this->name}'";
    $html.= "value='{$this->value}' placeholder='{$this->placeholder}' />";
    echo $html;
  }

}

class Theme_Field extends Form_Field {

  public function input() {

  }

}
