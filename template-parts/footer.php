<?php
/*
 *  File Name:  template-parts/footer.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="ribbon"></div>

<div class="row footer">

	<?php who_am_i(); ?>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php microdata()->WPSideBar(); ?>><?php
		$footer_template_dir  = apply_filters( 'fluid_footer_template_dir', 'sidebar' );
		$footer_template_file = apply_filters( 'fluid_footer_template_file', 'footer' );
		get_template_part( $footer_template_dir, $footer_template_file ); ?>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
		<?php tcc_copyright(); ?>
	</div>

</div><!-- .footer -->
