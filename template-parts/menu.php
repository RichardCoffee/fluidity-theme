<?php  /* Name: Menu Default */
/*
 *  File Name:  template-parts/menu.php
 *
 */

defined( 'ABSPATH' ) || exit;

$page = get_page_slug();
$menu = apply_filters( 'tcc_menu', 'primary', $page );	#	defaults: primary, header or footer

if ( has_nav_menu( $menu ) ) {

#	$main_css = get_menu_class($menu,$page);

	$nav_attrs = array(
		'id'    => 'site-navigation',
		'class' => "main-navigation {$menu}-navigation {$page}-{$menu}-navigation navbar-fluidity",
		'role'  => 'navigation',
	);
	$nav_attrs = array_merge( $nav_attrs, microdata()->microdata_attrs( 'SiteNavigationElement' ) );

	$button_attrs = array(
		'class'         => 'menu-toggle navbar-toggle', // underscore and bootstrap
		'aria-controls' => $menu,
		'aria-expanded' => 'false',
		'data-toggle'   => 'collapse',               // bootstrap
		'data-target'   => ".navbar-$menu-collapse", // bootstrap
	);

	if ( tcc_layout( 'menu', 'bootstrap' ) === 'bootstrap' ) {

		/* bootstrap navigation */

		$nav_attrs['class']   = "navbar navbar-fluidity navbar-$menu navbar-$page navbar-{$page}-$menu";
		$button_attrs['type'] = 'button';

		fluid()->tag( 'nav', $nav_attrs ); ?>

			<div class="navbar-header">
				<?php fluid()->tag( 'button', $button_attrs ); ?>
					<span class="sr-only">Toggle navigation</span>
					<?php fluid()->fawe( 'fa-bars' ); ?>
				</button>
				<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
			</div>

			<div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php
				// FIXME: add filter for wp_nav_menu($args)
#				wp_nav_menu( array('menu'=>$menu,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new TCC_NavWalker_Bootstrap(), 'fallback_cb' => '' ) );
#				require_once( FLUIDITY_HOME . 'vendor/wp-bootstrap-navwalker.php' );
				wp_nav_menu( array(
					'menu'           => $menu,
					'menu_id'        => 'primary-menu',
					'theme_location' => $menu,
					'depth'          => 3,
					'container'      => false,
					'menu_class'     => 'nav navbar-nav',
					'walker'         => new TCC_NavWalker_Bootstrap(),
					'fallback_cb'    => 'TCC_NavWalker_Bootstrap::fallback'
				) ); ?>
				<span class="pull-right margint1e">
					<?php echo fluid_color_scheme(); ?>
				</span>
			</div>

		</nav><?php

	} else {

		/* underscore navigation */

		fluid()->tag( 'nav', $nav_attrs );

			fluid()->tag( 'button', $button_attrs ); ?>
				<span class="sr-only">Toggle navigation</span>
				<?php fluid()->fawe( 'fa-bars' ); ?>
			</button><?php

			wp_nav_menu( array(
				'theme_location' => $menu,
				'menu_id'        => 'primary-menu',
				'fallback_cb'    => '' )
			); ?>

			<span class="pull-right margint1e">
				<?php echo fluid_color_scheme(); ?>
			</span>

		</nav><!-- #site-navigation --><?php

	}
}
