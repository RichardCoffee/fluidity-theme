<?php
/*
 *  File Name:  template-parts/footer.php
 *
 */

define( 'ABSPATH' ) || exit; ?>

<div class="ribbon"></div>

<div class="row footer">

	<?php who_am_i(); ?>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php microdata()->WPSideBar(); ?>><?php
		$footer_template_stem = apply_filters( 'tcc_footer_template_stem', 'sidebar' );
		$footer_template_part = apply_filters( 'tcc_footer_template_part', 'footer' );
		get_template_part( $footer_template_stem, $footer_template_part ); ?>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
		<?php tcc_copyright(); ?>
	</div>

</div><!-- .footer -->
