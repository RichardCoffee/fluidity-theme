<?php 
/*     This is comment.phps by Christian Montoya, http://www.christianmontoya.com

    Available to you under the do-whatever-you-want license. If you like it, 
    you are totally welcome to link back to me. 
    
    Use of this code does not grant you the right to use the design or any of the 
    other files on my site. Beyond this file, all rights are reserved, unless otherwise noted. 
    
    Enjoy!
*/ ?>

<!-- Comments code provided by christianmontoya.com --><?php

if (!empty($post->post_password) && $_COOKIE['wp-postpass_'.COOKIEHASH]!=$post->post_password) { ?>
  <p id="comments-locked"><?php
    _e('Enter your password to view comments.','tcc-fluid'); ?>
  </p><?php
  return;
}

if (pings_open()) { ?>
  <p id="respond">
    <span id="trackback-link">
      <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('Get a Trackback link','tcc-fluid'); ?></a>
    </span>
  </p><?php
}

$comments = get_comments("post_id=".$post->ID);

if ($comments) {

  /* Author values for author highlighting */
  /* Enter your email and name as they appear in the admin options */
  $author = array("highlight" => "highlight",
                  "email"     => "YOUR EMAIL HERE",
                  "name"      => "YOUR NAME HERE");

  /* Count the totals */
  $numPingBacks = 0;
  $numComments  = 0;

  /* Loop through comments to count these totals */
  foreach ($comments as $comment) {
    if ($comment->comment_type != "comment") {
      $numPingBacks++;
    } else {
      $numComments++;
    }
  }

  /* Used to stripe comments */
  $striping = 'odd';

  /* This is a loop for printing pingbacks/trackbacks if there are any */
  if ($numPingBacks != 0) { ?>
   <h2 class="comments-header"><?php _n( '%d Trackback/Pingback', '%d Trackbacks/Pingbacks', $numPingBacks, 'tcc-fluid' ); ?></h2>
   <ol id="trackbacks"><?php
      $string = _x('%1s: %2$s on %3$s','first placeholder is the comment type, second placeholder is an author link, third placeholder is a date','tcc-fluid');
      $type_arr = array('comment'=>__('Comment'),'trackback'=>__('Trackback'),'pingback'=>__('Pingback'));
      foreach ($comments as $comment) {
        $comm_type = $comment->comment_type;
        if ($comm_type!=="comment") {
          $comm_css = "post $comm_type $striping" ?>
          <li id="comment-<?php echo $comment->comment_ID ?>" class="<?php comment_class( $comm_css, $comment->comment_ID, $post->ID, true); ?>"><?php
            echo sprintf( $string, $type_arr[$comm_type], get_comment_author_link($comment->comment_ID), get_comment_date('',$comment->comment_ID) ); ?>
          </li><?php
          $striping = ($striping==='odd') ? 'even' : 'odd';
        }
      } ?>
    </ol><?php
  }

  /* This is a loop for printing comments */
  if ($numComments != 0) { ?>
    <h2 class="comments-header"><?php _e($numComments); ?> Comments</h2>
    <ol id="comments"><?php
      $text_says = __('says','tcc-fluid');
      $auth_says = _x('%1$s %2$s:',"first placeholder is the comment author link, second placeholder is a span tag surrounding translation of 'says'",'tcc-fluid');
      foreach ($comments as $comment) {
        if (get_comment_type()=="comment") {

          /* Highlighting class for author or regular striping class for others */
          $css = ( get_the_author_meta('user_email') === get_comment_author_email($comment->comment_ID) ) ? 'post-author' : $striping; ?>
          <li id="comment-<?php echo $comment->comment_ID; ?>" class="<?php comment_class( $css, $comment->comment_ID, $post->ID, true );; ?>">

            <div class="comment-author vcard"><?php
              echo get_avatar( $comment, 40 );
              echo sprintf( $auth_says, get_comment_author_link($comment->comment_ID), $text_says); ?>
            </div><!-- .comment-author .vcard --><?php

            // FIXME:  check if user is author??
            if ( $comment->comment_approved == '0' ) { ?>
              <em><?php _e( 'Your comment is awaiting moderation.', 'tcc-fluid' ); ?></em><br /><?php
            } ?>

            <div class="comment-meta commentmetadata">
              <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php
                comment_date('',$comment->comment_ID); ?>
              </a> <?php
#              edit_comment_link( __( '(Edit)', 'tcc-fluid' ), ' ' ); ?>
            </div><!-- .comment-meta .commentmetadata -->

            <div class="comment-text"><?php
              comment_text(); ?>
            </div>

            <div class="reply"><?php
              comment_reply_link( '', $comment->comment_ID, $post->ID ); ?>
            </div><!-- .reply -->

          </li><?php
          $striping = ($striping==='odd') ? 'even' : 'odd';
        }
      } ?>
    </ol><?php
  } else {
    /* No comments at all means a simple message instead */ ?>
    <h2 class="comments-header"><?php _e('No Comments Yet','tcc-fluid'); ?></h2>
    <p><?php _e('You can be the first to comment!','tcc-fluid'); ?></p><?php
  }

  if (comments_open()) {
    /* This would be a good place for live preview... 
    <div id="live-preview">
        <h2 class="comments-header">Live Preview</h2>
        <?php live_preview(); ?>
    </div> */ ?>

    <div id="comments-form">
      <h2 id="comments-header"><?php _e('Leave a comment','tcc-fluid'); ?></h2><?php
      if (get_option('comment_registration') && !$user_ID ) { ?>
        <p id="comments-blocked"><?php
          $logged = __('logged in');
          $string = _x('You must be %s to post a comment.',"placeholder is a link wrapping the translation for 'logged in'",'tcc-fluid');
          $link   = '<a href="'.get_option('siteurl').'/wp-login.php?redirect_to='.the_permalink().'">'.$logged.'</a>';
          echo sprintf($string,$link); ?>
        </p><?php
      } else { ?>
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform"><?php
          if ($user_ID) {
            $form = "<p>";
            $form.= sprintf(__('Logged in as %s','tcc-fluid'),fluid_user_profile_link());
            $form.= " <a href='";
            $form.= get_option('siteurl');
            $form.= "/wp-login.php?action=logout'";
            $form.= " title='";
            $form.= __('Log out of this account','tcc-fluid');
            $form.= "'>";
            $form.= __('Logout','tcc-fluid');
            $form.= "</a></p>";
            echo $form;
          } else { ?>
            <p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" />
        <label for="author">Name<?php if ($req) _e(' (required)'); ?></label></p>
        
        <p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" />
        <label for="email">E-mail (will not be published)<?php if ($req) _e(' (required)'); ?></label></p>
        
        <p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" />
        <label for="url">Website</label></p>
    
    <?php endif; ?>

    <?php /* You might want to display this: 
        <p>XHTML: You can use these tags: <?php echo allowed_tags(); ?></p> */ ?>

        <p><textarea name="comment" id="comment" rows="5" cols="30"></textarea></p>
        
        <?php /* Buttons are easier to style than input[type=submit], 
                but you can replace: 
                <button type="submit" name="submit" id="sub">Submit</button>
                with: 
                <input type="submit" name="submit" id="sub" value="Submit" />
                if you like */ 
        ?>
        <p><button type="submit" name="submit" id="sub">Submit</button>
        <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>"></p>
    
    <?php do_action('comment_form', $post->ID); ?>

    </form>
    </div>

<?php endif; // If registration required and not logged in ?>

<?php else : // Comments are closed ?>
    <p id="comments-closed">Sorry, comments for this entry are closed at this time.</p>
<?php endif; ?> 
