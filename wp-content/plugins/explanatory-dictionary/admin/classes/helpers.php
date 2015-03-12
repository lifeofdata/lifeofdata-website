<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */

class Explanatory_Dictionary_Helpers {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	public static $instance = null;

	const SETTINGS_SUCCESS_MESSAGE = 1;
	const SETTINGS_ERROR_MESSAGE = 2;
	const SETTINGS_RESET_MESSAGE = 3; 
	const UPLOAD_DIR_NOT_WRITABLE = 4; 
	const SYNONYMS_ERROR_IN_TITLE = 20;
	const SYNONYMS_ERROR_IN_SYNONYM = 21;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		add_action( 'admin_init', array( 
				$this, 'check_plugin_version' 
		) );
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
	
	public function check_plugin_version() {
		
		if ( false === get_option( Explanatory_Dictionary::$plugin_slug_safe . '_version' ) ) {
			/*$this->_update_from_below_3_0();
			$this->_update_from_3_0_2();
			$this->_update_from_4_0_2();*/
			
			Explanatory_Dictionary_Settings::reset_all_settings();
			
			update_option( Explanatory_Dictionary::$plugin_slug_safe . '_version', Explanatory_Dictionary::VERSION);
			
		} else if ( get_option( Explanatory_Dictionary::$plugin_slug_safe . '_version' ) < Explanatory_Dictionary::VERSION ) {
			$version = get_option( Explanatory_Dictionary::$plugin_slug_safe . '_version' );
			
			if( '4.1.1' <= $version && '4.1.5' > $version ) {
				$this->_update_to_4_1_5();
			}
			else if( '4.1.0' <= $version && '4.1.5' < $version ) {
				$this->_update_from_4_1_0();
				$this->_update_from_4_1_4();
			} else if( '4.0.2' == $version ) {
				$this->_update_from_4_0_2();
			} else if ( '3.0.2' == $version ) {
				$this->_update_from_3_0_2();
				$this->_update_from_4_0_2();
			} else if ( '3.0' > $version ) {
				$this->_update_from_below_3_0();
				$this->_update_from_3_0_2();
				$this->_update_from_4_0_2();
			} else {
				delete_option( Explanatory_Dictionary::$plugin_slug_safe . 'settings');
				
				Explanatory_Dictionary_Settings::reset_all_settings();
			}
		}
		
		update_option( Explanatory_Dictionary::$plugin_slug_safe . '_version', Explanatory_Dictionary::VERSION);
	}
	
	private function _update_from_below_3_0() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		$wpdb->query("
				ALTER TABLE {$table_name}
				ADD `synonyms_and_forms` TEXT NOT NULL AFTER `word`
		");
				
		Explanatory_Dictionary_Settings::reset_all_settings();
	}
	
	private function _update_from_3_0_2() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		$wpdb->query("
			ALTER TABLE {$table_name}
			ADD `status` int(1) NOT NULL DEFAULT 1
		");
		
		$this->update_synonyms();
				
		Explanatory_Dictionary_Settings::reset_all_settings();
	}
	
	private function _update_from_4_0_2() {
		self::migrate_old_data();
		
		Explanatory_Dictionary_Settings::reset_all_settings();
	}
	
	private function _update_from_4_1_0() {
		// Fix the typo-ed directory
		$upload_dir = wp_upload_dir();
		
		if ( !is_writable( $upload_dir['path'] ) ) {
			return;
		}
		
		$dir = $upload_dir['basedir'] . '/explanatory-dictionary';
		$old_settings_css = $upload_dir['basedir'] . '/explanatory-doctionary/settings.css';
		if( !is_dir( $dir ) && file_exists( $old_settings_css ) ) {
			rename( $upload_dir['basedir'] . '/explanatory-doctionary', $dir);
		}
		
		// add the new settings
		Explanatory_Dictionary_Settings::load_user_settings();
		Explanatory_Dictionary_Settings::update_settings_list( array( '_custom_word_styling' => 'no' ) );
		Explanatory_Dictionary_Settings::update_settings();
	}
	
	private function _update_to_4_1_5() {		
		// add the new settings
		Explanatory_Dictionary_Settings::load_user_settings();
		Explanatory_Dictionary_Settings::update_settings_list( array( '_heavy_search' => 'no' ) );
		Explanatory_Dictionary_Settings::update_settings();
	}
	
	/**
	 * Trim function
	 *
	 * @since 4.0.0
	 * @param string $v The string that needs trimming
	 */
	private function trim( &$v ) {
		$v = trim( $v );
	}
	
	/**
	 * Serialize the old synonyms
	 *
	 * @since 4.0.0
	 */
	function update_synonyms() {
		global $wpdb;
		$table_name = $table_name = $wpdb->prefix . 'explanatory_dictionary';
	
		foreach( $this->get_all_entries() as $entry ) {
			$arrasy_synonyms = array();
			if( !empty( $entry->synonyms_and_forms ) ) {
				$array_synonyms = explode(',', $entry->synonyms_and_forms );
				array_walk( $array_synonyms, array( 'self' , 'trim' ) );
				$array_synonyms = array_unique( $array_synonyms );
			}
			$update = $wpdb->update( $table_name, array(
					'word' => stripcslashes( $entry->word ), 'synonyms_and_forms' => maybe_serialize( $array_synonyms ), 'explanation' => stripcslashes( $entry->explanation ), 'status' => 1
			), array(
					'id' => $entry->id
			), array(
					'%s', '%s', '%s', '%d'
			), array(
					'%d'
			) );
		}
	}
	
	public static function migrate_old_data() {
		global $wpdb;
		$table_name = $table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$words = $wpdb->get_results("
			SELECT * 
			FROM {$table_name}
		");
		
		foreach( $words as $word ) {
			$my_post = array(
				'post_title'    => $word->word,
				'post_content'  => $word->explanation,
				'post_status'   => $word->status == 1 ? 'publish' : 'pending',
				'post_author'   => 1,
				'post_type' 	=> Explanatory_Dictionary_PostType::$post_type
			);
			
			$slug = sanitize_title( $word->word );
				
			$posts = get_posts( array(
				'name' => $slug,
				'post_type' => Explanatory_Dictionary_PostType::$post_type,
				'post_status' => array( 
					'any'
				)
			) );
			
			if( empty( $posts ) ) {
				// Insert the post into the database
				$post_id = wp_insert_post( $my_post );
				
				// now add the synonyms as meta data
				$word->synonyms_and_forms = maybe_unserialize( $word->synonyms_and_forms );
				if( is_array( $word->synonyms_and_forms ) ) {
					$word->synonyms_and_forms = implode( ', ', $word->synonyms_and_forms );
				}
					
				add_post_meta( $post_id, Explanatory_Dictionary::$plugin_slug_safe . '_synonyms', sanitize_text_field( $word->synonyms_and_forms ) );
			}
		}
	}

	/*----------------------------------------------------------------------------*
	 * VIEW functions
	 *----------------------------------------------------------------------------*/

	/**
	 * A function for displaying messages in the admin.  It will wrap the message in the appropriate <div> with the
	 * custom class entered. The updated class will be added if no $class is given.
	 *
	 * @since 1.0.0
	 * @param string $class Class the <div> should have.
	 * @param string $message The text that should be displayed.
	 */
	public static function admin_message( $message = '', $status ) {
		switch($status){
			case 'update':
				echo '<div class="updated admin-message"><p>' . $message . '</p></div>';
				break;
			case 'error':
				echo '<div class="error"><p>' . $message . '</p></div>';
				break;
		}
	}


	/*----------------------------------------------------------------------------*
	 * GET functions
	 *----------------------------------------------------------------------------*/

	/**
	 * This is to help with securely making sure forms have been processed from the correct place.
	 *
	 * @since 1.0.0
	 * @param string $action Additional action to add to the nonce.
	 * 
	 * @return string The nonce name
	 */
	public static function get_nonce( $action = '' ) {
		if ( $action ) {
			return Explanatory_Dictionary::$plugin_slug . "-component-action_{$action}";
		} else {
			return Explanatory_Dictionary::$plugin_slug . "-plugin";
		}
	}
	
	public static function get_message( $id ) {
		
		switch ( $id ) {
			case self::SETTINGS_SUCCESS_MESSAGE:
				self::admin_message( __( 'Tooltip settings updated', 'explanatory-dictionary'), 'update' );
				break;
			case self::SETTINGS_ERROR_MESSAGE:
				self::admin_message( __( 'Error updating the settings', 'explanatory-dictionary'), 'error' );
				break;
			case self::SETTINGS_RESET_MESSAGE:
				self::admin_message( __( 'Tooltip settings have been reset', 'explanatory-dictionary'), 'update' );
				break;
			case self::UPLOAD_DIR_NOT_WRITABLE:
				self::admin_message( __( 'Your upload directory is not writable, please check your file permissions', 'explanatory-dictionary'), 'error' );
				break;
			case self::SYNONYMS_ERROR_IN_TITLE:
				self::admin_message( __( 'Error updating synonyms, one or more already exists as a term', 'explanatory-dictionary'), 'error' );
				break;
			case self::SYNONYMS_ERROR_IN_SYNONYM:
				self::admin_message( __( 'Error updating synonyms, one or more already exists as another synonym', 'explanatory-dictionary'), 'error' );
				break;
		}
	}
	
	public static function is_selected( $setting, $value ) {
		$settings = Explanatory_Dictionary_Settings::get_settings_list();
		
		if( $settings[$setting] == $value ) {
			return 'selected="selected"';
		}
	}
	
	public static function is_checked( $setting, $value = 'yes' ) {
		$settings = Explanatory_Dictionary_Settings::get_settings_list();
		if( $settings[$setting] == $value ) {
			return 'checked="checked"';
		}
	}


	/*----------------------------------------------------------------------------*
	 * SET functions
	 *----------------------------------------------------------------------------*/


	/**
	 * This is to help handeling options and save them.
	 *
	 * @since 1.0.0
	 * @param array $settings array with the settings, $key as name $value as value.
	 * 
	 */
	public static function handle_options( $post_settings, $tab_options ) {
		
		foreach ( $post_settings as $key => $post_setting ) {
			if( is_array($post_setting ) ) {
				$post_settings[$key] = $post_setting;
			} else {
				$post_settings[$key] = trim( esc_html( $post_setting ) );
			}
		}
		
		foreach( $tab_options as $setting ) {
			if( is_callable( array('Explanatory_Dictionary_Settings_Validation', 'validate' . $setting ), false, $method ) ) {
				try {
					call_user_func( $method, array(&$post_settings) );
				} catch (Exception $e) {
					
				}
			} else {
				if( WP_DEBUG ) {
					var_dump('validate' . $setting);
				}
			}
		}
		
		Explanatory_Dictionary_Settings::update_settings_list($post_settings);
		Explanatory_Dictionary_Settings::update_settings();
	}

	/*----------------------------------------------------------------------------*
	 * DELETE functions
	 *----------------------------------------------------------------------------*/

}