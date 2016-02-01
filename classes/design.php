<?php

new Design_Theme_Options;

class Design_Theme_Options {

  private $translated;

  public function __construct() {
    add_filter('tcc_options_form_layout', array($this,'form_layout'),4);
  }

  public function form_layout($form) {
    $form['design'] = array('describe' => array($this,'describe_design'),
                            'title'    => __('Design','tcc-fluid'),
                            'layout'   => $this->design_layout());
    return $form;
  }

  public function describe_design() {
    _e('Design Options','tcc-theme-options');
  }

  protected static function design_layout() {
    $layout = array();
    $layout['logo']   = array('default' => '',
                              'label'   => __('Theme Logo','fluid'),
                              'render'  => 'image',
                              'class'   => 'upload-img',
                              'media'   => array('title'  => __('Assign/Upload Theme Logo','tcc-fluid'),
                                                 'button' => __('Assign Logo',             'tcc-fluid')));
    $layout['type']   = array('label'   => __('Typography','tcc-fluid'),
                              'text'    => __('Site typography options','tcc-fluid'),
                              'render'  => 'title');
    $layout['font']   = array('default' => 'Helvitica Neue',
                              'label'   => __('Font Type','tcc-fluid'),
                              'render'  => 'font',
                              'source'  => TCC_Typography::mixed_fonts());
    $layout['size']   = array('default' => 14,
                              'label'   => __('Font Size','tcc-fluid'),
                              'text'    => _x('px',"abbreviation for 'pixel'",'tcc-fluid'),
                              'render'  => 'text',
                              'class'   => 'tcc_text_3em');
    $layout['back']   = array('label'   => __('Background','tcc-fluid'),
                              'text'    => __('Use these options to add/change the background images','tcc-fluid'),
                              'render'  => 'title');
    $layout['header'] = array('label'   => __('Header',      'tcc-fluid'),
                              'text'    => __('Coming Soon!','tcc-fluid'),
                              'render'  => 'display');
    $layout['footer'] = array('label'   => __('Footer',      'tcc-fluid'),
                              'text'    => __('Coming Soon!','tcc-fluid'),
                              'render'  => 'display');
    $layout['site']   = array('label'   => __('Site',        'tcc-fluid'),
                              'text'    => __('Coming Soon!','tcc-fluid'),
                              'render'  => 'display');
    return $layout;
  }
  
  
}
