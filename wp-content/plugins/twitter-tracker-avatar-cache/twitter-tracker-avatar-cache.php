<?php
/*
Plugin Name: Twitter Tracker: Avatar Cache
Plugin URI: http://simonwheatley.co.uk/wordpress/twitter-tracker-avatar-cache
Description: Caches Twitter avatars used by the Twitter Tracker widgets to avoid using the Twitter API, which sets cookies.
Author: Simon Wheatley
Version: 1.0
Author URI: http://simonwheatley.co.uk/wordpress/
*/

/*  Copyright 2012 Simon Wheatley

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require_once( dirname (__FILE__) . '/plugin.php' );
require_once( dirname (__FILE__) . '/class-Twitter-Avatar-Cache.php' );

/**
 * Hooks the tt_avatar_url and tt_avatar_bigger_url filters provided by Twitter Tracker 
 * to provide the caching service.
 *
 * @param string $avatar_url The avatar URL to cache or provide a cache for
 * @param string $twit_uid The user ID of the twit in question
 * @param int $size The size of the avatar
 * @return string A URL to either a local cache or a mystery icon
 **/
function ttac_avatar_url( $avatar_url, $twit_uid, $size ) {
	// Initiate the caching on the avatar images
	global $tt_avatar;
	if ( ! $_avatar_url = $tt_avatar->get_local_url( $avatar_url ) )	{
		$_avatar_url = get_avatar( "{$twit_uid}@example.com", $size, 'mystery' );
		preg_match( '|src=\'([^\']+)\'|i', $_avatar_url, $matches );
		// error_log( "SW: Matches; " . print_r( $matches, true ) );
		if ( is_array( $matches ) && isset( $matches[ 1 ] ) )
			$_avatar_url = $matches[ 1 ];
		else
			$_avatar_url = '';
	}
	return $_avatar_url;
}
add_filter( 'tt_avatar_url', 'ttac_avatar_url', null, 3 );
add_filter( 'tt_avatar_bigger_url', 'ttac_avatar_url', null, 3 );

?>