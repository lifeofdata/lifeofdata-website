<?php
/*
Plugin Name: oEmbed styling
Plugin URI: http://wordpress.org/plugins/oembed-styling/
Description: This plugins wraps oEmbed generated HTML with divs that allow for styling them with CSS (style sheet of the theme)
Version: 1.1
Author: Honza Skypala
Author URI: http://www.honza.info/
License: WTFPL 2.0
*/

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class oEmbedStyling {
  const version = "1.1";

  public function __construct() {
    add_filter('oembed_dataparse', array($this, 'filter'), 10, 3);
    
    register_activation_hook(__FILE__, array($this, 'flush_oembed_cache'));
    register_deactivation_hook(__FILE__, array($this, 'flush_oembed_cache'));
  }

  public function filter($html, $data, $url) {
  	preg_match('#(http|ftp)s?://(www\.)?([a-z0-9\.\-]+)/?.*#i', $url, $matches);
  	$server4css = str_replace(".", "-", $matches[3]);
  	return "<div class=\"oembed oembed-$data->type oembed-$server4css oembed-$data->type-$server4css\">$html</div>";
  }
  
  public function flush_oembed_cache() {
  	// flush the complete oEmbed cache, so all oEmbed code is re-generated
  	global $wpdb;
  	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_oembed_%'");
  }
}

$wp_oembed_styling = new oEmbedStyling();
?>