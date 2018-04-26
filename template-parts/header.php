<?php
/*
 *  File Name:  template-parts/header.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<header id="fluid-header" <?php microdata()->WPHeader(); ?> role="banner">

	<div class="<?php e_esc_attr( 'header-' . tcc_layout( 'header', 'static' ) ); ?> <?php e_esc_attr( container_type( 'header' ) ); ?>">

		<?php do_action('tcc_header_body_content', get_page_slug() ); ?>

	</div>

</header><?php
