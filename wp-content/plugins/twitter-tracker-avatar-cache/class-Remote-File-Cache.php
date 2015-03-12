<?php

/*  Copyright 2012 Code for the People Ltd

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

/**
* 
*/
class CFTP_Remote_File_Cache extends TwitterTrackerAvatarCache_Plugin {
	
	/**
	 * Group name for all items cached by a particular plugin, 
	 * so we can easily delete when the plugin is deactivated.
	 *
	 * @var string
	 **/
	public $group_name;

	/**
	 * The desired cache expiry, in seconds, used to determine 
	 * how frequently the cache is kept up to date.
	 *
	 * @var int
	 **/
	public $cache_expiry;

	/**
	 * A version for upgrading the DB, rewrite rules, cron jobs, etc
	 *
	 * @var int
	 **/
	public $version;

	/**
	 * Let's get this show on the road.
	 *
	 * @return void
	 **/
	public function __construct() {
		$this->add_action( 'after_setup_theme', 'init', 5 );
		$this->add_action( 'after_setup_theme', 'setup' );
		$this->add_action( 'after_setup_theme', 'maybe_upgrade', 15 );
		$this->add_action( 'cftp_cache', 'cache', null, 2 );
		$this->add_action( 'cftp_expire_cache', 'expire_cache' );
		$this->add_filter( 'cron_schedules' );
		
		$this->version = 3;
	}
	
	/**
	 * Hooks the plugins_loaded action, the child class MUST
	 * override this method.
	 *
	 * @return void
	 **/
	public function setup() {
		throw new exception( 'CFTP Cache: You must override the setup method in the child class' );
	}

	/**
	 * Hooks the cron_schedules filter to add our schedule
	 *
	 * @param array $schedules The Cron schedules, e.g. index: 'hourly' => array( 'interval' => 3600, 'display' => 'Once Hourly' )
	 * @return array The Cron schedules
	 **/
	public function cron_schedules( $schedules ) {
		$schedules[ 'cftp_expire_cache' ] = array( 'interval' => $this->cache_expiry / 2, 'display' => 'A half of the cache expiry' );
		return $schedules;
	}

	/**
	 * Hooks the WP init action to create the custom post type
	 * we use to store the caching information.
	 *
	 * @return void
	 **/
	public function init() {
		$args = array(
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'capability_type' => 'no_meddling',
			'rewrite' => false,
			'has_archive' => false,
			'query_var' => false,
			'show_ui' => false,
			'can_export' => false,
			'show_in_nav_menus' => false,
			'show_in_menu' => false,
			'show_in_admin_bar' => false,
		);
		register_post_type( 'cftp_cache', $args );
		$args = array(
			// 'update_count_callback' => array( & $this, 'do_nothing' ),
			'rewrite' => false,
			'query_var' => false,
			'public' => false,
			'show_ui' => false,
			'show_tagcloud' => false,
			'show_in_nav_menus' => false,
		);
		register_taxonomy( 'cftp_cache_group', array( 'cftp_cache' ), $args );
	}

	/**
	 * Hooks the Cron action cftp_expire_cache which occurs at twice the
	 * frequency of the cache expiry, i.e. if the cache expires in an hour
	 * this cron action runs twice an hour.
	 *
	 * @return void
	 **/
	public function expire_cache() {
		global $wpdb;
		$now = current_time( 'timestamp', true );
		$expiry = gmdate( 'Y-m-d H:i:s', ( time() - $this->cache_expiry ) );
		$sql = " SELECT ID FROM $wpdb->posts WHERE post_type = 'cftp_cache' AND post_status = 'publish' AND post_date_gmt < %s ";
		$post_ids = $wpdb->get_col( $wpdb->prepare( $sql, $expiry ) );
		foreach ( $post_ids as $post_id ) {
			if ( is_object_in_term( $post_id, 'cftp_cache_group', $this->cache_group ) )
				$this->expire_cache_by_id( $post_id );
		}
		// // If the max cache count is set to false or zero (0) then don't trim at all
		$cache_count = $this->cache_count();
		if ( ! $this->max_cache_count ) {
			return;
		}
		// // Now trim any excess cache entries for this plugin
		if ( $cache_count < $this->max_cache_count ) {
			return;
		}
		// How many excess items are there?
		$excess_count = (int) $cache_count - $this->max_cache_count;
		$cache_group_term = get_term_by( 'name', $this->cache_group, 'cftp_cache_group' );
		$cache_item_ids = get_objects_in_term( $cache_group_term->term_id, 'cftp_cache_group' );
		// Sanitise all IDs so they are definitely integers
		$cache_item_ids = array_map( 'absint', $cache_item_ids );
		$cache_item_ids = join( ',', $cache_item_ids );
		$sql = " SELECT ID FROM $wpdb->posts WHERE post_type = 'cftp_cache' AND ID in ( $cache_item_ids ) ORDER BY post_date ASC LIMIT %d ";
		$post_ids = $wpdb->get_col( $wpdb->prepare( $sql, $excess_count ) );
		foreach ( $post_ids as $post_id ) {
			$this->delete_cache_by_id( $post_id );
		}
	}

	/**
	 * Queue a URL to be cached, also refreshes the cache of an 
	 * already cached resource.
	 *
	 * @param string $remote_url The URL of the resource to cache 
	 * @return boolean True if the request has been successfully queued
	 **/
	public function queue( $remote_url ) {
		wp_schedule_single_event( time(), 'cftp_cache', array( $remote_url, $this->cache_group ) );
	}

	/**
	 * Retrieves and caches the remote resource, or refreshes the last used date of 
	 * the local/cached resource.
	 *
	 * @param string $remote_url The URL of the resource to cache 
	 * @param string $group_name The name of the group to assign this cache to, one group per cache
	 * @return void
	 **/
	public function cache( $remote_url, $group_name ) {
		// return;
		set_time_limit( 60*1 ); // Try to push the execution time out
		$this->cache_group = $group_name;
		// If we already have a cache, then just refresh it
		if ( $post_id = $this->get_cache_id( $remote_url ) ) {
			$post = get_post( $post_id );
			// If the cache is past expiry, queue it for refresh
			$cache_timestamp = mysql2date( 'U', $post->post_date_gmt );
			$now_timestamp = current_time( 'timestamp', true );
			// The cache is fresh enough, no need for action
			if ( ( $now_timestamp - $cache_timestamp ) < $this->cache_expiry )
				return;
			$local_file = get_post_meta( $post_id, '_cftp_cache_local_file', true );
		} else {
			$location = $remote_url;
			$response = wp_remote_head( $location, array( 'redirect' => 10 ) );
			if ( is_wp_error( $response ) ) {
				error_log( "CFTP Cache error getting headers for $remote_url: " . print_r( $response, true ) );
				return;
			}
			// Follow the first redirection
			if ( 301 == $response[ 'response' ][ 'code' ] || 302 == $response[ 'response' ][ 'code' ] ) {
				$location = $response[ 'headers' ][ 'location' ];
				$response = wp_remote_head( $location, array( 'redirect' => 10 ) );
				if ( is_wp_error( $response ) ) {
					error_log( "CFTP Cache error getting headers for $location: " . print_r( $response, true ) );
					return;
				}
			}
			if ( 403 == $response[ 'response' ][ 'code' ] ) {
				// User could have been suspended or whatever, just abandon the attempt
				// no need to log this.
				return;
			}
			if ( 200 != $response[ 'response' ][ 'code' ] ) {
				error_log( "CFTP Cache problem getting headers for $location, maybe tried one optional redirect, got this: " . print_r( $response, true ) );
				return;
			}
			$filename = md5( $remote_url );
			$mimetype = $response[ 'headers' ][ 'content-type' ];
			// $path = parse_url( $redirected_url, PHP_URL_PATH );
			// error_log( "SW: Path: $path from $redirected_url" );
			// preg_match( '|[^\.]+$|i', $path, $matches );
			if ( ! isset( $mimetype ) ) {
				error_log( "CFTP Cache: Something has gone a bit weird, we couldn't find the content type for this resource: $remote_url." );
				return;
			}
			$mime_to_extension = array(
				'image/jpeg' => 'jpg',
				'image/gif' => 'gif',
				'image/png' => 'png',
			);
			if ( ! isset( $mime_to_extension[ $mimetype ] ) ) {
				error_log( "CFTP Cache: Cannot recognise the mimetype $mimetype." );
				return;
			}
			$extension = $mime_to_extension[ $mimetype ];
			$local_file = $this->cache_filepath( "$filename.$extension" );
			$local_url = $this->cache_url( "$filename.$extension" );
		}

		require_once( ABSPATH . '/wp-admin/includes/file.php' );
		
		// START download_url
		if ( ! $remote_url ) {
			error_log( "CFTP Cache: Invalid URL Provided." );
			return;
		}

		// SW: Change from download_url
		$tmpfname = "$local_file.cftp_cache_tmp";
		if ( ! $tmpfname ) {
			error_log( "CFTP Cache: Could not create Temporary file." );
			return;
		}

		$response = wp_remote_get( $remote_url, array( 'timeout' => 300, 'stream' => true, 'filename' => $tmpfname ) );

		if ( is_wp_error( $response ) ) {
			unlink( $tmpfname );
			error_log( "CFTP Cache: Problem downloading file: " . $response->get_error_message() );
			return;
		}

		if ( 200 != wp_remote_retrieve_response_code( $response ) ){
			unlink( $tmpfname );
			error_log( "CFTP Cache: Problem with response to download request: " . trim( wp_remote_retrieve_response_message( $response ) ) );
			return;
		}
		// END download_url
		
		rename( $tmpfname, $local_file );
		// error_log( "SW: Renaming $tmpfname > $local_file for $remote_url" );
		$post_data = array(
			'post_title'    => $remote_url,
			'post_type'     => 'cftp_cache',
			'post_status'   => 'publish',
			'post_category' => array(),
			'post_date_gmt' => current_time( 'mysql', true ),
			'guid'          => $remote_url,
		);
		if ( isset( $post_id ) && $post_id ) {
			$post_data[ 'ID' ] = $post_id;
			wp_update_post( $post_data );
		} else {
			$post_id = wp_insert_post( $post_data );
			wp_set_object_terms( $post_id, $this->cache_group, 'cftp_cache_group' );
			update_post_meta( $post_id, '_cftp_cache_local_url', $local_url );
		}
		update_post_meta( $post_id, '_cftp_cache_local_file', $local_file );
	}

	/**
	 * Returns the local URL for the cached resource, or false if no cache exists.
	 *
	 * @param string $remote_url The URL of the resource we want the local URL for
	 * @return string|boolean Either the local URL for the resource, or false if we have no cache yet
	 **/
	public function get_local_url( $remote_url ) {
		// error_log( "SW: Get local URL for $remote_url" );
		if ( ! $post_id = $this->get_cache_id( $remote_url ) ) {
			$this->queue( $remote_url );
			return false;
		}
		// If the local URL for that cache is somehow missing, we're screwed
		if ( ! $local_url = get_post_meta( $post_id, '_cftp_cache_local_url', true ) ) {
			// Clean up, then re-cache
			$this->delete_cache_by_id( $post_id );
			$this->queue( $remote_url );
			return false;
		}
		// If the local filepath for that cache is somehow missing, we're also screwed
		if ( ! $local_file = get_post_meta( $post_id, '_cftp_cache_local_file', true ) ) {
			// Clean up, then re-cache
			$this->delete_cache_by_id( $post_id );
			$this->queue( $remote_url );
			return false;
		}
		// If the local file for that cache is non-existent or unreadable, we're still screwed
		if ( ! is_readable( $local_file ) ) {
			$this->delete_cache_by_id( $post_id );
			$this->queue( $remote_url );
			return false;
		}
		return $local_url;
	}

	/**
	 * Returns the local URL for the cached resource, or false if no cache exists.
	 *
	 * @param string $remote_url The URL of the resource we want the local URL for
	 * @return string|boolean Either the local URL for the resource, or false if we have no cache yet
	 **/
	public function get_cache_id( $remote_url ) {
		global $wpdb;
		// Get any cache for this remote URL, current or expired
		$sql = " SELECT ID, post_status FROM $wpdb->posts WHERE guid = %s AND post_type = 'cftp_cache' ORDER BY post_date DESC LIMIT 1 ";
		if ( $cache = $wpdb->get_row( $wpdb->prepare( $sql, $remote_url ) ) ) {
			// Check if the cache is current, which is the simplest most
			// desirable situation.
			if ( 'publish' == $cache->post_status )
				return $cache->ID;
			// The cache is not current, queue it for refresh but return 
			// the file "as is"
			$this->queue( $remote_url );
			return $cache->ID;
		}
		return false;
	}

	/**
	 * Expires a cache as identified by it's cache tracker post.
	 *
	 * @param int $post_id The ID of the cache tracker whose cache is to be deleted 
	 * @return boolean True if it worked, otherwise false
	 **/
	public function expire_cache_by_id( $post_id ) {
		$post_data = array(
			'ID' => $post_id,
			'post_status' => 'draft', // Abusing the draft status
		);
		wp_update_post( $post_data );
	}
 
	/**
	 * Deletes a cache as identified by it's cache tracker post.
	 *
	 * @param int $post_id The ID of the cache tracker whose cache is to be deleted 
	 * @return boolean True if it worked, otherwise false
	 **/
	public function delete_cache_by_id( $post_id ) {
		$local_file = get_post_meta( $post_id, '_cftp_cache_local_file', true );
		if ( $local_file && is_file( $local_file ) ) {
			@unlink( $local_file );
		}
		wp_delete_post( $post_id, true );
	}

	/**
	 * Count the items in this cache group.
	 *
	 * @return int The number of items in this cache group
	 **/
	public function cache_count() {
		$term = get_term_by( 'slug', $this->cache_group, 'cftp_cache_group' );
		if ( ! $term )
			return 0;
		wp_update_term_count_now( array( $term->term_taxonomy_id ), 'cftp_cache_group' );
		$term = get_term_by( 'slug', $this->cache_group, 'cftp_cache_group' );
		return $term->count;
	}

	/**
	 * Return the system filepath to the file (if provided)
	 * in the cache directory.
	 *
	 * @param string $file The name of the file within the directory (optional)
	 * @return A path in the filesystem
	 **/
	public function cache_filepath( $file = '' ) {
		$bits = wp_upload_dir();
		extract( $bits );
		// Attempt to handle Windows
		$ds = defined( 'DIRECTORY_SEPARATOR' ) ? DIRECTORY_SEPARATOR : '\\';
		if ( ! is_dir( $basedir . $ds . 'cftp-cache' ) )
			mkdir( $basedir . $ds . 'cftp-cache' );
		return $basedir . $ds . 'cftp-cache' . $ds . $file;
	}

	/**
	 * Return the URL to the file (if provided)
	 * in the cache directory.
	 *
	 * @param string $file The name of the file within the directory (optional)
	 * @return string An URL
	 **/
	public function cache_url( $file = '' ) {
		$bits = wp_upload_dir();
		extract( $bits );
		return  "$baseurl/cftp-cache/$file";
	}

	/**
	 * Does nothing, used as a callback when nothing is needed
	 * from the callback.
	 *
	 * @return void
	 **/
	public function do_nothing() {
		// Nothing, nada, zip…
	}
	/**
	 * Checks the DB structure is up to date, rewrite rules, 
	 * theme image size options are set, etc.
	 *
	 * @return void
	 **/
	public function maybe_upgrade() {
		global $wpdb;
		$option_name = 'cftp_remote_file_cache_' . $this->group_name;
		$version = get_option( $option_name, 0 );

		if ( ! is_admin() )
			return;

		if ( $version == $this->version )
			return;

		if ( $start_time = get_option( "{$option_name}_running", false ) ) {
			$time_diff = time() - $start_time;
			// Check the lock is less than 30 mins old, and if it is, bail
			if ( $time_diff < ( 60 * 30 ) ) {
				error_log( "CFTP Remote File Cache TT: Existing update routine has been running for less than 30 minutes" );
				return;
			}
			error_log( "CFTP Remote File Cache TT: Update routine is running, but older than 30 minutes; going ahead regardless" );
		} else {
			add_option( "{$option_name}_running", time(), null, 'no' );
		}

		if ( $version < 3 ) {
			// Create the cache expiry
			wp_clear_scheduled_hook( 'cftp_expire_cache' );
			wp_schedule_event( time() + $this->cache_expiry, 'cftp_expire_cache', 'cftp_expire_cache' );
			error_log( "CFTP Remote File Cache TT: Set cron job" );
		}

		// N.B. Remember to increment $this->version above when you add a new IF

		update_option( $option_name, $this->version );
		delete_option( "{$option_name}_running", true, null, 'no' );
		error_log( "CFTP Remote File Cache TT: Done upgrade, now at version " . $this->version );
	}

}



?>