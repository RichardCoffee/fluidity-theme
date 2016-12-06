<?php 

/*
 *  fluidity/footer.php
 *
 */

 ?>

<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php microdata()->WPFooter(); ?>>
  <div class="row"><?php
    $slug = fluidity_page_slug();
    get_template_part('template-parts/footer',$slug);
    wp_footer(); ?>
  </div>
</div>

</body>

</html>
