<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 *
 * @wordpress-plugin
 * Plugin Name: Explanatory Dictionary
 * Plugin URI:  
 * Description: Add a dictionary to your wordpress site
 * Version:     4.1.5
 * Author:      EXED internet (RJvD, BHdH)
 * Author URI:  http://www.mixcom.nl/online
 * Text Domain: explanatory-dictionary
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

/*  
Copyright 2014  EXED internet  (email : service@exed.nl)

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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/classes/default.php' );
add_action( 'plugins_loaded', array( 'Explanatory_Dictionary', 'get_instance' ) );

require_once( plugin_dir_path( __FILE__ ) . 'admin/classes/admin.php' );
add_action( 'plugins_loaded', array( 'Explanatory_Dictionary_Admin', 'get_instance' ) );

require_once( plugin_dir_path( __FILE__ ) . 'admin/classes/settings.php' );
add_action( 'plugins_loaded', array( 'Explanatory_Dictionary_Settings', 'get_instance' ) );

require_once( plugin_dir_path( __FILE__ ) . 'admin/classes/settings-validations.php' );
add_action( 'plugins_loaded', array( 'Explanatory_Dictionary_Settings_Validation', 'get_instance' ) );

require_once( plugin_dir_path( __FILE__ ) . 'admin/classes/helpers.php' );
add_action( 'plugins_loaded', array( 'Explanatory_Dictionary_Helpers', 'get_instance' ) );

// require and initialize the post type class
require_once( plugin_dir_path( __FILE__ ) . 'public/classes/explandict-post-type.php' );
add_action( 'plugins_loaded', array( 'Explanatory_Dictionary_PostType', 'get_instance' ) );

// On enabeling the plugin we need to fix a couple of things so run the following method
register_activation_hook(__FILE__, 'Explanatory_Dictionary::on_activate');
