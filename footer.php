<?php 

/*
 *  fluidity/footer.php
 *
 */

 ?>

<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php microdata()->WPFooter(); ?>>
  <div class="row"><?php
    get_template_part('template-parts/footer',tcc_layout('footer'));
    wp_footer(); ?>
  </div>
</div>

</body>

</html>
