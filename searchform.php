<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" role="form">
  <div class="input-group">
    <label class="sr-only" for="s"><?php esc_html_e('Search field','tcc-fluid'); ?></label>
    <input type="text" value="" name="s" id="s" class="form-control" placeholder="<?php esc_html_e('Search','tcc-fluid'); ?>" />
    <span class="input-group-btn">
      <button class="btn btn-fluidity" type="submit"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>
