<?php

/*
 * tcc-fluidity/classes/microdata.php
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
  public function Person() {
    echo "itemscope itemtype='http://schema.org/Person'";
  }

  // descendant of many types - see link
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
   *  echo sprintf(__('Posted on %1$s by %2$s','text-domain'),$instance->get_the_date(),$instance->get_the_author());
   *
   */

  public function get_bloginfo($show,$filter='raw') {
    $string = get_bloginfo($show,$filter);
    if ($show=='name') { $string = "<span itemprop='copyrightHolder'>$string</span>"; }
    return $string;
  }

  public function get_the_author() {
    $string = "<span itemprop='author'>";
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
   *  These are filters, and will do their work behind the scenes.  Nothing else is required.
   *
   */

  private function filters() {
    add_filter('comments_popup_link_attributes',      array($this,'comments_popup_link_attributes'),     5);
    add_filter('comment_reply_link',                  array($this,'comment_reply_link_filter'),          5);
    add_filter('get_avatar',                          array($this,'get_avatar'),                         5);
    add_filter('get_comment_author_link',             array($this,'get_comment_author_link'),            5);
    add_filter('get_comment_author_url_link',         array($this,'get_comment_author_url_link'),        5);
    add_filter('post_thumbnail_html',                 array($this,'post_thumbnail_html'),                5);
    add_filter('the_author_posts_link',               array($this,'the_author_posts_link'),              5);
    add_filter( 'wp_get_attachment_image_attributes', array($this,'wp_get_attachment_image_attributes'), 5, 2);
    add_filter( 'wp_get_attachment_link',             array($this,'wp_get_attachment_link'),             5);
  }

  public function comments_popup_link_attributes($attr) {
    return 'itemprop="discussionURL"';
  }

  public function comment_reply_link($link) {
    return preg_replace('/(<a\s)/i','$1 itemprop="replyToUrl"',$link);
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

  public function post_thumbnail_html( $html ) {
    return preg_replace('/(<img.*?)(\/>|>)/i','$1 itemprop="image" $2',$html);
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

