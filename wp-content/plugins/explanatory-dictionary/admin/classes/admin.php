<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */

class Explanatory_Dictionary_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add an action link pointing to the options page.
		add_filter( 'plugin_action_links_' . __FILE__ , array( $this, 'add_action_links' ) );
		
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_menu', array( $this, 'add_old_dictionary_menu' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 4.0.0
	 *       
	 * @return null Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles( $hook ) {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_register_style( Explanatory_Dictionary::$plugin_slug_safe . '-admin-styles', plugins_url( '../assets/css/admin.css', __FILE__ ), array(), Explanatory_Dictionary::VERSION );
		wp_register_style( Explanatory_Dictionary::$plugin_slug_safe . '-qtip', plugins_url( '../../public/assets/js/qtip/jquery.qtip.min.css', __FILE__ ), array(), '2.2.0', false);
		
		$upload_dir = wp_upload_dir();
		$settings_css = $upload_dir['baseurl'] . '/explanatory-dictionary/settings.css';
		wp_register_style( Explanatory_Dictionary::$plugin_slug_safe . '-custom-settings', $settings_css, null, false, false );
	}
	
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 4.0.0
	 *       
	 * @return null Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		wp_register_script( Explanatory_Dictionary::$plugin_slug_safe . '-qtip', plugins_url( '../../public/assets/js/qtip/jquery.qtip.min.js', __FILE__ ), array( 'jquery' ), '2.2.0', true);
		wp_register_script( Explanatory_Dictionary::$plugin_slug_safe . '-admin-script', plugins_url( '../assets/js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker', Explanatory_Dictionary::$plugin_slug_safe . '-qtip' ), Explanatory_Dictionary::VERSION, true );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_submenu_page( 'edit.php?post_type=explandict', __( 'Settings', 'explanatory-dictionary' ), __( 'Settings', 'explanatory-dictionary' ), 'manage_options', 'explanatory-dictionary'.'-settings', array( &$this , 'settings_page' ) );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_old_dictionary_menu() {
		add_submenu_page( 'edit.php?post_type=explandict', __( 'Old data', 'explanatory-dictionary' ), __( 'Old data', 'explanatory-dictionary' ), 'manage_options', 'explanatory-dictionary'.'-old-data', array( &$this , 'old_data_page' ) );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function settings_page() {
		Explanatory_Dictionary_Settings::render_main_page();
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function old_data_page() {
		$this->render_old_page();
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=' . 'explanatory-dictionary'.'-settings' ) . '">' . __( 'Settings', 'explanatory-dictionary' ) . '</a>';

		return $links;
	}
	
	/**
	 * Render a page that shows the data from the old tables in case the import didn't work properly
	 * 
	 * @since 
	 */
	private function render_old_page() {
		if( !isset( $_GET['action'] ) || empty( $_GET['action'] ) ) {
			include_once( plugin_dir_path( __FILE__ ) . '../views/old/admin-list.php' );	
		}
		else if( 'view' == $_GET['action'] ) {
			include_once( plugin_dir_path( __FILE__ ) . '../views/old/admin-view.php' );	
		}
	}
}