<?php
/*
  Plugin Name: Church Podcast Publisher
  Depends: Category Featured Images
  Plugin URI:
  Description: Creates posts using ID3 information in MP3 files.
  Author: Jared Bangs
  Version: 1.0.0
  Author URI: http://freepressblog.org
 */
namespace AgileStory\PodcastPublisher;

/*
 * Requires:
 * PHP 5.3 or greater
 * Ventura Vineyard Theme
 * Category Featured Images - https://wordpress.org/plugins/category-featured-images/
 *
 * */

require_once 'Bootstrapper.php';
new Main(plugin_basename(__FILE__));
