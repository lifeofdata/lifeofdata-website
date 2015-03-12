<?php
/**
 * Automatically close Comments, Pingbacks and Trackbacks after certain amount of days.
 *
 * @package AutoClose
 *
 * @wordpress-plugin
 * Plugin Name: Auto-Close Comments, Pingbacks and Trackbacks
 * Version:     1.5
 * Plugin URI:  http://ajaydsouza.com/wordpress/plugins/autoclose/
 * Description: Automatically close Comments, Pingbacks and Trackbacks after certain amount of days.
 * Author:      Ajay D'Souza
 * Author URI:  http://ajaydsouza.com/
 * Text Domain:	twittercounter
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:	/languages
*/

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
 * Holds the filesystem directory path.
 */
define( 'ALD_ACC_DIR', dirname( __FILE__ ) );

/**
 * Set the global variables for autoclose path and URL
 */
$acc_path = plugin_dir_path( __FILE__ );
$acc_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );


/**
 * Initialises text domain for l10n.
 */
function ald_acc_lang_init() {
	load_plugin_textdomain( 'autoclose', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ald_acc_lang_init' );


/**
 * Main function.
 */
function ald_acc() {
    global $wpdb;
    $poststable = $wpdb->posts;
	$acc_settings = acc_read_options();

    $comment_age = $acc_settings['comment_age']. ' DAY';
    $pbtb_age = $acc_settings['pbtb_age']. ' DAY';
    $comment_pids = $acc_settings['comment_pids'];
    $pbtb_pids = $acc_settings['pbtb_pids'];

	// Get the post types
	parse_str( $acc_settings['comment_post_types'], $comment_post_types );	// Save post types in $comment_post_types variable
	parse_str( $acc_settings['pbtb_post_types'], $pbtb_post_types );	// Save post types in $comment_post_types variable

	// What is the time now?
	$now = gmdate( "Y-m-d H:i:s", ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );

	// Get the date up to which comments and pings will be closed
	$comment_age = $comment_age - 1;
	$comment_date = strtotime( '-' . $comment_age . ' DAY' , strtotime( $now ) );
	$comment_date = date( 'Y-m-d H:i:s' , $comment_date );

	$pbtb_age = $pbtb_age - 1;
	$pbtb_date = strtotime( '-' . $pbtb_age . ' DAY' , strtotime( $now ) );
	$pbtb_date = date( 'Y-m-d H:i:s' , $pbtb_date );

	// Close Comments on posts
	if ( $acc_settings['close_comment'] ) {
		// Prepare the query
		$args = array(
			$comment_date,
		);
		$sql = "
				UPDATE $poststable
				SET comment_status = 'closed'
				WHERE comment_status = 'open'
				AND post_status = 'publish'
				AND post_date < '%s'
		";
		$sql .= " AND ( ";
		$multiple = false;
		foreach ( $comment_post_types as $post_type ) {
			if ( $multiple ) $sql .= ' OR ';
			$sql .= " post_type = '%s'";
			$multiple = true;
			$args[] = $post_type;	// Add the post types to the $args array
		}
		$sql .= " ) ";

		$results = $wpdb->get_results( $wpdb->prepare( $sql, $args ) );
	}

	// Close Pingbacks/Trackbacks on posts
	if ( $acc_settings['close_pbtb'] ) {
		// Prepare the query
		$args = array(
			$pbtb_date,
		);
		$sql = "
				UPDATE $poststable
				SET ping_status = 'closed'
				WHERE ping_status = 'open'
				AND post_status = 'publish'
				AND post_date < '%s'
		";
		$sql .= " AND ( ";
		$multiple = false;
		foreach ( $pbtb_post_types as $post_type ) {
			if ( $multiple ) $sql .= ' OR ';
			$sql .= " post_type = '%s'";
			$multiple = true;
			$args[] = $post_type;	// Add the post types to the $args array
		}
		$sql .= " ) ";

		$results = $wpdb->get_results( $wpdb->prepare( $sql, $args ) );
	}

	// Open Comments on these posts
	if ( '' != $acc_settings['comment_pids'] ) {
		$wpdb->query( "
			UPDATE $poststable
			SET comment_status = 'open'
			WHERE comment_status = 'closed'
			AND post_status = 'publish'
			AND ID IN ($comment_pids)
		" );
	}

	// Open Pingbacks / Trackbacks on these posts
	if ( '' != $acc_settings['pbtb_pids'] ) {
		$wpdb->query( "
			UPDATE $poststable
			SET ping_status = 'open'
			WHERE ping_status = 'closed'
			AND post_status = 'publish'
			AND ID IN ($pbtb_pids)
		" );
	}

	// Delete Post Revisions (WordPress 2.6 and above)
	if ( $acc_settings['delete_revisions'] ) {
		$wpdb->query( "
			DELETE FROM $poststable
			WHERE post_type = 'revision'
		" );
	}
}
add_action( 'ald_acc_hook', 'ald_acc' );


/**
 * Default options.
 *
 * @return array Default settings
 */
function acc_default_options() {

	$comment_post_types	= http_build_query( array( 'post' => 'post' ), '', '&' );
	$pbtb_post_types = $comment_post_types;

	$acc_settings = array (
		'comment_age' => '90',	// Close comments before these many days
		'pbtb_age' => '90',		// Close pingbacks/trackbacks before these many days
		'comment_pids' => '',	// Comments on these Post IDs to open
		'pbtb_pids' => '',		// Pingback on these Post IDs to open
		'close_comment' => false,	// Close Comments on posts
		'close_comment_pages' => false,	// Close Comments on pages
		'close_pbtb' => false,		// Close Pingbacks and Trackbacks on posts
		'close_pbtb_pages' => false,		// Close Pingbacks and Trackbacks on pages
		'delete_revisions' => false,		// Delete post revisions
		'daily_run' => false,		// Run Daily?
		'cron_hour' => '0',		// Cron Hour
		'cron_min' => '0',		// Cron Minute
		'comment_post_types' => $comment_post_types,		// WordPress custom post types
		'pbtb_post_types' => $pbtb_post_types,		// WordPress custom post types
	);

	return apply_filters( 'acc_default_options', $acc_settings );
}


/**
 * Function to read options from the database.
 *
 * @return array Options for the database. Will add any missing options.
 */
function acc_read_options() {
	$acc_settings_changed = false;

	$defaults = acc_default_options();

	$acc_settings = array_map( 'stripslashes', (array)get_option( 'ald_acc_settings' ) );
	unset( $acc_settings[0] ); // produced by the (array) casting when there's nothing in the DB

	foreach ( $defaults as $k=>$v ) {
		if ( ! isset( $acc_settings[$k] ) ) {
			$acc_settings[$k] = $v;
		}
		$acc_settings_changed = true;
	}
	if ( true == $acc_settings_changed ) {
		update_option( 'ald_acc_settings', $acc_settings );
	}

	return apply_filters( 'acc_read_options', $acc_settings );
}


/**
 * Function to enable run or actions.
 *
 * @param int $hour Hour
 * @param int $min Min
 */
function acc_enable_run( $hour, $min ) {
	if ( ! wp_next_scheduled( 'ald_acc_hook' ) ) {
		wp_schedule_event( mktime( $hour, $min, 0, date( "n" ), date( "j" ) + 1, date( "Y" ) ), 'daily', 'ald_acc_hook' );
	} else {
		wp_clear_scheduled_hook( 'ald_acc_hook' );
		wp_schedule_event( mktime( $hour, $min, 0, date( "n" ), date( "j" ) + 1, date( "Y" ) ), 'daily', 'ald_acc_hook' );
	}
}


/**
 * Function to disable daily run or actions.
 */
function acc_disable_run() {
	if ( wp_next_scheduled( 'ald_acc_hook' ) ) {
		wp_clear_scheduled_hook( 'ald_acc_hook' );
	}
}


// Process the admin page if we're on the admin screen
if ( is_admin() || strstr( $_SERVER['PHP_SELF'], 'wp-admin/' ) ) {
	require_once( ALD_ACC_DIR . "/admin.inc.php" );

	/**
	 * Filter to add link to WordPress plugin action links.
	 *
	 * @param array $links
	 * @return array
	 */
	function acc_plugin_actions_links( $links ) {

		return array_merge( array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=acc_options' ) . '">' . __( 'Settings', 'autoclose' ) . '</a>'
			), $links );

	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'acc_plugin_actions_links' );

	/**
	 * Filter to add links to the plugin action row.
	 *
	 * @param array $links
	 * @param array $file
	 */
	function acc_plugin_actions( $links, $file ) {
		static $plugin;
		if ( ! $plugin ) $plugin = plugin_basename( __FILE__ );

		// create link
		if ( $file == $plugin ) {
			$links[] = '<a href="http://wordpress.org/support/plugin/autoclose">' . __( 'Support', 'autoclose' ) . '</a>';
			$links[] = '<a href="http://ajaydsouza.com/donate/">' . __( 'Donate', 'autoclose' ) . '</a>';
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'acc_plugin_actions', 10, 2 ); // only 2.8 and higher

} // End admin.inc

?>