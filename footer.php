<?php 

/*
 *  fluidity/footer.php
 *
 */

global $micro; ?>

<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php $micro->WPFooter(); ?>>
  <div class="row"><?php
    get_template_part('template-parts/footer',tcc_layout('footer'));
    wp_footer(); ?>
  </div>
</div>

</body>

</html>
