<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" role="form">
  <div class="input-group">
    <input type="text" value="" name="s" id="s" class="form-control" placeholder="<?php _e('Search','creatom'); ?>" />
    <span class="input-group-btn">
      <button class="btn btn-<?php echo tcc_color_scheme('searchform'); ?>" type="submit"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>
