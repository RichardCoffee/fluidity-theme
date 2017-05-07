<?php

class TCC_Register_Sidebars {


	protected $sidebars = array();
	protected $title;
	protected $widget;


	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register_sidebars' );
	}

	public function register_sidebars() {
		remove_action( 'widgets_init', array( $this, 'register_sidebars' );
		$title  = $this->title_html();
		$widget = $this->widget_html();
		$sidebars = apply_filters( 'tcc_register_sidebars', $this->sidebars );
		foreach( $sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

	protected function title_html() {
		$title  = new stdClass;
		$status = tcc_layout( 'widget', 'perm' ); // FIXME: get layout default value
		$icon   = tcc_layout( 'widget_icon', 'default' ); // this value is fine


		$before_title  = '<div class="panel-heading"';
		$before_title .= ( $widget === 'perm' )   ? '' : ' role="button"';
		$before_title .= ( $widget === 'closed' ) ? ' data-collapse="1">' : '>';


		$fa_sign       = ( $widget === 'open' )   ? 'fa-minus' : 'fa-plus';


		$before_title .= ( $widget === 'perm' )   ? '' : fluid_library()->get_fawe( "$fa_sign pull-right panel-sign" );



		$before_css    = ( $widget === 'perm' )   ? '' : 'text-center scroll-this pointer';
		$before_title .= "<div class='panel-title $before_css'><b>";
		$after_title   = '</b></div></div><div class="panel-body">';



	}

	protected function widget_html() {
		$widget = new stdClass;
		$widget->before = '<div class="panel panel-fluidity">';
		#	Second /div closes "panel-body" div
		$widget->after  = '</div></div>';
		return apply_filters( 'tcc_register_sidebar_widget', $widget );
	}


}
