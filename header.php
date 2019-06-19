<?php
/**
 * @package Fluidity
 * @subpackage Main
 * @since 20150511
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/header.php
 */
defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"><?php
	do_action( 'fluid_header_links' ); ?>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]--><?php //*/

  wp_head();

  fluid_custom_css(); ?>

</head>

<body <?php body_class( ); ?> <?php fluid_schema_page_check(); ?>>
	<a class="skip-link sr-only" href="#fluid-content"><?php esc_html_e( 'Skip to content', 'tcc-fluid' ); ?></a><?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
	$slug = get_page_slug();
	$dir  = apply_filters( 'fluid_header_template_dir', 'template-parts', $slug );
	$root = apply_filters( 'fluid_header_template_root', 'header', $slug );
	get_template_part( "$dir/$root", $slug );
