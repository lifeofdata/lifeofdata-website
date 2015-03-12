<?php
/*
Plugin Name: List Attachments Shortcode
Plugin URI: http://plugins.ten-321.com/category/list-attachments/
Description: This plug-in simply adds a new shortcode that allows you to list all of a post's attachments inline.
Version: 0.4.1a
Author: Curtiss Grymala
Author URI: http://ten-321.com/
License: GPL2
*/
/*  Copyright 2011  Curtiss Grymala  (email : curtiss@ten-321.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_shortcode('list-attachments','la_listAttachments');

function la_listAttachments( $atts = array() ) {
	$r = '';
	if( !is_array( $atts ) )
		$atts = array();
	$defaults = array(
		'type' => NULL,
		'orderby' => NULL,
		'groupby' => NULL,
		'order' => NULL,
		'before_list' => '',
		'after_list' => '',
		'opening' => '<ul class="attachment-list">',
		'closing' => '</ul>',
		'before_item' => '<li>',
		'after_item' => '</li>',
		'showsize' => false,
	);
	
	$atts = array_merge( $defaults, $atts );

	if( !empty( $atts['type'] ) ) {
		$types = explode( ',', str_replace( ' ', '', $atts['type'] ) );
	}
	else {
		$types = array();
	}
	
	$showsize = ( $atts['showsize'] == true || $atts['showsize'] == 'true' || $atts['showsize'] == 1 ) ? true : false;
	$upload_dir = wp_upload_dir();
	
	global $post, $wp_query;

	$op = clone $post;
	$oq = clone $wp_query;
	
	foreach( array( 'before_list', 'after_list', 'opening', 'closing', 'before_item', 'after_item' ) as $htmlItem ) {
		$atts[$htmlItem] = str_replace( array( '&lt;', '&gt;' ), array( '<', '>' ), $atts[$htmlItem] );
	}
	
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => -1,
		'post_status' => null,
		'post_parent' => $post->ID,
	);
	if( !empty( $atts['orderby'] ) ) {
		$args['orderby'] = $atts['orderby'];
	}
	if( !empty( $atts['order'] ) ) {
		$atts['order'] = ( in_array( strtolower( $atts['order'] ), array('a','asc','ascending') ) ) ? 'asc' : 'desc';
		$args['order'] = $atts['order'];
	}
	if( !empty( $atts['groupby'] ) ) {
		$args['orderby'] = $atts['groupby'];
	}
	
	$attachments = get_posts($args);
	
	if( $attachments ) {
		$grouper = $atts['groupby'];
		$test = $attachments;
		$test = array_shift( $test );
		if( !property_exists( $test, $grouper ) ) {
			$grouper = 'post_' . $grouper;
		}
		$attlist = array();
		foreach( $attachments as $att ) {
			$key = ( !empty( $atts['groupby'] ) ) ? $att->$grouper : $att->ID;
			$key .= ( !empty( $atts['orderby'] ) ) ? $att->$atts['orderby'] : '';
			
			$attlink = wp_get_attachment_url( $att->ID );
			if( count( $types ) ) {
				foreach( $types as $t ) {
					if( substr( $attlink, (0- strlen( '.' . $t ) ) ) == '.' . $t ) {
						$attlist[ $key ] = clone $att;
						$attlist[ $key ]->attlink = $attlink;
					}
				}
			}
			else {
				$attlist[ $key ] = clone $att;
				$attlist[ $key ]->attlink = $attlink;
			}
		}
		if( $atts['groupby'] ) {
			if( $atts['order'] == 'asc' ) {
				ksort( $attlist );
			}
			else {
				krsort( $attlist );
			}
		}
	}
	
	if( count( $attlist ) ) {
		$open = false;
		$r = $atts['before_list'] . $atts['opening'];
		foreach( $attlist as $att ) {
			if( !empty( $atts['groupby'] ) && $current_group != $att->$grouper ) {
				if( $open ) {
					$r .= $atts['closing'] . $atts['after_item'];
					$open = false;
				}
				$r .= $atts['before_item'] . '<h3>' . $att->$grouper . '</h3>' . $atts['opening'];
				$open = true;
				$current_group = $att->$grouper;
			}
			$attlink = $att->attlink;
			$r .= $atts['before_item'] . '<a href="' . $attlink .'" title="View ' . apply_filters('the_title_attribute',$att->post_title) . '">' . apply_filters('the_title',$att->post_title) . '</a>' . ( ( $showsize ) ? ' <span class="attachment-size">' . la_get_filesize( str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $attlink ) ) . '</span>' : '' ) . $atts['after_item'];
		}
		if( $open ) {
			$r .= $atts['closing'] . $atts['after_item'];
		}
		$r .= $atts['closing'] . $atts['after_list'];
	}
	
	$wp_query = clone $oq;
	$post = clone $op;
	
	return $r;
}

function la_get_filesize( $file ) {
	$bytes = filesize( $file );
	$s = array( 'b', 'Kb', 'Mb', 'Gb' );
	$e = floor( log( $bytes ) / log( 1024 ) );
	return sprintf( '%.2f ' . $s[$e], ( $bytes / pow( 1024, floor( $e ) ) ) );
}
?>