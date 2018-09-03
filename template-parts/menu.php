<?php  /* Name: Menu Default */
/*
 *  File Name:  template-parts/menu.php
 *
 */

defined( 'ABSPATH' ) || exit;

$page = get_page_slug();
$menu = apply_filters( 'fluid_menu', 'primary', $page ); # defaults: primary, header or footer
$msys = apply_filters( 'fluid_menu_system', 'underscore' );
fluid_taxonomy( [ 'limit' => 1 ] );

who_am_i();

if ( has_nav_menu( $menu ) ) {

#	$main_css = get_menu_class($menu,$page);

	$nav_attrs = array(
		'id'    => 'site-navigation',
		'class' => "main-navigation {$menu}-navigation {$page}-{$menu}-navigation navbar-fluidity",
	);
	$nav_attrs = array_merge( $nav_attrs, microdata()->microdata_attrs( 'SiteNavigationElement' ) );

	$button_attrs = array(
		'class'         => 'menu-toggle navbar-toggle', // underscore and bootstrap
		'aria-controls' => "$menu-menu",
		'aria-expanded' => 'false',
		'data-toggle'   => 'collapse',               // bootstrap
		'data-target'   => ".navbar-$menu-collapse", // bootstrap
	);

	if ( $msys === 'bootstrap' ) {

		/* bootstrap navigation */

		$nav_attrs['class']   = "navbar navbar-fluidity navbar-$menu navbar-$page navbar-{$page}-$menu";
		$button_attrs['type'] = 'button';

		fluid()->tag( 'nav', $nav_attrs ); ?>

			<div class="navbar-header">
				<?php fluid()->tag( 'button', $button_attrs ); ?>
					<span class="sr-only">Toggle navigation</span>
					<?php fluid()->fawe( 'fa-bars' ); ?>
				</button>
				<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>"><?php microdata()->bloginfo( 'name' ); ?></a>
			</div>

			<div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php
				// FIXME: add filter for wp_nav_menu($args)
#				wp_nav_menu( array('menu'=>$menu,'container'=>false,'menu_class'=>'nav navbar-nav','walker'=> new TCC_NavWalker_Bootstrap(), 'fallback_cb' => '' ) );
#				require_once( FLUIDITY_HOME . 'vendor/wp-bootstrap-navwalker.php' );
				wp_nav_menu( array(
					'menu'           => $menu,
					'menu_id'        => "$menu-menu",
					'theme_location' => $menu,
					'depth'          => 3,
					'container'      => false,
					'menu_class'     => 'nav navbar-nav',
					'walker'         => new TCC_NavWalker_Bootstrap(),
					'fallback_cb'    => 'TCC_NavWalker_Bootstrap::fallback'
				) );
				fluid_show_color_scheme(); ?>
			</div>

		</nav><!-- #site-navigation --><?php

	} else {

		/* underscore navigation */

		$nav_attrs = apply_filters( 'fluid_menu_underscore', $nav_attrs );
		fluid()->tag( 'nav', $nav_attrs );

			fluid()->tag( 'button', $button_attrs ); ?>
				<span class="sr-only">Toggle navigation</span>
				<?php fluid()->fawe( 'fa-bars' ); ?>
			</button><?php

			wp_nav_menu( array(
				'menu'           => $menu,
				'menu_id'        => "$menu-menu",
				'theme_location' => $menu,
				'fallback_cb'    => '' )
			);

			fluid_show_color_scheme(); ?>

		</nav><!-- #site-navigation --><?php

	}
}
