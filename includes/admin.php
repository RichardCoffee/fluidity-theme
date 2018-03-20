<?php

if ( ! function_exists( 'tcc_custom_css_admin' ) ) {
log_entry('tcc_custom_css_admin');
	function tcc_custom_css_admin() {
log_entry('admin_head');
 ?>
		<style id="tcc-custom-css-admin" type="text/css">
			<?php do_action('tcc_custom_css_admin'); ?>
		</style><?php
	}
	add_action( 'admin_head', 'tcc_custom_css_admin' );
}
