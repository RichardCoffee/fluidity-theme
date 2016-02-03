<?php

/*  
 *  File:   classes/form-fields.php
 *
 */

abstract class Form_Field {

  protected $callback = 'input';
  protected $click;
  protected $css      = '';
  protected $echo     = true;
  protected $id;
  protected $label; // label css
  protected $name;
  protected $placeholder;
  protected $post_id;
  protected $text     = '';
  protected $type     = 'text';
  protected $value;

  public function __construct($args) {
    foreach($args as $key=>$value) {
      $this->$key = $value;
    }
    if ((empty($this->placeholder)) && (!empty($this->text))) {
      $this->placeholder = $this->text; }
  }

  public function input($label=true) {
    $html = '';
    if ($label && $this->text) { $html.= $this->label(); }
    $html.= "<input";
    $html.= (empty($this->id)) ? " id='{$this->name}'" : " id='{$this->id}'";
    $html.= " type='{$this->type}'";
    $html.= " class='{$this->css}'";
    $html.= " name='{$this->name}'";
    $html.= " value='{$this->value}'";
    $html.= " placeholder='{$this->placeholder}'";
    $html.= " />";
    echo $html;
  }

  protected function label() {
    $html = "<label";
    $html.= ($this->label) ? " class='{$this->label}'" : "";
    $html.= " for='".((empty($this->id)) ? $this->name : $this->id);
    $html.= "'>{$this->text}</label>";
    return $html;
  }

}

class Admin_Field extends Form_Field {

  protected $sanit   = 'esc_attr';
  protected $default = '';
  protected $group;

  public function __construct($args) {
    parent::__construct($args);
    $sanit = $this->sanit;
    $this->value = $clean(get_option($this->name));
    if (empty($value)) $value = $sanit($this->default);
    add_filter('admin_init',array(&$this,'register_field'),9);
  }

  public function register_field() {
    if (!empty($this->group)) {
      register_setting($this->group,$this->name,$this->clean);
      $callback = (is_array($this->callback)) ? $this->callback : array(&$this,$this->callback);
      add_settings_field($this->name, $this->label(), $callback, $this->group);
    }
  }

  public function input($label=false) {
    parent::input($label);
  }

}

class Meta_Field extends Form_Field {

  public function __construct($args) {
    parent::__construct($args);
    $this->value = get_post_meta($this->post_id,$this->name,true);
  }

}

class Theme_Field extends Form_Field {

}
