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
class Explanatory_Dictionary_Settings_Validation extends Explanatory_Dictionary_Settings {
	
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
	
	private static function setting_has_value( $setting, $key ) {
		return ( isset( $setting[$key] ) && '' != $setting[$key] );
	}
	
	private static function color_setting_has_value( $setting, $key ) {
		return ( isset( $setting[$key] ) && preg_match( '/(^#[0-9abcdef]{6}|^#[0-9abcdef]{3})/i', $setting[$key] ) );
	}
	
	/**
	 * Validation method for exclude setting
	 * @param unknown $settings
	 */
	public static function validate_exclude( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_exclude' ) ) {
			$settings[0]['_exclude'] = Explanatory_Dictionary_Settings::$exclude;
		}
	}

	/**
	 * Validation method for limit setting
	 * @param unknown $settings
	 */
	public static function validate_limit( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_limit' ) ) {
			$settings[0]['_limit'] = Explanatory_Dictionary_Settings::$limit;
		}
	}

	/**
	 * Validation method for max-width setting
	 * @param unknown $settings
	 */
	public static function validate_max_width( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_max_width' ) ) {
			$settings[0]['_max_width'] = '';
		}
	}

	/**
	 * Validation method for min-width setting
	 * @param unknown $settings
	 */
	public static function validate_min_width( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_min_width' ) ) {
			$settings[0]['_min_width'] = '';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_external_css_file( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_external_css_file' ) ) {
			$settings[0]['_external_css_file'] = 'no';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_theme( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_theme' ) ) {
			$settings[0]['_theme'] = Explanatory_Dictionary_Settings::$theme;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_enable_rounded( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_enable_rounded' ) ) {
			$settings[0]['_enable_rounded'] = 'no';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_enable_shadow( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_enable_shadow' ) ) {
			$settings[0]['_enable_shadow'] = 'no';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_border_width( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_border_width' ) ) {
			$settings[0]['_border_width'] = '0';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_border_color( &$settings ) {
		if ( ! self::color_setting_has_value( $settings[0], '_border_color' ) ) {
			$settings[0]['_border_color'] = Explanatory_Dictionary_Settings::$border_color;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_background( &$settings ) {
		if ( ! self::color_setting_has_value( $settings[0], '_title_background' ) ) {
			$settings[0]['_title_background'] = Explanatory_Dictionary_Settings::$title_background;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_content_background( &$settings ) {
		if ( ! self::color_setting_has_value( $settings[0], '_content_background' ) ) {
			$settings[0]['_content_background'] = Explanatory_Dictionary_Settings::$content_background;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_border_radius( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_border_radius' ) ) {
			$settings[0]['_border_radius'] = '0';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_corner_my_y( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_corner_my_y' ) ) {
			$settings[0]['_corner_my_y'] = Explanatory_Dictionary_Settings::$corner_my_y;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_corner_my_x( &$settings ) {
		
		if ( ! self::setting_has_value( $settings[0], '_corner_my_x' ) ) {
			$settings[0]['_corner_my_x'] = Explanatory_Dictionary_Settings::$corner_my_x;
		}
		
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_corner_my_swap( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_corner_my_swap' ) ) {
			$settings[0]['_corner_my_swap'] = Explanatory_Dictionary_Settings::$corner_my_swap;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_corner_at_y( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_corner_at_y' ) ) {
			$settings[0]['_corner_at_y'] = Explanatory_Dictionary_Settings::$corner_at_y;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_corner_at_x( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_corner_at_x' ) ) {
			$settings[0]['_corner_at_x'] = Explanatory_Dictionary_Settings::$corner_at_x;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_corner_adjust( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_corner_adjust' ) ) {
			$settings[0]['_corner_adjust'] = Explanatory_Dictionary_Settings::$corner_adjust;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_hide_title_from_tooltip( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_hide_title_from_tooltip' ) ) {
			$settings[0]['_hide_title_from_tooltip'] = Explanatory_Dictionary_Settings::$hide_title_from_tooltip;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_use_theme_settings( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_title_use_theme_settings' ) ) {
			$settings[0]['_title_use_theme_settings'] = 'no';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_font_size( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_title_font_size' ) ) {
			$settings[0]['_title_font_size'] = 'inherit';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_color( &$settings ) {
		if ( ! self::color_setting_has_value( $settings[0], '_title_color' ) ) {
			$settings[0]['_title_color'] = Explanatory_Dictionary_Settings::$title_color;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_font_style( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_title_font_style' ) ) {
			$settings[0]['_title_font_style'] = Explanatory_Dictionary_Settings::$title_font_style;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_font_weight( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_title_font_weight' ) ) {
			$settings[0]['_title_font_weight'] = Explanatory_Dictionary_Settings::$title_font_weight;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_title_text_decoration( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_title_text_decoration' ) ) {
			$settings[0]['_title_text_decoration'] = Explanatory_Dictionary_Settings::$title_text_decoration;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_content_use_theme_settings( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_content_use_theme_settings' ) ) {
			$settings[0]['_content_use_theme_settings'] = 'no';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_content_text_align( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_content_text_align' ) ) {
			$settings[0]['_content_text_align'] = Explanatory_Dictionary_Settings::$content_text_align;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_content_font_size( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_content_font_size' ) ) {
			$settings[0]['_content_font_size'] = Explanatory_Dictionary_Settings::$content_font_size;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_content_color( &$settings ) {
		if ( ! self::color_setting_has_value( $settings[0], '_content_color' ) ) {
			$settings[0]['_content_color'] = Explanatory_Dictionary_Settings::$content_color;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_content_padding( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_padding' ) ) {
			$settings[0]['_padding'] = '0';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_custom_word_styling( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_custom_word_styling' ) ) {
			$settings[0]['_custom_word_styling'] = Explanatory_Dictionary_Settings::$custom_word_styling;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_word_color( &$settings ) {
		if ( ! self::color_setting_has_value( $settings[0], '_word_color' ) ) {
			$settings[0]['_word_color'] = Explanatory_Dictionary_Settings::$word_color;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_word_font_style( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_word_font_style' ) ) {
			$settings[0]['_word_font_style'] = Explanatory_Dictionary_Settings::$word_font_style;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_word_font_weight( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_word_font_weight' ) ) {
			$settings[0]['_word_font_weight'] = Explanatory_Dictionary_Settings::$word_font_weight;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_word_text_decoration( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_word_text_decoration' ) ) {
			$settings[0]['_word_text_decoration'] = Explanatory_Dictionary_Settings::$word_text_decoration;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_search_results( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_search_results' ) ) {
			$settings[0]['_search_results'] = Explanatory_Dictionary_Settings::$search_results;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_show_on_homepage( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_show_on_homepage' ) ) {
			$settings[0]['_show_on_homepage'] = Explanatory_Dictionary_Settings::$show_on_homepage;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_case_sensitive( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_case_sensitive' ) ) {
			$settings[0]['_case_sensitive'] = Explanatory_Dictionary_Settings::$case_sensitive;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_heavy_search( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_heavy_search' ) ) {
			$settings[0]['_heavy_search'] = Explanatory_Dictionary_Settings::$heavy_search;
		}
	}
	
	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_use_custom_alphabet( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_use_custom_alphabet' ) ) {
			$settings[0]['_use_custom_alphabet'] = Explanatory_Dictionary_Settings::$use_custom_alphabet;
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_alphabet( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_alphabet' ) ) {
			$settings[0]['_alphabet'] = '';
		}
	}

	/**
	 * Validation method for external css file setting
	 * @param unknown $settings
	 */
	public static function validate_usedletters( &$settings ) {
		if ( ! self::setting_has_value( $settings[0], '_usedletters' ) ) {
			$settings[0]['_usedletters'] = Explanatory_Dictionary_Settings::$usedletters;
		}
	}
	
}