<?php

class TCC_Basic_Widget extends WP_Widget {

  protected $title = '';
  protected $desc  = '';
  protected $slug  = '';

  function __construct($slug='',$title='',$desc=array()) {
    if (empty($this->slug)) $this->slug = $slug; // FIXME:  update all current widgets to use $this->slug
    parent::__construct($this->slug,$this->title,array('description'=>$this->desc));
  }

  protected function pre_widget($args) {
    $title = apply_filters('widget_title',$args['tcc-title']);
    echo $args['before_widget'];
    if (!empty($title))
      echo $args['before_title'].$title.$args['after_title'];
  }

  public function widget($args,$instance) {
    $args['tcc-title'] = $instance['title'];
    $this->pre_widget($args);
    $this->inner_widget($args,$instance);
    $this->post_widget($args);
  }

  protected function post_widget($args) {
    echo $args['after_widget'];
  }

  public function form($instance) {
    $this->form_title($instance);
  }

  protected function form_title($instance) {
    $instance['title'] = (isset($instance['title'])) ? $instance['title'] : $this->title;
    $form = "<p><label for='".$this->get_field_id('title')."'>";
    $form.= __('Title:','fluid-theme')."</label>";
    $form.= "<input type='text' class='widefat'";
    $form.= " id='"   .$this->get_field_id('title')  ."'";
    $form.= " name='" .$this->get_field_name('title')."'";
    $form.= " value='".esc_attr($instance['title'])  ."'";
    $form.= " /></p>";
    echo $form;
  }

  public function update($new,$old) {
    $instance = $old;
    $instance['title'] = (!empty($new['title'])) ? strip_tags($new['title']) : '';
    return $instance;
  }

}

class TCC_Login_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = __('Login & Registration','fluid-theme');
    $this->desc  = $this->title;
    $this->slug  = 'tcc_login';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    tcc_login_form();
  }

}
do_action('tcc_widget_class_loaded');

function register_fluid_widgets() {
  register_widget('TCC_Login_Widget');
  do_action('tcc_register_widgets');
}
add_action('widgets_init','register_fluid_widgets'); ?>
