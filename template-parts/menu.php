<?php  /* Name: Menu Default */

/*
 *  File Name:  template-parts/menu.php
 *
 */

$menu = 'primary';	#	primary, header or footer
$page = fluidity_page_slug();
if (has_nav_menu($menu)) { /* ?>
  <nav id="navbar-<?php echo $page.'-'.$menu; ?>" class="navbar navbar-fluidity" <?php microdata()->SiteNavigationElement(); ?> role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-<?php echo $menu; ?>-collapse">
        <span class="sr-only">Toggle navigation</span>
        <i class="fa fa-bars"> </i>
      </button>
      <a class="navbar-brand " href="<?php echo esc_url( home_url() )?>"><?php bloginfo('name')?></a>
    </div>
    <div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php /* Primary navigation * /
		// FIXME: add filter for wp_nav_menu($args)
      wp_nav_menu( array('menu'=>$menu,'depth'=>2,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new wp_bootstrap_navwalker())); ?>
    </div>
  </nav><?php */
	/*	underscore navigation */ ?>
	<nav id="site-navigation" class="main-navigation" role="navigation">
		<div class="pull-right">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', '_s' ); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
		</div>
	</nav><!-- #site-navigation -->
<?php
}
