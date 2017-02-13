<?php

class TCC_Widget_Widget extends WP_Widget {

	protected $title = '';
	protected $desc  = '';
	protected $slug  = '';
	protected static $micro = null;

	function __construct($slug='',$title='',$desc=array()) {
		parent::__construct($this->slug,$this->title,array('description'=>$this->desc));
		if (!self::$micro && class_exists('TCC_Microdata')) { self::$micro = microdata(); }
	}

	public function widget($args,$instance) {
		$title = apply_filters('widget_title',$instance['title'],$this->id_base);
		echo $args['before_widget'];
		$this->tcc_widget_title($args);
		$this->inner_widget($args,$instance);
		echo $args['after_widget'];
	}

	protected function tcc_widget_title($args) {
		if (strpos($args['after_title'],'panel-body')) {  #  FIXME
			echo '<div>';
		} else {
			echo $args['before_title'] . $title . $args['after_title'];
		}
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
		$html = "<p><label for='".$this->get_field_id($slug)."'>";
		$html.= esc_html($text)."</label>";
		$html.= "<input type='text' class='widefat'";
		$html.= " id='"   .$this->get_field_id($slug)  ."'";
		$html.= " name='" .$this->get_field_name($slug)."'";
		$html.= " value='$valu'";
		$html.= " /></p>";
		echo $html;
	}

	protected function form_checkbox($instance,$slug,$text) {
		$valu = (empty($instance[$slug])) ? 'off' : $instance[$slug];
		$html = "<p><label>";
		$html.= "<input type='checkbox'";
		$html.= " id='".$this->get_field_id($slug)  ."'";
		$html.= " name='" .$this->get_field_name($slug)."'";
		$html.= checked($valu, 'on', false);
		$html.= "/> <span> $text</span></label>";
		$html.= "</label></p>";
		echo $html;
	}

	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = (!empty($new['title'])) ? strip_tags($new['title']) : '';
		return $instance;
	}

}
