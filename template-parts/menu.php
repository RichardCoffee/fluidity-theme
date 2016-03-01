<?php  /* Name: Menu Default */

/*
 *  File Name:  template-parts/menu.php
 *
 */

if (has_nav_menu('primary')) {
  $micro = microdata();
  $color_scheme = tcc_color_scheme(); ?>
  <div>
    <nav class="navbar navbar-<?php echo $color_scheme; ?>" <?php $micro->SiteNavigationElement(); ?> role="navigation">
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
        wp_nav_menu( array('menu'=>'primary','depth'=>2,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new wp_bootstrap_navwalker())); ?>
      </div>
    </nav>
  </div><?php
}
