<?php

class TCC_Basic_Widget extends WP_Widget {

  protected $title = '';
  protected $desc  = '';
  protected $slug  = '';
  protected static $micro = null;

  function __construct($slug='',$title='',$desc=array()) {
    parent::__construct($this->slug,$this->title,array('description'=>$this->desc));
    if (!self::$micro && class_exists('TCC_Microdata')) self::$micro = TCC_Microdata::get_instance();
  }

  public function widget($args,$instance) {
    #$args['tcc-title'] = apply_filters('widget_title',$instance['title'],$this->id_base);
    $title = apply_filters('widget_title',$instance['title'],$this->id_base);
    echo $args['before_widget'];
    if (!empty($title)) {
      echo $args['before_title'].$title.$args['after_title'];
    } else {
      if (!empty($args['before_title'])) echo "<div>";
    }
    $this->inner_widget($args,$instance);
    echo $args['after_widget'];
  }

  public function form($instance) {
    $this->form_title($instance);
  }

	protected function form_title($instance) {
		$instance['title'] = (isset($instance['title'])) ? $instance['title'] : $this->title;
		$text = esc_html__('Title:','tcc-fluid');
		$this->form_field($instance, 'title', $text);
	}

	protected function form_field($instance,$slug,$text) {
		$valu = (empty($instance[$slug])) ? '' : esc_attr($instance[$slug]);
		$form = "<p><label for='".$this->get_field_id($slug)."'>";
		$form.= esc_html($text)."</label>";
		$form.= "<input type='text' class='widefat'";
		$form.= " id='"   .$this->get_field_id($slug)  ."'";
		$form.= " name='" .$this->get_field_name($slug)."'";
		$form.= " value='$valu'";
		$form.= " /></p>";
		echo $form;
	}

  public function update($new,$old) {
    $instance = $old;
    $instance['title'] = (!empty($new['title'])) ? strip_tags($new['title']) : '';
    return $instance;
  }

}

class TCC_Address_Widget extends TCC_Basic_Widget {

	private $address;

	function __construct() {
		$this->title = esc_html__('Address','tcc-fluid');
		$this->desc  = esc_html__('Fluidity address widget','tcc-fluid');
		$this->slug  = 'tcc_address';
		$this->address = array('tcc-street' => __('Street Address','tcc-fluid'),
							'tcc-local'  => __('City','tcc-fluid'),
							'tcc-region' => __('State','tcc-fluid'),
							'tcc-code'   => __('Zipcode','tcc-fluid'),
							'tcc-phone'  => __('Contact NumberStreet Address','tcc-fluid'),
						);
		parent::__construct();
	}

	public function inner_widget($args,$instance) { ?>
		<div <?php self::$micro->Organization(); ?>>
			<h4 class="text-center" itemprop="name"><?php bloginfo('name'); ?></h4>
			<address class="text-center" <?php self::$micro->PostalAddress(); ?>>
				<span itemprop="streetAddress">
					<?php echo esc_html($instance['tcc-street']); ?>
				</span><br>
				<span itemprop="addressLocality">
					<?php echo esc_html($instance['tcc-local']); ?>
				</span> <span itemprop="addressRegion">
					<?php echo esc_html($instance['tcc-region']); ?>
				</span>, <span itemprop="postalCode">
					<?php echo esc_html($instance['tcc-code']); ?>
				</span><br><?php
				if (!empty($instance['tcc-phone'])) {
					esc_html_e('Office: ','tcc-fluid'); ?> <span itemprop="telephone">
					<?php echo esc_html($instance['tcc-phone']); ?>
					</span><br><?php
				}
				esc_html_e('Email: ','tcc-fluid'); ?>
				<a href="mailto:<?php echo get_option('admin_email'); ?>">
					<?php bloginfo ('title');?>
				</a>
			</address>
		</div><?php
	}

	public function form($instance) {
		parent::form($instance);
		foreach($this->address as $slug=>$text) {
			$this->form_field($instance, $slug, $text);
		}
	}

	public function update($new,$old) {
		$instance = parent::update($new,$old);
		foreach($this->address as $slug=>$text) {
			$instance[$slug] = (!empty($new[$slug])) ? strip_tags($new[$slug]) : '';
		}
		return $instance;
	}

}

class TCC_Login_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = esc_html__('Login','tcc-fluid');
    $this->desc  = esc_html__('Fluidity Login form','');
    $this->slug  = 'tcc_login';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    tcc_login_form();
  }

}

class TCC_Logo_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = esc_html__('Logo','tcc-fluid');
    $this->desc  = esc_html__('Fluidity - Displays your site logo','tcc-fluid');
    $this->slug  = 'tcc_logo';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    $logo = tcc_design('logo'); ?>
    <a href="<?php self::$micro->bloginfo('url'); ?>/">
      <img itemprop="logo" class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
    </a><?php
  }

}

class TCC_Search_Widget extends TCC_Basic_Widget {

  function __construct() {
    $this->title = esc_html__('Search','tcc-fluid');
    $this->desc  = esc_html__('Fluidity Search Form','tcc-fluid');
    $this->slug  = 'tcc_search';
    parent::__construct();
    unregister_widget('WP_Widget_Search');
  }

  public function inner_widget($args,$instance) {
    get_search_form();
  }

}

do_action('tcc_widget_class_loaded');

function tcc_register_widgets() {
  register_widget('TCC_Address_Widget');
  register_widget('TCC_Login_Widget');
  register_widget('TCC_Logo_Widget');
  register_widget('TCC_Search_Widget');
  do_action('tcc_register_widgets');
}
add_action('widgets_init','tcc_register_widgets'); //*/
