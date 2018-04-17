<?php

class TCC_Options_Content extends TCC_Options_Options {

	protected $base     = 'content';
	protected $priority = 90;

	protected function form_title() {
		return __( 'Content', 'tcc-fluid' );
	}

	public function describe_options() {
		_e( "Utilize these options to change the way content is displayed.  These options may one day show up in the WordPress Customizer.", 'tcc-fluid' );
	}

	protected function options_layout() {
		$layout = array( 'default' => true );
		$layout['postdate'] = array(
			'default'  => 'original',
			'label'    => __( 'Post Edit Date', 'tcc-fluid' ),
			'render'   => 'radio',
			'source'   => array(
				'both'     => __( 'Show both modified and original post date when showing full post content', 'tcc-fluid' ),
				'modified' => __( 'Use modified post date, where applicable.', 'tcc-fluid' ),
				'original' => __( 'Always use published post date.', 'tcc-fluid' ),
				'none'     => __( 'Never show the post date.', 'tcc-fluid' ),
			),
		);
		$layout['content'] = array(
			'default' => 'excerpt',
			'label'   => __( 'Blog/News/Search', 'tcc-fluid' ),
			'text'    => __( 'Show full post content or just an excerpt on archive/category/search pages', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'content' => __( 'Content', 'tcc-fluid' ),
				'excerpt' => __( 'Excerpt', 'tcc-fluid' ),
			),
		);
		$layout['exdate'] = array(
			'default' => 'show',
			'label'   => __( 'Excerpt Date', 'tcc-fluid' ),
			'text'    => __( 'Should the post date be displayed with excerpt?', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'none' => __( 'Do not show date', 'tcc-fluid' ),
				'show' => __( 'Always show date', 'tcc-fluid' ),
			),
		);
		$layout['exlength'] = array(
			'default' => apply_filters( 'excerpt_length', 55 ),
			'label'   => __( 'Excerpt Length', 'tcc-fluid' ),
			'render'  => 'spinner',
			'stext'   => __( 'number of words', 'tcc-fluid' ),
		);
		$layout = apply_filters( "tcc_{$this->base}_options_layout", $layout );
		return $layout;
	}

	protected function customizer_data() {
		$data = array(
			array(
			),
		);
		return apply_filters( "fluid_{$this->base}_customizer_data", $data );
	}


}
