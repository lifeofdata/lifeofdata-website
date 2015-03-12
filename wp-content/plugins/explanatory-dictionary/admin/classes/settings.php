<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */

/**
 * Plugin class.
 *
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 */
class Explanatory_Dictionary_Settings {

	// tooltip settings
	protected static $exclude = array();
	protected static $limit = -1;
	protected static $max_width = '350';
	protected static $min_width = '200';
	
	// tooltip styling
	protected static $external_css_file = 'no';
	protected static $theme = 'qtip-default';
	protected static $enable_shadow = 'no';
	protected static $enable_rounded = 'no';
	protected static $border_width = '1px';
	protected static $border_color = '#000000';
	protected static $title_background = '#f5f5f5';
	protected static $content_background = '#FFFFFF';
	protected static $border_radius = '5px';
	// positioning
	protected static $corner_my_y = 'bottom';
	protected static $corner_my_x = 'left';
	protected static $corner_my_swap = 'no';
	protected static $corner_at_y = 'top';
	protected static $corner_at_x = 'right';
	protected static $corner_adjust = 'none';
	
	//tooltip title settings
	protected static $hide_title_from_tooltip = 'no';
	protected static $title_use_theme_settings = 'yes';
	protected static $title_font_size = '16px';
	protected static $title_color = '#000000';
	protected static $title_font_style = 'normal';
	protected static $title_font_weight = 'normal';
	protected static $title_text_decoration = 'none';
	
	//tooltip content settings
	protected static $content_use_theme_settings = 'yes';
	protected static $content_text_align = 'justify';
	protected static $content_font_size = '12px';
	protected static $content_color = '#000000';
	protected static $content_padding = '10px';
	
	//other settings
	protected static $custom_word_styling = 'no';
	protected static $word_color = '#750909';
	protected static $word_font_style = 'normal';
	protected static $word_font_weight = 'normal';
	protected static $word_text_decoration = 'none';
	protected static $search_results = 'no';
	protected static $show_on_homepage = 'no';
	protected static $case_sensitive = 'no';
	protected static $heavy_search;
	protected static $use_custom_alphabet = 'no';
	protected static $alphabet = 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z';
	protected static $usedletters = '';
	
	private static $tab_fields = array(
		1 => array (
			'_exclude',
			'_limit',
			'_max_width',
			'_min_width',
		),
		2 => array (
			'_external_css_file',
			'_theme',
			'_enable_rounded',
			'_enable_shadow',
			'_border_width',
			'_border_color',
			'_title_background',
			'_content_background',
			'_border_radius',
			'_corner_my_y',
			'_corner_my_x',
			'_corner_my_swap',
			'_corner_at_y',
			'_corner_at_x',
			'_corner_adjust',
			'_hide_title_from_tooltip',
			'_title_use_theme_settings',
			'_title_font_size',
			'_title_color',
			'_title_font_style',
			'_title_font_weight',
			'_title_text_decoration',
			'_content_use_theme_settings',
			'_content_text_align',
			'_content_font_size',
			'_content_color',
			'_content_padding',
		),
		3 => array (
			'_custom_word_styling',
			'_word_color',
			'_word_font_style',
			'_word_font_weight',
			'_word_text_decoration',
			'_search_results',
			'_show_on_homepage',
			'_case_sensitive',
			'_heavy_search',
			'_use_custom_alphabet',
			'_alphabet',
			'_usedletters',
		)
	);
	
	protected static $settings_list;
	
	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *       
	 * @var object
	 */
	protected static $instance = null;
	
	/**
	 * Initialize the plugin by setting localization, filters, and
	 * administration functions.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'admin_init', array ($this, 'initialize_settings_sections' ) );
		add_action( 'admin_init', array ($this, 'check_post' ) );  
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		
		self::load_user_settings();
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *       
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/*----------------------------------------------------------------------------*
	 * VIEW functions
	 *----------------------------------------------------------------------------*/

	
	public function initialize_settings_sections() {
		add_settings_section(  
			Explanatory_Dictionary::$plugin_slug_safe . '_general_settings_section',  
			__( 'Tooltip settings', 'explanatory-dictionary' ),  
			array( $this, 'general_settings_callback' ),  
			Explanatory_Dictionary::$plugin_slug.'-general-settings'  
	    );
	    
		add_settings_section(  
			Explanatory_Dictionary::$plugin_slug_safe . '_tooltip_title_settings_section',  
			__( 'Tooltip title settings', 'explanatory-dictionary' ),  
			array( $this, 'tooltip_title_settings_callback' ),  
			Explanatory_Dictionary::$plugin_slug.'-tooltip-title-settings'  
	    );
	    
		add_settings_section(  
			Explanatory_Dictionary::$plugin_slug_safe . '_tooltip_content_settings_section',  
			__( 'Tooltip content settings', 'explanatory-dictionary' ),  
			array( $this, 'tooltip_content_settings_callback' ),  
			Explanatory_Dictionary::$plugin_slug.'-tooltip-content-settings'  
	    );
	    
		add_settings_section(  
			Explanatory_Dictionary::$plugin_slug_safe . '_other_settings_section',  
			__( 'Other settings', 'explanatory-dictionary' ),  
			array( $this, 'other_settings_callback' ),  
			Explanatory_Dictionary::$plugin_slug.'-other-settings'  
	    );
	    
		add_settings_section(  
			Explanatory_Dictionary::$plugin_slug_safe . '_tooltip_theme_settings_section',  
			__( 'Tooltip theme settings', 'explanatory-dictionary' ),  
			array( $this, 'tooltip_theme_settings_callback' ),  
			Explanatory_Dictionary::$plugin_slug.'-tooltip-theme-settings'  
	    );
	    
	}
	
	public function check_post() {
		
		if( isset( $_POST['settings'] ) ) {
			
			if ( isset( $_GET["tab"] ) ) {
				$tab = (int) $_GET["tab"];
			} else {
				$tab = 1;
			}
			
			if(! isset( self::$tab_fields[$tab] ) ) {
				$url = admin_url( 'edit.php?post_type=explandict&page=explanatory-dictionary-settings&tab=' . $tab . '&message=' . Explanatory_Dictionary_Helpers::SETTINGS_ERROR_MESSAGE);
					
				wp_redirect($url);
				exit;
			}
			
			$tab_title = 'tab' . $tab;
			
			if( isset( $_POST[$tab_title . '_nonce'] ) ) {
				$nonce = $_POST[$tab_title . '_nonce'];

				if( wp_verify_nonce( $nonce, Explanatory_Dictionary_Helpers::get_nonce($tab_title) ) ) {
				
				
					if( isset( $_POST['submit'] ) ) {
						Explanatory_Dictionary_Helpers::handle_options( $_POST['settings'], self::$tab_fields[$tab] );
					
						$url = admin_url( 'edit.php?post_type=explandict&page=explanatory-dictionary-settings&tab=' . $tab . '&message=' . Explanatory_Dictionary_Helpers::SETTINGS_SUCCESS_MESSAGE);
						
						wp_redirect($url);
						exit;
					} else if( isset( $_POST['reset'] ) ) {
						self::reset_settings_for_tab( $tab );
						
						$url = admin_url( 'edit.php?post_type=explandict&page=explanatory-dictionary-settings&tab=' . $tab . '&message=' . Explanatory_Dictionary_Helpers::SETTINGS_RESET_MESSAGE);
						
						wp_redirect($url);
						exit;
					}
					
				}
			}
		}
	}

	public function general_settings_callback() {
		$settings = Explanatory_Dictionary_Settings::$settings_list;
		
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings-blocks/tooltip-settings.php' );
	}
	
	public function tooltip_title_settings_callback() {
		$settings = Explanatory_Dictionary_Settings::$settings_list;
		
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings-blocks/tooltip-title-settings.php' );
	}
	
	public function tooltip_content_settings_callback() {
		$settings = Explanatory_Dictionary_Settings::$settings_list;
		
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings-blocks/tooltip-content-settings.php' );
	}
	
	public function other_settings_callback() {
		$settings = Explanatory_Dictionary_Settings::$settings_list;
		
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings-blocks/other-settings.php' );
	}
	
	public function tooltip_theme_settings_callback() {
		$settings = Explanatory_Dictionary_Settings::$settings_list;
				
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings-blocks/tooltip-theme-settings.php' );
	}
	
	/**
	 * Renders the main settings page
	 *
	 * @since 1.0.0
	 *
	 * @return the view
	 */
	public static function render_main_page() {

		wp_enqueue_style( Explanatory_Dictionary::$plugin_slug_safe . '-qtip' );
		wp_enqueue_style( Explanatory_Dictionary::$plugin_slug_safe . '-admin-styles' );
		wp_enqueue_style( Explanatory_Dictionary::$plugin_slug_safe . '-custom-settings' );
		
		wp_enqueue_script( Explanatory_Dictionary::$plugin_slug_safe . '-qtip' );
		wp_enqueue_script( Explanatory_Dictionary::$plugin_slug_safe . '-admin-script' );
		
		if ( isset( $_GET["tab"] ) ) {
			$tab = (int) $_GET["tab"];
		} else {
			$tab = 1;
		}

		// We do it via a switch because we might get a different method for each tab
		switch ( $tab ) {
			case 1: 	self::view_settings_tab(1);		break;
			case 2: 	self::view_settings_tab(2); 	break;
			case 3: 	self::view_settings_tab(3); 	break;
			//case 4: 	self::view_settings_tab(4); 	break;
			default: 	self::view_settings_tab(1); 	break;
		}
	}
	
	public static function view_settings_tab( $tab ) {
		// let's just do this in case
		$tab = (int) $tab;
		
		$tab_title = 'tab' . $tab;
		$tab_frame = self::get_tab_frame( $tab );
		
		if( isset( $_GET['message'] ) ) {
			$id = (int)$_GET['message'];
			
			Explanatory_Dictionary_Helpers::get_message( $id );
		}
		
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings.' . $tab_title . '.php' );
	}

	/*----------------------------------------------------------------------------*
	 * GET functions
	 *----------------------------------------------------------------------------*/

	public static function get_tab_frame( $tab ) {

		ob_start();
		include_once( plugin_dir_path( __FILE__ ) . '../views/settings-blocks/header.php' );
		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

	/*----------------------------------------------------------------------------*
	 * SET functions
	 *----------------------------------------------------------------------------*/
	

	/*----------------------------------------------------------------------------*
	 * DELETE functions
	 *----------------------------------------------------------------------------*/
	
	
	private static function create_settings_list() {
		self::$settings_list = array(
		// Tooltip settings
			'_exclude' => self::$exclude,
			'_limit' => self::$limit,
			'_max_width' => self::$max_width,
			'_min_width' => self::$min_width,
			
		// Tooltip styling
			'_external_css_file' => self::$external_css_file,
			'_theme' => self::$theme,
			'_enable_rounded' => self::$enable_rounded,
			'_enable_shadow' => self::$enable_shadow,
			'_border_width' => self::$border_width,
			'_border_color' => self::$border_color,
			'_title_background' => self::$title_background,
			'_content_background' => self::$content_background,
			'_border_radius' => self::$border_radius,
		// Positioning
			'_corner_my_y' => self::$corner_my_y,
			'_corner_my_x' => self::$corner_my_x,
			'_corner_my_swap' => self::$corner_my_swap,
			'_corner_at_y' => self::$corner_at_y,
			'_corner_at_x' => self::$corner_at_x,
			'_corner_adjust' => self::$corner_adjust,
		// Title
			'_hide_title_from_tooltip' => self::$hide_title_from_tooltip,
			'_title_use_theme_settings' => self::$title_use_theme_settings,
			'_title_font_size' => self::$title_font_size,
			'_title_color' => self::$title_color,
			'_title_font_style' => self::$title_font_style,
			'_title_font_weight' => self::$title_font_weight,
			'_title_text_decoration' => self::$title_text_decoration,
		// Content
			'_content_use_theme_settings' => self::$content_use_theme_settings,
			'_content_text_align' => self::$content_text_align,
			'_content_font_size' => self::$content_font_size,
			'_content_color' => self::$content_color,
			'_content_padding' => self::$content_padding,
		
		// Other settings
			'_custom_word_styling' => self::$custom_word_styling,
			'_word_color' => self::$word_color,
			'_word_font_style' => self::$word_font_style,
			'_word_font_weight' => self::$word_font_weight,
			'_word_text_decoration' => self::$word_text_decoration,
			'_search_results' => self::$search_results,
			'_show_on_homepage' => self::$show_on_homepage,
			'_case_sensitive' => self::$case_sensitive,
			'_heavy_search' => self::$heavy_search,
			'_use_custom_alphabet' => self::$use_custom_alphabet,
			'_alphabet' => self::$alphabet,
			'_usedletters' => self::$usedletters,
		);
	}
	
	public static function add_default_options() {
		add_option( Explanatory_Dictionary::$plugin_slug_safe . '_settings', self::$settings_list );
	}
	
	public static function reset_all_settings() {
		foreach( array_keys( self::$tab_fields ) as $tab ) {
			self::reset_settings_for_tab( $tab );
		}
	}
	
	public static function reset_settings_for_tab( $tab = 1 ) {
		
		$tab = (int)$tab;
		if( ! isset( self::$tab_fields[$tab] ) ) {
			return false;
		}
		$fields = self::$tab_fields[$tab];
		
		foreach( $fields as $field ) {
			$property = substr($field, 1);
			self::$settings_list[$field] = self::$$property;
		}
		
		self::update_settings();
	}
	
	public static function load_user_settings() {	

		self::$settings_list = get_option( Explanatory_Dictionary::$plugin_slug_safe . '_settings' );
		
		if( self::$settings_list == null ) {
			// create the list and save it to the DB
			self::create_settings_list();
			self::add_default_options();
		}
	}
	
	public static function get_settings_list() {
		self::load_user_settings();
		
		return self::$settings_list;
	}
	
	public static function get_setting( $setting ) {
		self::load_user_settings();
		
		return self::$settings_list[$setting];
	}
	
	public static function update_settings_list( $new_settings ) {
		foreach ( $new_settings as $key => $value ) {
			self::$settings_list[$key] = $value;
		}
	}
	
	public static function update_settings() {
		update_option( Explanatory_Dictionary::$plugin_slug_safe . '_settings', self::$settings_list );
		
		self::generate_settings_stylesheet();
		
		return true;
	}
	
	public static function generate_settings_stylesheet() {
		$upload_dir = wp_upload_dir();
		
		if ( !is_writable( $upload_dir['path'] ) ) {
			return;
		}
		
		$dir = $upload_dir['basedir'] . '/explanatory-dictionary';
		if( !is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}
		
		$settings_css = $dir . '/settings.css';
		
		// create the file
		if( is_writable( $dir ) && ! file_exists( $settings_css ) ) {
			file_put_contents( $settings_css, '' );
		}
		
		$settings = self::$settings_list;
		
		$hide_title = '';
		if( 'yes' === $settings['_hide_title_from_tooltip'] ) {
			$hide_title = 'display: none;';
		}
		
		
		$term_styling = '';
		$word_decoration = ( 'underline' == $settings['_word_text_decoration'] ? 'border-bottom: 1px dashed;' : 'border-bottom: none;' );
		if( 'no' === $settings['_custom_word_styling'] ) {
			$term_styling = "
.explanatory-dictionary-highlight {
	font-style: {$settings['_word_font_style']};
	font-weight: {$settings['_word_font_weight']};
	{$word_decoration}
	color: {$settings['_word_color']};
}";
		}
		
		
$css = "/* GENERATED CSS DO NOT MODIFY */
" . $term_styling ."
		
.qtip-custom {
	background-color: " . $settings['_content_background'] . " ;
	border-color: " . $settings['_border_color'] . " ;
	border-style: solid;
	border-width: " . $settings['_border_width'] . " px;
}

.qtip-custom .qtip-titlebar {
	background-color: " . $settings['_title_background'] . " ;
}

.qtip .qtip-titlebar {
	" . $hide_title . "
}

.qtip-custom-title .qtip-titlebar {
	color: " . $settings['_title_color'] . " ;
	font-weight: " . $settings['_title_font_weight'] . " ;
	font-style: " . $settings['_title_font_style'] . " ;
	text-decoration: " . $settings['_title_text_decoration'] . " ;
}

.qtip-custom-content .qtip-content {
	text-align: " . $settings['_content_text_align'] . " ;
	padding: " . $settings['_content_padding'] . " ;
	font-size: " . $settings['_content_font_size'] . " ;
	color: " . $settings['_content_color'] . " ;
}

.qtip-custom.qtip-rounded {
	border-radius: " . $settings['_border_radius'] . " px;
	-moz-border-radius: " . $settings['_border_radius'] . " px;
	-webkit-border-radius: " . $settings['_border_radius'] . " px;
}

.qtip-custom.qtip-rounded .qtip-titlebar {
	border-radius: " . ($settings['_border_radius'] - 1) . "px " . ($settings['_border_radius'] - 1) . "px 0 0;
	-moz-border-radius: " . ($settings['_border_radius'] - 1) . "px " . ($settings['_border_radius'] - 1) . "px 0 0;
	-webkit-border-radius: " . ($settings['_border_radius'] - 1) . "px " . ($settings['_border_radius'] - 1) . "px 0 0;
}
";

		$file = fopen( $settings_css, 'w+' );
		fwrite( $file, $css );
		fclose( $file );
	}
	
	public function admin_notices() {
		// Perhaps limit it to only our plugin pages
		$upload_dir = wp_upload_dir();
		
		if ( !is_writable( $upload_dir['path'] ) ) {
			Explanatory_Dictionary_Helpers::get_message( Explanatory_Dictionary_Helpers::UPLOAD_DIR_NOT_WRITABLE );
		}
		
	}
	
	public static function migrate_old_settings() {
		$old_settings = get_option(Explanatory_Dictionary::$plugin_slug_safe . '_settings');
		$new_settings = array();
		foreach ( $old_settings as $key => $value ) {
			$new_key = str_replace( Explanatory_Dictionary::$plugin_slug_safe, '', $key );
			$new_settings[ $new_key ] = $value;
		}
		update_option( Explanatory_Dictionary::$plugin_slug_safe . '_settings', $new_settings );
	}
}