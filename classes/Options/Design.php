<?php

class TCC_Options_Design extends TCC_Options_Options {


	protected $base     = 'design';
	protected $priority = 80;


  protected function form_title() {
    return __('Design','tcc-fluid');
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
		$layout['title'] = array(
			'default' => 'no',
			'label'   => __( 'Page Title', 'tcc-fluid' ),
			'text'    => __( 'Do you want to show the page title before the content?', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'no'   => __( 'Do not show the page title.', 'tcc-fluid' ),
				'page' => __( 'Show the page title after the header and before the content/sidebar.', 'tcc-fluid' ),
			)
		);
		if ( ! ( tcc_layout( 'sidebar' ) === 'none' ) ) {
			$layout['title']['source']['main'] = __( 'Over content area only, when showing sidebar.', 'tcc-fluid' );
		}
		$layout['type'] = array(
			'label'   => __( 'Typography', 'tcc-fluid' ),
			'text'    => __( 'Site typography options', 'tcc-fluid' ),
			'render'  => 'title',
		);
		$layout['font'] = array(
			'default' => 'Helvitica Neue',
			'label'   => __( 'Font Type', 'tcc-fluid' ),
			'render'  => 'font',
			'source'  => TCC_Options_Typography::mixed_fonts(),
		);
		$layout['size'] = array(
			'default' => 18,
			'label'   => __('Font Size','tcc-fluid'),
			'stext'   => _x( 'px', "abbreviation for 'pixel' - not sure this even needs translating...", 'tcc-fluid' ),
			'render'  => 'text',
			'divcss'  => 'tcc_text_3em',
		);
		if ( is_plugin_active( 'bbpress/bbpress.php' ) ) {
			$layout['bbpsize'] = array(
				'default' => 12,
				'label'   => __('bbPress Font Size','tcc-fluid'),
				'text'    => __('Control the main font size on forum pages', 'tcc-fluid' ),
				'stext'   => _x( 'px', "abbreviation for 'pixel' - not sure this even needs translating...", 'tcc-fluid' ),
				'render'  => 'text',
				'divcss'  => 'tcc_text_3em',
			);
			$layout['bbposize1'] = array(
				'default' => 11,
				'label'   => __('bbPress Font Size','tcc-fluid'),
				'text'    => __('Control the other font size on forum pages', 'tcc-fluid' ),
				'stext'   => _x( 'px', "abbreviation for 'pixel' - not sure this even needs translating...", 'tcc-fluid' ),
				'render'  => 'text',
				'divcss'  => 'tcc_text_3em',
			);
		}
		return apply_filters( "tcc_{$this->base}_options_layout", $layout );
	}


}
