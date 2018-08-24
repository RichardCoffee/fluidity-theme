<?php

class TCC_Options_Design extends TCC_Options_Options {


	protected $base     = 'design';
	protected $priority = 80;


	protected function form_title() {
		return __( 'Design', 'tcc-fluid' );
	}

	protected function form_icon() {
		return 'dashicons-art';
	}

	public function describe_options() { ?>
		<span title="No they don't.  This doesn't work.  yet">
			<?php esc_html_e('Design Options - these options also show up in the WordPress Customizer.','tcc-fluid'); ?>
		</span><?php
	}

	protected function options_layout() {
		$layout = array(
			'default' => true
		);
		$layout['paral'] = array(
			'default' => 'no',
			'label'   => __( 'Parallax', 'tcc-fluid' ),
			'text'    => __( "Do you want to use the parallax effect for a page's featured image?", 'tcc-fluid' ),
			'help'    => "No idea why this is called parallax, but it sounds very scientific.",
			'render'  => 'radio',
			'source'  => array(
				'no'  => __( 'Show featured images normally - although currently this is not at all on pages', 'tcc-fluid' ),
				'yes' => __( 'Use featured image as background', 'tcc-fluid' ),
			)
		);
		return apply_filters( "tcc_{$this->base}_options_layout", $layout );
	}


}
