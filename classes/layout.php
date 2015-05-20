<?php

class Fluid_Layout {

  private static $instance = null;
  public $clearfix      = 'lg=4&md=4&sm=6&xs=12';
  public $current_page  = '';
  public $inner_class   = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
  public $microdata     = null;
  public $primary_class = 'col-lg-8 col-md-8 col-sm-12 col-xs-12';
  public $sidebar_class = 'col-lg-4 col-md-4 hidden-sm hidden-xs';
  public $sidebar_name  = 'standard';
  public $sidebar_side  = 'right';

  public function __construct($args=array()) {
    if (self::$instance) return self::$instance;
    $this->microdata = TCC_Microdata::get_instance();
    foreach($args as $key=>$value) {
      if (property_exists($this,$key))
        $this->$key = $value;
    }
    $this->determine_sidebar();
    self::$instance = $this;
    return $this;
  }

  public static function get_instance($page='') {
    if (self::$instance===null) {
      self::$instance = new Fluid_Layout($page);
    }
    return self::$instance;
  }

  private function determine_sidebar() {
    // FIXME:  this needs to be coordinated in register_fluid_sidebars() in sidebars.php
    $known = array('standard','front','three_column','footer');
    $known = apply_filters('fluidity_known_sidebars',$known);
    if ((empty($this->sidebar_name)) || (!in_array($this->sidebar_name,$known))) {
      if (is_front()) {
        $this->sidebar_name = 'front';
      } else if (is_search()) {
        $this->sidebar_name = 'search';
      } else if (is_archive()) {
        $this->sidebar_name = 'archive';
      } else {
        $this->sidebar_name = 'standard';
      }
    }
  }

  public function get_sidebar() {
    if (is_active_sidebar($this->sidebar_name)) {
      $this->sidebar_class.= ($this->sidebar_side=='right') ? ' pull-right' : ''; ?>
      <div class="<?php echo $this->sidebar_class; ?>" <?php $this->microdata->WPSideBar(); ?>><?php
        fluidity_get_sidebar($this->sidebar_name); ?>
      </div><?php
    } else {
      $this->primary_class = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
      $this->inner_class   = "col-lg-3  col-md-3  col-sm-6  col-xs-12";
      $this->clearfix      = "lg=3&md=3&sm=6&xs=12";
    }
  }

}
