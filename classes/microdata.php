<?php

/*
 *  tcc-fluid/classes/microdata.php
 *
 *  Sources: http://www.bloggingspell.com/add-schema-org-markup-wordpress/
 *           https://github.com/justintadlock/hybrid-core
 *
 */

class TCC_Microdata {

  static $instance = null;

  private function __construct() {
#    add_filter('comments_popup_link_attributes',array($this,'comments_popup_link_attributes',2));
#    add_filter('comment_reply_link',            array($this,'comment_reply_link_filter',2));
#    add_filter('get_avatar',                    array($this,'get_avatar',2));
#    add_filter('get_comment_author_link',       array($this,'get_comment_author_link',2));
#    add_filter('get_comment_author_url_link',   array($this,'get_comment_author_url_link',2));
#    add_filter('post_thumbnail_html',           array($this,'post_thumbnail_html',2));
    add_filter('the_author_posts_link',         array($this,'the_author_posts_link',2));
  }

  public static function get_instance () {
    if (self::$instance===null) {
      self::$instance = new TCC_Microdata();
    }
    return self::$instance;
  }

  /*
   *  These function should be inserted into elements like so:
   *
   *  <article id="post-<?php the_ID(); ?>" <?php post_class(); $micro->BlogPosting(); ?>>
   *
   */

  public function Author() {
    echo "itemscope itemtype='http://schema.org/Person' itemprop='mainContentOfPage'";
  }

  public function Blog() {
    echo "itemscope itemtype='http://schema.org/Blog' itemprop='mainContentOfPage'";
  }

  public function BlogPosting() {
    echo "itemprop='blogPost' itemscope itemtype='http://schema.org/BlogPosting'";
  }

  public function Footer() {
    echo "itemscope itemtype='http://schema.org/WPFooter'";
  }

  public function Header() {
    echo "itemscope itemtype='http://schema.org/WPHeader'";
  }

  public function Navigation() {
    echo "itemscope itemtype='http://schema.org/SiteNavigationElement'";
  }

  public function ProfilePage() {
    echo "itemscope itemtype='http://schema.org/ProfilePage' itemprop='mainContentOfPage'";
  }

  public function SideBar() {
    echo "itemscope itemtype='http://schema.org/WPSideBar'";
  }
  /*
   *  These functions should be utilized like so:
   *
   *  echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),$micro->get_the_date(),$micro->get_the_author());
   *
   */

  public function get_bloginfo($show,$filter='raw') {
    $string = get_bloginfo($show,$filter);
    if ($show=='name') { $string = "<span itemprop='copyrightHolder'>$string</span>"; }
    return $string;
  }

  public function get_the_author() {
    $string = "<span itemprop='author' itemscope itemtype='http://schema.org/Person'>";
    $string.= get_the_author();
    $string.= "</span>";
    return $string;
  }

  public function get_the_date() {
    $datetime = get_the_date('Y-m-d');
    $string = "<time itemprop='datePublished' datetime='$datetime'>";
    $string.= get_the_date();
    $string.= "</time>";
    return $string;
  }

  public function get_the_title() {
    $string = "<span itemprop='headline'>";
    $string.= get_the_title();
    $string.= "</span>";
    return $string;
  }

  /*
   *  These are filters, and will do their work behind the scenes.  Nothing else required
   *
   */

  public function the_author_posts_link( $link ) {
    $pattern = array("/(<a.*?)(>)/i",'/(<a.*?>)(.*?)(<\/a>)/i');
    $replace = array('$1 class="url fn n" itemprop="url"$2','$1<span itemprop="name">$2</span>$3');
    return preg_replace($pattern,$replace,$link);
  }


}

