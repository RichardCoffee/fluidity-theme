<?php

class TCC_Basic_Widget extends WP_Widget {

  protected $title = '';
  protected $desc  = '';
  protected $slug  = '';
  public static $micro = null;

  function __construct($slug='',$title='',$desc=array()) {
    parent::__construct($this->slug,$this->title,array('description'=>$this->desc));
    if (!self::$micro) self::$micro = TCC_Microdata::get_instance();
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
    $form.= __('Title:','tcc-fluid')."</label>";
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

class TCC_Address_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = __('Address','tcc-fluid');
    $this->desc  = $this->title;
    $this->slug  = 'tcc_address';
    parent::__construct();
  }

  public function inner_widget($args,$instance) { ?>
    <div <?php $micro->Organization(); ?>>
      <h4 class="text-center" itemprop="name"><?php bloginfo('title'); ?></h4>
      <!-- FIXME: address needs to be editable option in theme options -->
      <address class="text-center" <?php self::$micro->PostalAddress(); ?>>
        <span itemprop="streetAddress">123 Main Street</span><br>
        <span itemprop="addressLocality">Van</span> <span itemprop="addressRegion">TX</span>, <span itemprop="postalCode">75790</span><br>
        Office: <span itemprop="telephone">888 555 1212</span><br>
        Email: <a href="mailto:<?php echo get_option('admin_email'); ?>"><?php bloginfo ('title');?> </a>
      </address>
    </div><?php
  }
  
}

class TCC_Login_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = __('Login & Registration','tcc-fluid');
    $this->desc  = $this->title;
    $this->slug  = 'tcc_login';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    tcc_login_form();
  }

}

class TCC_Logo_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = __('Site Logo','tcc-fluid');
    $this->desc  = $this->title;
    $this->slug  = 'tcc_logo';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    $logo = tcc_design('logo'); ?>
    <a href="<?php self::$micro->bloginfo('url'); ?>/">
      <img itemprop="logo" class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
    </a><?php
  }

}

do_action('tcc_widget_class_loaded');

function register_fluid_widgets() {
  register_widget('TCC_Login_Widget');
  register_widget('TCC_Logo_Widget');
  do_action('tcc_register_widgets');
}
add_action('widgets_init','register_fluid_widgets'); ?>
