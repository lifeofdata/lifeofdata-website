<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/classes/default.php' );
if( Explanatory_Dictionary::VERSION < '4.1.5') {
	global $wpdb;
	$table_name = $wpdb->prefix . "dictionary_list";
	$sql = "
		DROP TABLE `{$table_name}`
	";
		
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}