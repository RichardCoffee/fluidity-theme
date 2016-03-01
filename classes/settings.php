<?php

class Theme_Admin_Options {

  private $base     = 'admin';

  public function __construct() { #Fluidity_Options_Form $form) {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),500);
  }

  private function form_title() {
    return __('Design','tcc-fluid');
  }

  public function form_layout($form) {
    $form[$this->base] = array('describe' => array($this,'describe_options'),
                               'title'    => $this->form_title(),
                               'option'   => 'tcc_options_'.$this->base,
                               'layout'   => $this->options_layout());
    return $form;
  }

  public function describe_options() {
    _e('Design Options','tcc-fluid');
  }

  protected static function options_layout() {
    $layout = array('default'=>true);
    $layout['heart'] = array('default' => 'on',
                             'label'   => __('WP Heartbeat','tcc-fluid'),
                             'text'    => __('Control the status of the WordPress Heartbeat API','tcc-fluid'),
                             'help'    => __('If turned Off, the Heartbeat API will remain active on these pages: post.php, post-new.php, and admin.php','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('on'  => __('On','tcc-fluid'),
                                                'off' => __('Off','tcc-fluid')));
    return $layout;
  }



}
