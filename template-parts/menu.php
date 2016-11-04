<?php  /* Name: Menu Default */

/*
 *  File Name:  template-parts/menu.php
 *
 */

if (has_nav_menu('primary')) {
  $micro = microdata();
  $color = tcc_color_scheme(); ?>
  <nav class="navbar navbar-<?php echo $color; ?>" <?php $micro->SiteNavigationElement(); ?> role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand " href="<?php echo esc_url( home_url() )?>"><?php bloginfo('name')?></a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse"><?php /* Primary navigation */
      wp_nav_menu( (object) array('menu'=>'header','depth'=>2,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new wp_bootstrap_navwalker()));
      do_action('fluidity_menubar'); ?>
    </div>
  </nav><?php
}
