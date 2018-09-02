<?php
/*
 *  File Name:  template-parts/footer.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="row footer">

	<?php who_am_i(); ?>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-sidebar" <?php microdata()->WPSideBar(); ?>><?php
		$dir  = apply_filters( 'fluid_footer_sidebar_dir', 'template-parts' );
		$file = apply_filters( 'fluid_footer_sidebar_file', 'sidebar' );
		$ext  = apply_filters( 'fluid_footer_sidebar_ext', 'footer' );
		get_template_part( "$dir/$file", $ext ); ?>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-copyright">
		<?php tcc_copyright(); ?>
	</div>

</div><!-- .footer -->
