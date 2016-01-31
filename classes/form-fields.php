<?php

/*  
 *  File:   classes/form-fields.php
 *
 */

class Form_Field {

  protected $click;
  protected $echo;
  protected $field_css;
  protected $field_name;
  protected $label_css;
  protected $label_text;
  protected $placeholder;
  protected $post_id;

  public function __construct($args) {
    foreach($args as $key=>$value) {
      $this->$key = $value;
    }
  }

}

class Admin_Field extends Form_Field {

  public function input() {

  }

}

class Meta_Field extends Form_Field {

  public function __construct($args) {
    parent::__construct($args);
    $this->value = get_post_meta($this->post_id,$this->field_name,true);
  }

  public function input() {
    $html = "<label class='{$this->label_css}' for='{$this->field_name}'>{$this->label_text}</label>";
    $html.= "<input id='{$this->field_name}' type='text' class='{$this->field_css}' name='{$this->field_name}'";
    $html.= "value='{$this->value}' placeholder='{$this->placeholder}' />";
    echo $html;
  }

}

class Theme_Field extends Form_Field {

  public function input() {

  }

}
