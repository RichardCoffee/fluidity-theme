<?php

abstract class TCC_Theme_BasicNav {


	protected $nav_css = 'posts-navigation';
	protected $sr_text = '';

	use TCC_Trait_Attributes;
	use TCC_Trait_ParseArgs;

	abstract protected function generate_links();


	public function __construct( $args = array() ) {
		$this->sr_text = __( 'Post navigation' ,' tcc-fluid' );
		$this->parse_args( $args );
	}

	protected function generate_navigation() {
		$links = $this->generate_links();
		$template = apply_filters( 'navigation_markup_template', null, $this->nav_css );
		if ( $template ) {
			$html = sprintf( $template, sanitize_html_class( $this->nav_css ), esc_html( $this->sr_text ), $links );
		} else {
			$template = $this->generate_markup();
			$html = sprintf( $template, $links );
		}
		echo $html;
	}

	protected function generate_markup() {
		$attrs = array(
			'class' => 'navigation no-print ' . $this->nav_css,
			'title' => $this->sr_text,
			'aria-label' => $this->sr_text,
			'role'  => 'navigation',
		);
		$html = $this->get_apply_attrs_tag( $attrs, 'nav' );
		$html.= '<div class="nav-links">%s</div>';
		$html.= '</nav>';
		return $html;
	}


}
