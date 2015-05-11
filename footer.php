<?php 

/*
 *  fluidity/footer.php
 *
 */

?>

<div class="<? echo container_type('footer'); ?>">
  <div class="row"><?php
    do_action('tcc_footer');
    wp_footer(); ?>
  </div>
</div>

</body>

</html>
