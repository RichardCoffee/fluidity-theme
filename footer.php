<?php 

/*
 *  fluidity/footer.php
 *
 */

global $micro;

?>

<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php $micro->WPFooter(); ?>>
  <div class="row"><?php
    do_action('tcc_footer');
    wp_footer(); ?>
  </div>
</div>

</body>

</html>
