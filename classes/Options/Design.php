<?php

class TCC_Options_Design {

  private $base     = 'design';
  private $priority = 34; # customizer priority

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

  public function describe_options() { ?>
		<span title="No they don't.  This doesn't work.  yet">
			<?php esc_html_e('Design Options - these options also show up in the WordPress Customizer.','tcc-fluid'); ?>
		</span><?php
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
    $layout['paral']  = array('default' => 'no',
                              'label'   => __('Parallax','tcc-fluid'),
                              'text'    => __('Do you want to use different background images on displayed pages?','tcc-fluid'),
                              'help'    => "No idea why this is called 'parallax', but it sounds very scientific.",
                              'render'  => 'radio',
                              'source'  => array('no'  => __('Do not use image backgrounds','tcc-fluid'),
                                                 'yes' => __('Use parallax backgrounds','tcc-fluid')));
    $layout['type']   = array('label'   => __('Typography','tcc-fluid'),
                              'text'    => __('Site typography options','tcc-fluid'),
                              'render'  => 'title');
    $layout['font']   = array('default' => 'Helvitica Neue',
                              'label'   => __('Font Type','tcc-fluid'),
                              'render'  => 'font',
                              'source'  => TCC_Options_Typography::mixed_fonts());
    $layout['size']   = array('default' => 18,
                              'label'   => __('Font Size','tcc-fluid'),
                              'stext'   => _x('px',"abbreviation for 'pixel'",'tcc-fluid'),
                              'render'  => 'text',
                              'divcss'  => 'tcc_text_3em');
/*    $layout['header'] = array('default' => '',
                              'label'   => __('Header Background','tcc-fluid'),
                              'render'  => 'image',
                              'divcss'  => 'upload-img',
                              'media'   => array('title'  => __('Assign/Upload Header Background Image','tcc-fluid'),
                                                 'button' => __('Assign Background', 'tcc-fluid'),
                                                 'delete' => __('Remove Background', 'tcc-fluid')));//*/
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

  public function options_customize_register($wp_customize, TCC_Options_Fluidity $form) {
    $wp_customize->add_section( 'fluid_'.$this->base, array('title' => $this->form_title(), 'priority' => $this->priority));
    $form->customizer_settings($wp_customize,$this->base);
  }


}
