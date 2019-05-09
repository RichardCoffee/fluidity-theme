<?php
/*
 *  File Name:  template-parts/menu.php
 *
 * @since 20160301
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/template-parts/menu.php
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

defined( 'ABSPATH' ) || exit;

$page = get_page_slug();
$menu = apply_filters( 'fluid_menu', 'primary', $page ); # defaults: primary, header or footer
$msys = apply_filters( 'fluid_menu_system', 'underscore' );

do_action( 'fluid_before_menu', $page );

who_am_i();

if ( has_nav_menu( $menu ) ) {

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

			<div class="navbar-header"><?php
					fluid()->tag( 'button', $button_attrs ); ?>
					<span class="sr-only"><?php
						esc_html_e( 'Toggle navigation', 'tcc-fluid' ); ?>
					</span><?php
					fluid()->fawe( 'fa-bars' ); ?>
				</button>
				<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>"><?php microdata()->bloginfo( 'name' ); ?></a>
			</div>

			<div class="collapse navbar-collapse navbar-<?php echo $menu; ?>-collapse"><?php
				$args = array(
					'menu'           => $menu,
					'menu_id'        => "$menu-menu",
					'theme_location' => $menu,
					'depth'          => 3,
					'container'      => false,
					'menu_class'     => 'nav navbar-nav',
					'walker'         => new TCC_NavWalker_Bootstrap(),
					'fallback_cb'    => 'TCC_NavWalker_Bootstrap::fallback'
				);
				$args = apply_filters( 'fluid_nav_menu', $args );
				wp_nav_menu( $args );
				fluid_show_color_scheme(); ?>
			</div>

		</nav><!-- #site-navigation --><?php

	} else {

		/* underscore navigation */

		$nav_attrs = apply_filters( 'fluid_menu_underscore', $nav_attrs );
		fluid()->tag( 'nav', $nav_attrs );

			fluid()->tag( 'button', $button_attrs ); ?>
				<span class="sr-only"><?php
					esc_html_e( 'Toggle navigation', 'tcc-fluid' ); ?>
				</span><?php
			fluid()->fawe( 'fa-bars' ); ?>
			</button><?php

			$args = array(
				'menu'           => $menu,
				'menu_id'        => "$menu-menu",
				'theme_location' => $menu,
				'fallback_cb'    => ''
			);
			$args = apply_filters( 'fluid_nav_menu', $args );
			wp_nav_menu( $args );
#			fluid_show_color_scheme(); ?>
		</nav><!-- #site-navigation --><?php

	}
}
