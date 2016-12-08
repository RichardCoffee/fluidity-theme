<?php

/*
 *  File Name:  template-parts/header.php
 *
 */

$page  = fluidity_page_slug();
$color = tcc_color_scheme(); 

?>

<div id="" class="main-header" style="background-color:red;">
 <div class="container-fluid">

  <div class="row">
    <div class="col-md-4">
	
	  <img class="img-responsive hidden-xs" src="http://the-creative-collective.com/wp-content/uploads/2016/11/tcclogo.png" alt="The Creative Collective" itemprop="image">
	
	</div>
	<div class="col-md-8">

	<?php get_template_part('template-parts/menu'); ?>
	
	</div>
  </div>

 </div>
</div>