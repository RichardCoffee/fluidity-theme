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
		$main_css = "navbar navbar-fluidity navbar-$menu navbar-$page navbar-{$page}-$menu";
		$button_attrs = array(
			'type'  => 'button',
			'class' => 'navbar-toggle',
			'aria-controls' => $menu,
			'aria-expanded' => 'false',
			'data-toggle'   => 'collapse',
			'data-target'   => ".navbar-$menu-collapse",
		); ?>
		<nav id="<?php echo $main_id; ?>" class="<?php echo $main_css; ?>" <?php microdata()->SiteNavigationElement(); ?> role="navigation">
			<div class="navbar-header">
				<button <?php fluid_library()->apply_attrs( $button_attrs ); ?>>
					<span class="sr-only">Toggle navigation</span>
					<?php library()->fawe( 'fa-bars' ); ?>
				</button>
				<a class="navbar-brand " href="<?php echo esc_url( home_url() )?>"><?php bloginfo('name')?></a>
			</div>
			<div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php
				// FIXME: add filter for wp_nav_menu($args)
#				wp_nav_menu( array('menu'=>$menu,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new TCC_NavWalker_Bootstrap(), 'fallback_cb' => '' ) );
				require_once( FLUIDITY_HOME . 'vendor/wp-bootstrap-navwalker.php');
				wp_nav_menu( array(
					'menu'           => $menu,
					'theme_location' => $menu,
					'depth'          => 3,
					'container'      => false,
					'menu_class'     => 'nav navbar-nav',
					'walker'         => new TCC_NavWalker_Bootstrap(),
					'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback'
				) ); ?>
			</div>
		</nav><?php
	} else {
		/*	underscore navigation */
		$main_css = "main-navigation {$menu}-navigation {$page}-{$menu}-navigation";
		$button_attrs = array(
			'class' => 'menu-toggle',
			'aria-controls' => $menu,
			'aria-expanded' => 'false',
		); ?>
		<nav id="site-navigation" class="<?php echo $main_css; ?>" <?php microdata()->SiteNavigationElement(); ?> role="navigation">
			<button <?php fluid_library()->apply_attrs( $button_attrs ); ?>>
				<span class="sr-only">Toggle navigation</span>
				<?php library()->fawe( 'fa-bars' ); ?>
			</button>
			<?php wp_nav_menu( array( 'theme_location' => $menu, 'menu_id' => 'primary-menu', 'fallback_cb' => '' ) ); ?>
		</nav><!-- #site-navigation --><?php
	}
}
