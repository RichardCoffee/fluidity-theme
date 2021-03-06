<?php

class TCC_Options_Images {

  private $base     = 'images';
#  private $priority = 34; # customizer priority

  public function __construct() {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),10);
    add_action('fluid-customizer', array($this,'options_customize_register'),$this->priority,2);
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
    esc_html_e('Design Options - these options also show up in the WordPress Customizer.','tcc-fluid');
  }

  protected function options_layout() {
    $layout = array('default'=>true);
    $layout['logo']   = array('default' => '',
                              'label'   => __('Theme Logo','tcc-fluid'),
                              'render'  => 'image',
                              'divcss'  => 'upload-img',
                              'media'   => array('title'  => __('Assign/Upload Theme Logo','tcc-fluid'),
                                                 'button' => __('Assign Logo', 'tcc-fluid'),
                                                 'delete' => __('Remove Logo', 'tcc-fluid')));
    $layout['lrepeat'] = array('default' => 'none',
                               'label'   => __('Background Repeat','tcc-fluid'),
                               'render'  => 'radio',
                               'source'  => array('none' => __('No Repeat','tcc-fluid'),
                                                  'tile' => __('Tile','tcc-fluid'),
                                                  'hori' => __('Tile Horizontally','tcc-fluid'),
                                                  'vert' => __('Tile Vertically','tcc-fluid')));
    $layout['lposi']   = array('default' => 'center',
                               'label'   => __('Background Position','tcc-fluid'),
                               'render'  => 'radio',
                               'source'  => array('left'   => __('Left','tcc-fluid'),
                                                  'center' => __('Center','tcc-fluid'),
                                                  'right'  => __('Right','tcc-fluid')));
    $layout['lrepeat'] = array('default' => 'fixed',
                               'label'   => __('Background Movement','tcc-fluid'),
                               'render'  => 'radio',
                               'source'  => array('scroll' => __('Scroll with page','tcc-fluid'),
                                                  'fixed'  => __('Fixed position in viewport','tcc-fluid')));
/*    $layout['header'] = array('default' => '',
                              'label'   => __('Header Background','tcc-fluid'),
                              'render'  => 'image',
                              'divcss'  => 'upload-img',
                              'media'   => array('title'  => __('Assign/Upload Header Background Image','tcc-fluid'),
                                                 'button' => __('Assign Background', 'tcc-fluid'),
                                                 'delete' => __('Remove Background', 'tcc-fluid')));//*/
    $layout['type']   = array('label'   => __('Typography','tcc-fluid'),
                              'text'    => __('Site typography options','tcc-fluid'),
                              'render'  => 'title');
    $layout['font']   = array('default' => 'Helvitica Neue',
                              'label'   => __('Font Type','tcc-fluid'),
                              'render'  => 'font',
                              'source'  => TCC_Typography::mixed_fonts());
    $layout['size']   = array('default' => 18,
                              'label'   => __('Font Size','tcc-fluid'),
                              'stext'   => _x('px',"abbreviation for 'pixel'",'tcc-fluid'),
                              'render'  => 'text',
                              'divcss'  => 'tcc_text_3em');
/*    $layout['back']   = array('label'   => __('Background','tcc-fluid'),
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
                              'render'  => 'display'); //*/
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }


}
