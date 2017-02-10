<?php  /* Name: Menu Default */

/*
 *  File Name:  template-parts/menu.php
 *
 */

$page = get_page_slug();
$menu = apply_filters( 'tcc_menu', 'primary', $page );	#	defaults: primary, header or footer
if (has_nav_menu($menu)) {
#	$main_css = get_menu_class($menu,$page);
	if (tcc_layout('menu')==='bootstrap') {
		/* bootstrap navigation */
		$main_id  = "navbar-{$page}-$menu";
		$main_css = "navbar navbar-fluidity navbar-$menu navbar-$page navbar-{$page}-$menu"; ?>
		<nav id="<?php echo $main_id; ?>" class="<?php echo $main_css; ?>" <?php microdata()->SiteNavigationElement(); ?> role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-<?php echo $menu; ?>-collapse">
					<span class="sr-only">Toggle navigation</span>
					<i class="fa fa-bars"> </i>
				</button>
				<a class="navbar-brand " href="<?php echo esc_url( home_url() )?>"><?php bloginfo('name')?></a>
			</div>
			<div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php
				// FIXME: add filter for wp_nav_menu($args)
				wp_nav_menu( array('menu'=>$menu,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new TCC_NavWalker_Bootstrap(), 'fallback_cb' => '' ) ); ?>
			</div>
		</nav><?php
	} else {
		/*	underscore navigation */
		$main_css = "main-navigation {$menu}-navigation {$page}-{$menu}-navigation"; ?>
		<nav id="site-navigation" class="<?php echo $main_css; ?>" <?php microdata()->SiteNavigationElement(); ?> role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<i class="fa fa-bars"> </i>
				<?php #esc_html_e( 'Primary Menu', 'tcc_fluid' ); ?>
			</button>
			<?php wp_nav_menu( array( 'theme_location' => $menu, 'menu_id' => 'primary-menu', 'fallback_cb' => '' ) ); ?>
		</nav><!-- #site-navigation --><?php
	}
}
