<?php  /* Name: Menu Default */

/*
 *  File Name:  template-parts/menu.php
 *
 */

$page = get_page_slug();
$menu = apply_filters( 'tcc_menu', 'primary', $page );	#	defaults: primary, header or footer
if ( has_nav_menu( $menu ) ) {
#	$main_css = get_menu_class($menu,$page);
	if ( tcc_layout( 'menu' ) === 'bootstrap' ) {
		/* bootstrap navigation */
		$nav_attrs = array(
			'id'    => "navbar-{$page}-$menu",
			'class' => "navbar navbar-fluidity navbar-$menu navbar-$page navbar-{$page}-$menu",
			'role'  => 'navigation',
		);
log_entry( microdata()->SiteNavigationElement( true ) );
		$nav_attrs = array_merge( $nav_attrs, microdata()->SiteNavigationElement( true ) );
		$button_attrs = array(
			'type'  => 'button',
			'class' => 'navbar-toggle',
			'aria-controls' => $menu,
			'aria-expanded' => 'false',
			'data-toggle'   => 'collapse',
			'data-target'   => ".navbar-$menu-collapse",
		);
		fluid_library()->apply_attrs_tag( $nav_attrs, 'nav' ); ?>
			<div class="navbar-header">
				<?php fluid_library()->apply_attrs_tag( $button_attrs, 'button' ); ?>
					<span class="sr-only">Toggle navigation</span>
					<?php library()->fawe( 'fa-bars' ); ?>
				</button>
				<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
			</div>
			<div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php
				// FIXME: add filter for wp_nav_menu($args)
#				wp_nav_menu( array('menu'=>$menu,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new TCC_NavWalker_Bootstrap(), 'fallback_cb' => '' ) );
				require_once( FLUIDITY_HOME . 'vendor/wp-bootstrap-navwalker.php' );
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
		$nav_attrs = array(
			'id'    => 'site-navigation',
			'class' => "main-navigation {$menu}-navigation {$page}-{$menu}-navigation",
			'role'  => 'navigation',
		);
		$nav_attrs = array_merge( $nav_attrs, microdata()->SiteNavigationElement( true ) );
		$button_attrs = array(
			'class' => 'menu-toggle',
			'aria-controls' => $menu,
			'aria-expanded' => 'false',
		);
		fluid_library()->apply_attrs_tag( $nav_attrs, 'nav' );
			fluid_library()->apply_attrs_tag( $button_attrs, 'button' ); ?>
				<span class="sr-only">Toggle navigation</span>
				<?php library()->fawe( 'fa-bars' ); ?>
			</button><?php
			wp_nav_menu( array(
				'theme_location' => $menu,
				'menu_id' => 'primary-menu',
				'fallback_cb' => '' )
			); ?>
		</nav><!-- #site-navigation --><?php
	}
}
