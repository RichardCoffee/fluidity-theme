<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php $micro=microdata(); $micro->WebPage(); ?>>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title itemprop="name"> <?php wp_title('>',true,'right'); ?> </title>
<link rel="icon"          href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<link rel="stylesheet"    href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' type='text/css'>  
<link rel="profile"       href="http://gmpg.org/xfn/11" />
<link rel="pingback"      href="<?php bloginfo('pingback_url'); ?>" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]--><?php

wp_head();
tcc_custom_colors(); ?>

</head>

<body <?php body_class('background'); ?>>
  <div id="overlay" class="hidden translucent fullview"></div><?php
  get_template_part('template_parts/header'); ?>
