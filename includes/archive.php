<?php

function fluid_archive_page_title() {
  the_archive_title( '<h1 class="page-title">', '</h1>' );
  the_archive_description( '<div class="taxonomy-description">', '</div>' );
}
do_action('fluid_archive_page_title','fluid_archive_page_title');
