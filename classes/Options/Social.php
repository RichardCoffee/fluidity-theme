<?php


class TCC_Options_Social extends TCC_Options_Options {


	protected $base     = 'social';
	protected $priority = 100;


	protected function form_title() {
		return __( 'Social Icons', 'tcc-fluid' );
	}

	public function describe_options() {
		$text = esc_html_x( 'Fluidity Social Icons (powered by %sFont Awesome%s)', 'html anchor/link tag', 'tcc-fluid' );
		$desc = sprintf( $text, '<a href="http://fontawesome.io/" rel="nofollow noopener" target="fontawesome.io">', '</a>' );
		echo wp_kses( $desc, [ 'a' => [ 'href' => [ ], 'rel' => [ ], 'target' => [ ] ] ] );
	}

	public function options_layout() {
		$layout = array(
			'default' => true,
		);
		$layout['active'] = array(
			'default' => 'no',
			'label'   => __( 'Use Theme Icons', 'tcc-fluid' ),
			'help'    => __( 'Contact us if you need help with a third-party plugin', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'yes' => __( "Yes - you want to use the theme's internal social icons", 'tcc-fluid' ),
				'no'  => __( "No -- you are using a plugin, or do not want social icons", 'tcc-fluid' ),
			),
			'showhide' => array(
				'origin' => 'social-option-active',
				'target' => 'social-option-icon',
				'show'   => 'yes',
			),
			'change'  => 'showhidePosi(this,".social-option-icon","yes");',
			'divcss'  => 'social-option-active',
		);
		$layout['target'] = array(
			'default' => 'target',
			'label'   => __( 'Browser', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'target'  => __( 'Open in new tab (default)', 'tcc-fluid' ),
				'replace' => __( 'Load in same tab', 'tcc-fluid'),
			),
			'divcss'  => 'social-option-icon',
		);
		$layout['size'] = array(
			'default' => 'normal',
			'label'   => __( 'Icon Size', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'normal' => __( 'Site font size', 'tcc-fluid' ),
				'fa-lg'  => __( '33% larger than font size', 'tcc-fluid' ),
				'fa-2x'  => __( '2 * font size', 'tcc-fluid' ),
				'fa-3x'  => __( '3 * font size', 'tcc-fluid' ),
				'fa-4x'  => __( '4 * font size', 'tcc-fluid' ),
				'fa-5x'  => __( '5 * font size', 'tcc-fluid' ),
			),
			'divcss'  => 'social-option-icon',
		);
		$layout['prote'] = array(
			'label'  => __( 'Social Site URL', 'tcc-fluid' ),
			'text'   => __( 'Be sure to add the protocol (ie: http:// or https://).', 'tcc-fluid' ),
			'render' => 'display',
			'divcss' => 'social-option-icon',
		);
		$icons = array(
			'Behance'     => 'blue',
			'Bitbucket'   => '#205081',
#			'Dribbble'    => '#ea4c89',
			'Facebook'    => '#3B5998',
			'GitHub'      => 'black',
			'Google Plus' => '#DC4E41', # '#D74D2F',
			'LinkedIN'    => '#0077B5', # '#287BBC',
			'Pinterest'   => '#BD081C',
			'RSS'         => '#F67F00',
#			'Steam'       => 'black',
			'Tumblr'      => 'black',
			'Twitter'     => '#55ACEE', # '#0084B4',
			'Xing'        => 'black',
			'YouTube'     => 'red',
		);
		foreach( $icons as $icon => $color ) {
			$key = sanitize_title( $icon );
			$layout[ $key ] = array(
				'default' => ( $icon === 'RSS' ) ? site_url( '/feed/' ) : '',
				'label'   => $icon,
				'color'   => $color,
				'help'    => sprintf( __( 'Default color is %s', 'tcc-fluid' ), $color ),
				'place'   => sprintf( "%s %s", $icon, __( 'site url','tcc-fluid' ) ),
				'render'  => 'text_color',
				'divcss'  => 'social-option-icon',
			);
		}
		return apply_filters("tcc_{$this->base}_options_layout", $layout );
	}

	protected function customizer_data() {
		$data = array(
			array(
			),
		);
		return apply_filters( "fluid_{$this->base}_customizer_data", $data );
	}


}
