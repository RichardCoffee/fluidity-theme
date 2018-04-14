<?php define( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php microdata()->WebPage(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon"       href="<?php echo get_theme_file_uri( 'favicon.ico' ); ?>" type="image/x-icon" />
  <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Open+Sans+Condensed:300,300i,700|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i' type='text/css'>
<?php #  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous"> ?>
  <link rel="profile"    href="http://gmpg.org/xfn/11" />
  <link rel="pingback"   href="<?php bloginfo( 'pingback_url' ); ?>" />

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
#log_entry('slug: '.$slug ); #, list_filter_hooks() );
	get_template_part( 'template-parts/header', $slug );
