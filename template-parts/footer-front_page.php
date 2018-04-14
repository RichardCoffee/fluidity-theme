<?php
/*
 *  File Name:  template-parts/footer.php
 *
 */

define( 'ABSPATH' ) || exit; ?>

<div class="ribbon"></div>

<div class="row footer">

	<?php who_am_i(); ?>

  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php microdata()->WPSideBar(); ?>>
		<?php get_template_part('sidebar','footer2'); ?>
  </div>

  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
		<?php tcc_copyright(); ?>
  </div>

<?php /*
 ?>
      <br><?php
      $foot_menu = array();
      if (page_exists('terms'))      $foot_menu[] = array('terms',      esc_html__('Terms & Conditions','tcc-fluid'));
      if (page_exists('conditions')) $foot_menu[] = array('conditions', esc_html__('Terms & Conditions','tcc-fluid'));
      if (page_exists('privacy'))    $foot_menu[] = array('privacy',    esc_html__('Privacy Policy',    'tcc-fluid'));
      if (page_exists('security'))   $foot_menu[] = array('security',   esc_html__('Security Policy',   'tcc-fluid'));
      $foot_menu = apply_filters('tcc_bottom_menu',$foot_menu);
      if ($foot_menu) {
        $string = '<span '.microdata()->SiteNavigationElement().'>';
        foreach($foot_menu as $option) {
          $string.= "<a href='/{$option[0]}/'> {$option[1]} </a> | "; }
        echo substr($string,0,-3).'</span>';
      } //*/ ?>


</div><!-- .footer -->
