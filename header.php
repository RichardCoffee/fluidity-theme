<?php defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php do_action( 'fluid_header_links' ); ?>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]--><?php //*/

  wp_head();

  tcc_custom_css(); ?>

</head>

<body <?php body_class( ); ?>>
	<a class="skip-link sr-only" href="#fluid-content"><?php esc_html_e( 'Skip to content', 'tcc-fluid' ); ?></a><?php
	$slug = get_page_slug();
#fluid()->log('slug: '.$slug ); #, list_filter_hooks() );
	get_template_part( 'template-parts/header', $slug );
