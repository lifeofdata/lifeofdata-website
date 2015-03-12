<?php
/*
Plugin Name: Better Adjacent Post Links
Plugin URI: http://unalignedcode.wordpress.com/better-nearby-post-links
Description: Adds two enhanced commands to replace wordpress 'previous post' and 'next post' functions. Works with Wordpress 2.7.
Version: 1.1
Author: unalignedcoder
Author URI: http://unalignedcode.wordpress.com
*/

function my_prev_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '', $string_lenght=22, $pre_link) {

	if ( is_attachment() )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = get_adjacent_post($in_same_cat, $excluded_categories, true);

	if ( !$post )
		return;		
		
	$title = apply_filters('the_title', $post->post_title, $post);	
	
	//create substring of the title to the last space and add dots
	if (strlen($post->post_title) >= ($string_lenght+1)){
		$short = substr($title,0,$string_lenght);	
		if (substr_count($short," ") > 1) {
			$lastspace = strrpos($short," ");
			$short = substr($short,0,$lastspace);
		}
		$dots = '...';
	} 
	else { 
		$short = $post->post_title;
		$dots = '';
	}	
	$string = '<a href="'.get_permalink($post->ID).'" title="previous post: '.$title.'">';
	$link = str_replace('%title', $short.$dots, $link);
	$link = $pre . $string . $link . '</a>';
	$format = str_replace('%link', $link, $format);
	echo $pre_link.$format;
}

function  my_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '', $string_lenght=22, $pre_link) {
		
	if ( is_attachment() )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = get_adjacent_post($in_same_cat, $excluded_categories, false);

	if ( !$post )
	return;
	
	$title = apply_filters('the_title', $post->post_title, $post);

	//create substring of the title to the last space and add dots
	if (strlen($post->post_title) >= ($string_lenght+1)){
		$short = substr($title,0,$string_lenght);	
		if (substr_count($short," ") > 1) {
			$lastspace = strrpos($short," ");
			$short = substr($short,0,$lastspace);
		}
		$dots = '...';
	} 
	else { 
		$short = $post->post_title;
		$dots = '';
	}	
	$string = '<a href="'.get_permalink($post->ID).'" title="next post: '.$title.'">';
	$link = str_replace('%title', $short.$dots, $link);
	$link = $pre . $string . $link . '</a>';
	$format = str_replace('%link', $link, $format);
	echo $pre_link.$format;
}
?>