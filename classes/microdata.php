<?php

/*
 * classes/microdata.php
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  Sources: http://www.bloggingspell.com/add-schema-org-markup-wordpress/
 *           http://leaves-and-love.net/how-to-improve-wordpress-seo-with-schema-org/
 *           https://github.com/justintadlock/hybrid-core
 *
 */

class TCC_Microdata {

  static $instance = null;

  private function __construct() {
    $this->filters();
  }

  public static function get_instance() {
    if (self::$instance===null) {
      self::$instance = new TCC_Microdata();
    }
    return self::$instance;
  }

 /*
  *  These functions should be inserted into elements like so:
  *
  *  <?php $instance = TCC_Microdata::get_instance(); ?>
  *  <div class="container" role="main" <?php $instance->Blog(); ?>>
  *
  *
  *  The attributes itemprop and itemscope sometimes appear either before
  *    or after the itemtype.  This serves merely as a reminder to the
  *    programmer of what the function is designed for, and has no impact
  *    whatsoever over how these attributes are interpreted by the browser
  *    or search engine.  The itemprop will always apply to any -previously-
  *    declared itemtype.  Do not misinterprete what 'previously' means.
  */

  // descendant of 'CreativeWork->WebPage'
  public function AboutPage() {
    echo "itemscope itemtype='http://schema.org/AboutPage'";
  }

  // descendant of 'CreativeWork'
  public function Blog() {
    echo "itemscope itemtype='http://schema.org/Blog'";
  }

  // descendant of 'CreativeWork->Blog'
  public function BlogPosting() {
    echo "itemprop='blogPost' itemscope itemtype='http://schema.org/BlogPosting'";
  }

  // descendant of 'CreativeWork->WebPage'
  public function ContactPage() {
    echo "itemscope itemtype='http://schema.org/ContactPage'";
  }

  // descendant of 'CreativeWork->WebPage'
  public function ItemPage() {
    echo "itemscope itemtype='http://schema.org/ItemPage'";
  }

  // first tier type
  public function Organization() {
    echo "itemscope itemtype='http://schema.org/Organization'";
  }

  // first tier type
  public function Person() {
    echo "itemscope itemtype='http://schema.org/Person'";
  }

  // descendant of many types - see itemtype link
  public function PostalAddress() {
    echo "itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'";
  }

  // descendant of 'CreativeWork->WebPage'
  public function ProfilePage() {
    echo "itemscope itemtype='http://schema.org/ProfilePage'";
  }

  // descendant of 'CreativeWork->WebPage'
  public function SearchResultsPage() {
    echo "itemscope itemtype='http://schema.org/SearchResultsPage'";
  }

  // descendant of 'CreativeWork->WebPage->WebPageElement'
  public function SiteNavigationElement() {
    echo "itemscope itemtype='http://schema.org/SiteNavigationElement'";
  }

  // descendant of 'CreativeWork'
  public function WebPage() {
    echo "itemscope itemtype='http://schema.org/WebPage'";
  }

  // descendant of 'CreativeWork->WebPage'
  public function WebPageElement() {
    echo "itemscope itemtype='http://schema.org/WebPageElement'";
  }

  // descendant of 'CreativeWork->WebPage->WebPageElement'
  public function WPFooter() {
    echo "itemscope itemtype='http://schema.org/WPFooter'";
  }

  // descendant of 'CreativeWork->WebPage->WebPageElement'
  public function WPHeader() {
    echo "itemscope itemtype='http://schema.org/WPHeader'";
  }

  // descendant of 'CreativeWork->WebPage->WebPageElement'
  public function WPSideBar() {
    echo "itemscope itemtype='http://schema.org/WPSideBar'";
  }

 /*
  *  These functions can be utilized like so:
  *
  *  $instance = TCC_Microdata::get_instance();
  *  echo sprintf(__('Posted on %1$s by %2$s','text-domain'),get_the_date(),$instance->get_the_author());
  *
  */

  public function bloginfo($show,$filter='raw') {
    if ($show=='url') { echo esc_url(home_url()); return; } // bloginfo('url') now deprecated
    $string = get_bloginfo($show,$filter);
    if ($show=='name') { $string = "<span itemprop='copyrightHolder'>$string</span>"; }
    echo $string;
  }

  public function get_bloginfo($show,$filter='raw') {
    if ($show=='url') return esc_url(home_url()); // bloginfo('url') now deprecated
    $string = get_bloginfo($show,$filter);
    if ($show=='name') { $string = "<span itemprop='copyrightHolder'>$string</span>"; }
    return $string;
  }

  public function get_the_author($showlink=false) {
    $string = '';
    if ($showlink) {
      $string.= "<a itemprop='url' rel='author' title='";
      $string.= sprintf(_x('Posts by %s',"Placeholder is the Author's name",'tcc-fluid'),get_the_author()); // FIXME: adjust text domain as required
      $string.= "' href='".get_author_posts_url(get_the_author_meta('ID'))."'>";
    }
    $string.= "<span itemprop='author'>";
    $string.= get_the_author();
    $string.= "</span>";
    if ($showlink) { $string.= "</a>"; }
    return $string;
  }

 /*
  *  These are filters, and will do their work behind the scenes.  Nothing else is required.
  *
  *  Note the priority on these.  Extend the class if you need a different priority.
  *
  */

  private function filters() {
    add_filter('comments_popup_link_attributes',     array($this,'comments_popup_link_attributes'),     5);
    add_filter('comment_reply_link',                 array($this,'comment_reply_link'),                 5);
    add_filter('get_archives_link',                  array($this,'get_archives_link'),                  5);
    add_filter('get_avatar',                         array($this,'get_avatar'),                         5);
    add_filter('get_comment_author_link',            array($this,'get_comment_author_link'),            5);
    add_filter('get_comment_author_url_link',        array($this,'get_comment_author_url_link'),        5);
    add_filter('get_post_time',                      array($this,'get_post_time'),                      5, 3);
    add_filter('get_the_archive_description',        array($this,'get_the_archive_description'),        5);
    add_filter('get_the_archive_title',              array($this,'get_the_archive_title'),              5);
    add_filter('get_the_date',                       array($this,'get_the_date'),                       5, 3);
    add_filter('get_the_title',                      array($this,'get_the_title'),                      5, 2);
    add_filter('post_thumbnail_html',                array($this,'post_thumbnail_html'),                5);
    add_filter('post_type_archive_title',            array($this,'get_the_title'),                      5, 2);
    add_filter('single_cat_title',                   array($this,'single_term_title'),                  5);
    add_filter('single_post_title',                  array($this,'get_the_title'),                      5, 2);
    add_filter('single_tag_title',                   array($this,'single_term_title'),                  5);
    add_filter('single_term_title',                  array($this,'single_term_title'),                  5);
    add_filter('the_author_posts_link',              array($this,'the_author_posts_link'),              5);
    add_filter('wp_get_attachment_image_attributes', array($this,'wp_get_attachment_image_attributes'), 5, 2);
    add_filter('wp_get_attachment_link',             array($this,'wp_get_attachment_link'),             5);
  }

  public function comments_popup_link_attributes($attr) {
    return 'itemprop="discussionURL"';
  }

  public function comment_reply_link($link) {
    return preg_replace('/(<a\s)/i','$1 itemprop="replyToUrl"',$link);
  }

  public function get_archives_link($link) {
    $patterns = array('/(<link.*?)(\/>)/i',"/(<option.*?>)(\'>)/i","/(<a.*?)(>)/i"); #<?
    $result =  preg_replace($patterns,'$1 itemprop="url" $2',$link);
    return preg_replace($patterns,'$1 itemprop="url" $2',$link);
  }

  public function get_avatar($avatar) {
    return preg_replace('/(<img.*?)(\/>|>)/i','$1 itemprop="image" $2',$avatar);
  }

  public function get_comment_author_link($link) {
    $patterns = array('/(<a.*?)(>)/i',      '/(<a.*?>)(.*?)(<\/a>)/i'); #<?
    $replaces = array('$1 itemprop="url"$2','$1<span itemprop="name">$2</span>$3');
    return preg_replace($patterns,$replaces,$link);
  }

  public function get_comment_author_url_link($link) {
    return preg_replace('/(<a.*?)(>)/i','$1 itemprop="url"$2',$link);
  }

  public function get_post_time($time,$format,$gmt) {
    $string = '';
    $post = $this->get_post_time_post(); // FIXME:  what's going on here?
    $date = mysql2date('Y-m-d\TH:i:s',get_post($post)->post_date);
    if ($date) { $string = "<time itemprop='datePublished' datetime='$date'>$time</time>";
    } else { log_entry(__FILE__.':'.__LINE__.') invalid publish date?',$post,get_post()); }
    return $string;
  }

  private function get_post_time_post() {
    $trace = debug_backtrace();
log_entry('debug trace for get_post_time_post',$trace);
    foreach($trace as $item) {
      if ($item['function']=='get_post_time') {
        return $item['args'][2];
      }
    }
    return array();
  }

  public function get_the_archive_description($descrip) {
    return "<span itemprop='description'>$descrip</span>";
  }

  public function get_the_archive_title($title) {
    if (is_author()) {
      $title = preg_replace('/(<span.*?)(>)/i','$1 itemprop="author"$2',$title);
    } else if ($title==__('Archives')) { // do not add text domain to this
      $title = "<span itemprop='headline'>$title</span>";
    }
    return $title;
  }

  public function get_the_date($the_date,$format,$postID) {
    $date = mysql2date('Y-m-d',get_post($postID)->post_date);
    return "<time itemprop='datePublished' datetime='$date'>$the_date</time>";
  }

  public function get_the_title($title,$id) {
    return "<span itemprop='headline'>$title</span>";
  }

  public function post_thumbnail_html($html) {
    return preg_replace('/(<img.*?)(\/>|>)/i','$1 itemprop="image" $2',$html);
  }

  public function single_term_title($title) {
    return "<span itemprop='headline'>$title</span>";
  }

  public function the_author_posts_link($link) {
    $pattern = array('/(<a.*?)(>)/i',      '/(<a.*?>)(.*?)(<\/a>)/i'); #<?
    $replace = array('$1 itemprop="url"$2','$1<span itemprop="name">$2</span>$3');
    return preg_replace($pattern,$replace,$link);
  }

  public function wp_get_attachment_image_attributes($attr,$attachment) {
    $attr['itemprop'] = 'thumbnail';
    return $attr;
  }

  public function wp_get_attachment_link($link) {
    return preg_replace('/(<a.*?)>/i','$1 itemprop="contentURL">',$link);
  }

}

