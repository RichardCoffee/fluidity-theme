<?php

if (!function_exists('tcc_register_widgets')) {
	function tcc_register_widgets() {
		$widgets = apply_filters( 'tcc_register_widgets_list', array('Address','Login','Logo','Search') );
		foreach($widgets as $widget) {
			register_widget("TCC_Widget_$widget");
		}
	}
	add_action('widgets_init','tcc_register_widgets');
}

if ( ! function_exists( 'fluid_show_address' ) ) {
	function fluid_show_address( $info ) { ?>
		<address <?php microdata()->PostalAddress(); ?>><?php
			if ( ! empty( $info['street'] ) ) {
				echo wp_kses( microdata()->street( $info['street'] ), fluid()->kses() ); ?>
				<br><?php
			} ?>
			<span class="comma-after" itemprop="addressLocality"><?php
				e_esc_html( $info['local'] ); ?>
			</span>&nbsp;
			<span itemprop="addressRegion"><?php
				e_esc_html( $info['region'] );
				if ( ! empty( $info['code'] ) ) { ?>
					</span>&nbsp;
					<span itemprop="postalCode"><?php
						e_esc_html( $info['code'] );
				} ?>
			</span>
		</address><?php
	}
}
