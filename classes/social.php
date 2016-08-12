<?php


class Theme_Social_Icons {

  private $base = 'social';

  public function __construct() { # Fluidity_Options_Form $form) {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),100);
  }

  public function form_layout($form) {
    $form[$this->base] = array('describe' => array($this,'describe_social'),
                            'title'    => __('Social Icons','tcc-fluid'),
                            'option'   => 'tcc_options_social',
                            'layout'   => $this->social_layout());
    return $form;
  }

  public function describe_social() {
    _e('Fluidity Social Icons (powered by Font Awesome)','tcc-fluid');
  }

  public function social_layout() {
    $layout = array('default'=>true);
    $layout['active'] = array('default' => 'no',
                              'label'   => __('Use Theme Icons','tcc-fluid'),
                              'help'    => __('Contact us if you need help with a third-party plugin','tcc-fluid'),
                              'render'  => 'radio',
                              'source'  => array('yes' => __("Yes - you want to use the theme's internal social icons",'tcc-fluid'),
                                                 'no'  => __("No -- you are using a plugin, or do not want social icons",'tcc-fluid')),
                              'change'  => 'showhidePosi(this,".social-option-icon","yes");',
                              'divcss'  => 'social-option-active');
    $icons = array('Bitbucket'   => 'black',  'Facebook' => '#4C66A4', 'GitHub'    => 'black',
                   'Google Plus' => 'red',    'LinkedIN' => '#287BBC', 'Pinterest' => 'black',
                   'RSS'         => 'orange', 'Tumblr'   => 'black',   'Twitter'   => '#0084B4',
                   'Xing'        => 'black',  'YouTube'  => 'red');
    foreach($icons as $icon=>$color) {
      $key = sanitize_title($icon);
      $layout[$key] = array('default' => ($icon==='RSS') ? site_url('/feed/') : '',
                            'label'   => $icon,
                            'color'   => $color,
                            'help'    => __('Your link information goes here','tcc-fluid'),
                            'place'   => sprintf("%s %s",$icon,__('site url','tcc-fluid')),
                            'render'  => 'text_color',
                            'divcss'  => 'social-option-icon');
    }
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }


}
