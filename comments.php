<?php
/*
 * fluidity/comments.php
 *
 */

if (post_password_required()) { return; }
global $micro; ?>

<div id="comments" class="comments-area" itemprop="comment" itemscope itemtype='http://schema.org/Comment'><?php
  if (have_comments()) { ?>
    <h2 class="comments-title"><?php
      $number = get_comments_number();
      $format = _n('One thought on %2$s','%1$s thoughts on %2$s',$number,'tcc-fluid');
      $number = "<span itemprop='commentCount'>$number</span>";
      $title  = '&ldquo;'.get_the_title().'&rdquo;';
      echo sprintf($format,$number,$title); ?>
    </h2><?php
    comment_navigation(); ?>
    <ol class="comment-list"><?php
      wp_list_comments(array('style'=>'ol','short_ping'=>true,'avatar_size'=>34)); ?>
    </ol><!-- .comment-list --><?php
    comment_navigation();
    if (!comments_open()) { ?>
      <p class="no-comments"><?php
        _e('Comments are closed.','tcc-fluid'); ?>
      </p><?php
    }
  }
  comment_form(); ?>
</div><!-- #comments --><?php

function comment_navigation() {
  if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
    <nav class="navigation comment-navigation" role="navigation">
      <h1 class="screen-reader-text"><?php
        _e('Comment navigation','tcc-fluid'); ?>
      </h1>
      <div class="nav-previous"><?php // FIXME:  poor translation string
        previous_comments_link('&larr; '.__('Older Comments','tcc-fluid')); ?>
      </div>
      <div class="nav-next"><?php
        next_comments_link(__('Newer Comments','tcc-fluid').' &rarr;'); ?>
      </div>
    </nav><!-- #comment-nav-above --><?php
  }
} ?>
