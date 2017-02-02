<?php


class TCC_Options_Privacy {

  private $base = 'privacy';

  public function __construct() {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),100);
  }

  public function form_layout($form) {
    $form[$this->base] = array('describe' => array($this,'describe_social'),
                               'title'    => __('Privacy','tcc-fluid'),
                               'option'   => 'tcc_options_social',
                               'layout'   => $this->privacy_layout());
    return $form;
  }

  public function describe_social() {
    _e('Control the information that WordPress gets from your site.  The default settings here duplicate what WordPress currently collects.','tcc-fluid');
  }

  public function privacy_layout() {
    $layout = array('default'=>true);
    $layout['blog']  = array('default' => 'yes',
                             'label'   => __('Blog URL','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('yes' => __("Let WordPress know your site's url.",'tcc-fluid'),
                                                'no'  => __('Do not let them know where you are.','tcc-fluid')));
    $layout['site']  = array('default' => 'yes',
                             'label'   => __('Install URL','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('yes' => __("Let WordPress know the url you installed WordPress to.",'tcc-fluid'),
                                                'no'  => __('Do not give WordPress this information.','tcc-fluid')));
    $layout['blogs'] = array('default' => 'yes',
                             'label'   => __('Multi-Site','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('yes' => __("Yes - Let WordPress know if you are running a multi-site blog.",'tcc-fluid'),
                                                'no'  => __("No -- Tell WordPress you are running just a simple blog. (recommended)",'tcc-fluid')));
    $layout['users'] = array('default' => 'all',
                             'label'   => __('Users','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('all'  => __('Accurately report to WordPress how manu users you have.','tcc-fluid'),
                                                'some' => __('Only let WordPress know you have some users ( actual number divided by 10 )','tcc-fluid'),
                                                'one'  => __('Tell WordPress that you are the only user. (recommended)','tcc-fluid'),
                                                'many' => __('Just generate some random number to give WordPress','tcc-fluid')));
  $layout['plugins'] = array('default' => 'all',
                             'label'   => __('Plugins','tcc-fluid'),
                             'render'  => 'radio',
                             'source'  => array('all'    => __("Let WordPress know what plugins you have installed.",'tcc-fluid'),
                                                'filter' => __('Filter the list that gets sent to WordPress.  (recommended)','tcc-fluid'),
                                                'none'   => __('Do not let them know where you are.','tcc-fluid')));


$plugins = wp_get_installed_translations( 'plugins' );
$themes  = wp_get_installed_translations( 'themes' );
log_entry($plugins,$themes);

/*
$layout['plugin_list']=array('default' => array(),
                             'label'   => __('Plugin List','tcc-fluid'),
                             'render'  => 'radio_multi',
                             'source'  => //*/



    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }


}
