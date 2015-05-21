<?php
/*
 *  tcc-fluidity/template_parts/profile.php
 *
 */

global $micro; ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php $micro->Person(); ?>>
 <h1><?php printf(__('All posts by %s','tcc-theme'),get_the_author()); ?></h1><?php
  if ($descrip=get_the_author_meta('description')) { ?>
    <div class="author-description"><?php
      echo $descrip; ?>
    </div><?php
  } ?>
</div>
