<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" role="form">
  <div class="input-group">
    <label class="screen-reader-text" for="s"><?php _e('Search for','tcc-fluid'); ?></label>
    <input type="text" value="" name="s" id="s" class="form-control" placeholder="<?php _e('Search','tcc-fluid'); ?>" />
    <span class="input-group-btn">
      <button class="btn btn-<?php echo tcc_color_scheme('searchform'); ?>" type="submit"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>
