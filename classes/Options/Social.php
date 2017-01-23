<?php


class TCC_Options_Social {

  private $base = 'social';

  public function __construct() {
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
    $layout['target'] = array('default' => 'target',
                              'label'   => __('Target','tcc-fluid'),
                              'render'  => 'radio',
                              'source'  => array('target'  => __('Open in new tab (default)','tcc-fluid'),
                                                 'replace' => __('Load in same tab','tcc-fluid')),
                              'divcss'  => 'social-option-icon');
    $layout['size']   = array('default' => 'normal',
                              'label'   => __('Icon Size','tcc-fluid'),
                              'render'  => 'radio',
                              'source'  => array('normal' => __('Site font size','tcc-fluid'),
                                                 'fa-lg'  => __('33% larger than font size','tcc-fluid'),
                                                 'fa-2x'  => __('2 * font size','tcc-fluid'),
                                                 'fa-3x'  => __('3 * font size','tcc-fluid'),
                                                 'fa-4x'  => __('4 * font size','tcc-fluid'),
                                                 'fa-5x'  => __('5 * font size','tcc-fluid')),
                              'divcss'  => 'social-option-icon'
);
    $layout['prote']  = array('label'   => __('Social Site URL','tcc-fluid'),
                              'text'    => __('Be sure to add the protocol (ie: http:// or https://).','tcc-fluid'),
                              'render'  => 'display',
                              'divcss'  => 'social-option-icon');
    $icons = array('Behance'   => 'blue',    'Bitbucket'   => '#205081', 'Facebook' => '#3B5998',
                   'GitHub'    => 'black',   'Google Plus' => '#D74D2F', 'LinkedIN' => '#287BBC',
                   'Pinterest' => 'red',     'RSS'         => '#F67F00',
# 'Steam'    => 'black',
                   'Tumblr'    => 'black',   'Twitter'     => '#0084B4', 'Xing'     => 'black',
                   'YouTube'  => 'red');
    foreach($icons as $icon=>$color) {
      $key = sanitize_title($icon);
      $layout[$key] = array('default' => ($icon==='RSS') ? site_url('/feed/') : '',
                            'label'   => $icon,
                            'color'   => $color,
                            'help'    => sprintf(__('Default color is %s','tcc-fluid'),$color),
                            'place'   => sprintf("%s %s",$icon,__('site url','tcc-fluid')),
                            'render'  => 'text_color',
                            'divcss'  => 'social-option-icon');
    }
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }


}
