<?php

class TCC_Options_Settings {

  private $base     = 'admin';
  private $priority = 500;

  public function __construct() {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),$this->priority);
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
    $layout['postdate'] = array('default' => 'modified',
                                'label'   => esc_html__('Post Edit Date','tcc-fluid'),
                                'render'  => 'radio',
                                'source'  => array('modified' => esc_html__('Use modified date, where applicable','tcc-fluid'),
                                                   'original' => esc_html__('Always use original post date','tcc-fluid')));
    $layout['heart'] = array('default' => 'on',
                             'label'   => esc_html__('WP Heartbeat','tcc-fluid'),
                             'text'    => esc_html__('Control the status of the WordPress Heartbeat API','tcc-fluid'),
                             'help'    => esc_html__('The Heartbeat API will always remain active on these pages: post.php, post-new.php, and admin.php','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('on'  => esc_html__('On','tcc-fluid'),
                                                'off' => esc_html__('Off','tcc-fluid')));
    $layout['where'] = array('default' => 'off',
                             'label'   => esc_html__('Where Am I?','tcc-fluid'),
                             'text'    => esc_html__('Display template file names on site front end - for development only','tcc-fluid'),
                             'help'    => esc_html__('Hi!','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('on'  => esc_html__('On','tcc-fluid'),
                                                'off' => esc_html__('Off','tcc-fluid')));/*
    $layout['coming'] = array('default' => 'off',
                              'label'   => esc_html__('Coming Soon','tcc-fluid'),
                              'text'    => esc_html__('Show a Coming Soon page','tcc-fluid'),
                              'help'    => esc_html__('Take your site down temporarily','tcc-fluid'),
                              'render'  => 'radio',
                              'source'  => array('off' => esc_html__('Show site','tcc-fluid'),
                                                 'on'  => esc_html__('Show Coming Soon page','tcc-fluid'))); //*/
    $layout['autocore'] = array('default' => 'on',
                                'label'   => esc_html__('Core Update','tcc-fluid'),
                                'text'    => esc_html__('Automatically update WordPress core files.','tcc-fluid'),
                                'render'  => 'radio',
                                'source'  => array('on'  => esc_html__('Automatically update for new versions. (recommended)','tcc-fluid'),
                                                   'off' => esc_html__('Manually update core files.','tcc-fluid'))); //*/
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }



}
