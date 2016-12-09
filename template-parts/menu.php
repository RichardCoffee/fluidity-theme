<?php  /* Name: Menu Default */

/*
 *  File Name:  template-parts/menu.php
 *
 */

$menu = 'primary';
$page = fluidity_page_slug();
if (has_nav_menu($menu)) {
  $color = tcc_color_scheme(); ?>
  <nav class="navbar navbar-<?php echo $color; ?> navbar-<?php echo $page; ?> visible-xs" <?php microdata()->SiteNavigationElement(); ?> role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-<?php echo $menu; ?>-collapse">
        <span class="sr-only">Toggle navigation</span>
        <i class="fa fa-bars"> </i>
      </button>
      <a class="navbar-brand " href="<?php echo esc_url( home_url() )?>"><?php bloginfo('name')?></a>
    </div>
    <div class="collapse navbar-collapse navbar-primary-collapse"><?php /* Primary navigation */
      wp_nav_menu( array('menu'=>'header','depth'=>2,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new wp_bootstrap_navwalker()));
      do_action('fluidity_menubar'); ?>
    </div>
  </nav><?php
}
