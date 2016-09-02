<?php

class Theme_Admin_Options {

  private $base     = 'admin';

  public function __construct() { #Fluidity_Options_Form $form) {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),500);
  }

  private function form_title() {
    return esc_html__('Settings','tcc-fluid');
  }

  public function form_layout($form) {
    $form[$this->base] = array('describe' => array($this,'describe_options'),
                               'title'    => $this->form_title(),
                               'option'   => 'tcc_options_'.$this->base,
                               'layout'   => $this->options_layout());
    return $form;
  }

  public function describe_options() {
    esc_html_e('Theme Behavior Options','tcc-fluid');
  }

  protected function options_layout() {
    $layout = array('default'=>true);
    $layout['heart'] = array('default' => 'on',
                             'label'   => esc_html__('WP Heartbeat','tcc-fluid'),
                             'text'    => esc_html__('Control the status of the WordPress Heartbeat API','tcc-fluid'),
                             'help'    => esc_html__('The Heartbeat API will always remain active on these pages: post.php, post-new.php, and admin.php','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('on'  => esc_html__('On','tcc-fluid'),
                                                'off' => esc_html__('Off','tcc-fluid')));
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }



}
