<?php

/*
 *  tcc-fluidity/classes/microdata.php
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  Sources: http://www.bloggingspell.com/add-schema-org-markup-wordpress/
 *           https://github.com/justintadlock/hybrid-core
 *
 */

class TCC_Microdata {

  static $instance = null;

  private function __construct() {
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

  public static function get_instance () {
    if (self::$instance===null) {
      self::$instance = new TCC_Microdata();
    }
    return self::$instance;
  }

  /*
   *  These functions should be inserted into elements like so:
   *
   *  <div class="container" role="main" <?php $micro->Blog(); ?>>
   *
   *  Notice that some functions either do not have the itemprop attribute
   *    or have an itemprop of 'mainContentOfPage'.  Please pay attention to
   *    this when utilizing these functions.  Extend the class if you have
   *    different needs.
   */

  public function Author() {
    echo "itemprop='author' itemscope itemtype='http://schema.org/Person'";
  }

  public function Blog() {
    echo "itemscope itemtype='http://schema.org/Blog' itemprop='mainContentOfPage'";
  }

  public function BlogPosting() {
    echo "itemprop='blogPost' itemscope itemtype='http://schema.org/BlogPosting'";
  }

  public function Person() {
    echo "itemscope itemtype='http://schema.org/Person' itemprop='mainContentOfPage'";
  }

  public function PostalAddress() {
    echo "itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'";
  }

  public function ProfilePage() {
    echo "itemscope itemtype='http://schema.org/ProfilePage' itemprop='mainContentOfPage'";
  }

  public function SiteNavigationElement() {
    echo "itemscope itemtype='http://schema.org/SiteNavigationElement'";
  }

  public function WPFooter() {
    echo "itemscope itemtype='http://schema.org/WPFooter'";
  }

  public function WPHeader() {
    echo "itemscope itemtype='http://schema.org/WPHeader'";
  }

  public function WPSideBar() {
    echo "itemscope itemtype='http://schema.org/WPSideBar'";
  }

  /*
   *  These functions should be utilized like so:
   *
   *  echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),$instance->get_the_date(),$instance->get_the_author());
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
   *  These are filters, and will do their work behind the scenes.  Nothing else is required.
   *
   */

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

