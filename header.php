<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php microdata()->WebPage(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon"          href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon" sizes="16x16" /><?php /*
  <link rel="stylesheet"    href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' type='text/css'> */ ?>
  <link rel="profile"       href="http://gmpg.org/xfn/11" />
  <link rel="pingback"      href="<?php bloginfo('pingback_url'); ?>" />

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]--><?php //*/

  wp_head();

  tcc_custom_css(); ?>

</head>

<body <?php body_class('background'); ?>>
	<a class="skip-link sr-only" href="#content"><?php esc_html_e('Skip to content','tcc-fluid'); ?></a>
	<header><?php
		$slug = get_page_slug();
		get_template_part('template-parts/header',$slug); ?>
	</header>

