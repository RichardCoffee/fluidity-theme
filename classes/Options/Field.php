<?php

if (!class_exists('TCC_Options_Field')) {

// References: http://codex.wordpress.org/Modifying_Options_Pages
class TCC_Options_Field {

  private $call    = 'esc_attr';
  private $default = '';
  private $group   = '';
  private $html    = 'field_html';
  private $label   = '';
  private $name    = '';
  private $rest    = false;
  private $type    = false;  #  'number','string','boolean'

  public function __construct($args) {
    foreach($args as $key=>$value) {
      if (property_exists($this,$key)) {
        $this->$key = $value;
      }
    }
    add_filter('admin_init',array(&$this,'register_fields'));
  }

  public function register_fields() {
    if (version_compare($GLOBALS['wp_version'],'4.7','<')) {
      $args = $this->call; }
    else {
      $args = array('sanitize_callback' => $this->call);
      if ($this->type)    { $args['type']         = $this->type; }
      if ($this->label)   { $args['description']  = $this->label; }
if ($this->rest)    { $args['show_in_rest'] = $this->rest; }
      if ($this->default) { $args['default']      = $this->default; }
    }
    register_setting($this->group,$this->name,$args);
    $label = "<label for='{$this->name}'>{$this->label}</label>";
    add_settings_field($this->name,$label,array(&$this,$this->html),$this->group);
  }

  public function field_html() {
    $call  = $this->call;
    $value = $call(get_option($this->name));
    if (empty($value)) $value = $call($this->default);
    echo "<input type='text' id='{$this->name}' name='{$this->name}' value='$value' />";
  }

}

} # class_exists
