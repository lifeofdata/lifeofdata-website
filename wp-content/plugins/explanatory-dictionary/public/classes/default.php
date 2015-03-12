<?php
/**
 * Explanatory Dictionary.
 *
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */

class Explanatory_Dictionary {
	
	/**
	 * Plugin version, used for cache-busting of style and script file
	 * references.
	 *
	 * @since 4.0.0
	 */
	const VERSION = '4.1.5';
	
	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the hoveriable name) as the text domain when
	 * internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since 4.0.0
	 */
	public static $plugin_slug = 'explanatory-dictionary';
	public static $plugin_slug_safe = 'explanatory_dictionary';
	
	/**
	 * Instance of this class.
	 *
	 * @since 4.0.0
	 */
	protected static $instance = null;
	
	/**
	 * Slug of the plugin screen.
	 *
	 * @since 4.0.0
	 */
	protected $plugin_screen_hook_suffix = null;
	
	/**
	 * Slug of the plugin options screen.
	 *
	 * @since 4.0.0
	 */
	protected $plugin_options_screen_hook_suffix = null;
	
	protected $exclude_array = array();
	
	/**
	 * hold the html of the definitioner to add at the end of the page.
	 * 
	 * @since 4.0.1 
	 */
	protected $definitioner;
	
	/**
	 * Exclude single words on the page specified by shortcode
	 * 
	 * @since 4.0.1
	 */
	protected $excluded_words = array();
	
	
	/**
	 * Initialize the plugin by setting localization, filters, and
	 * administration functions.
	 *
	 * @since 4.0.0
	 */
	private function __construct() {		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_shortcode( 'explanatory-dictionary' , array( $this, 'explanatory_dictionary_shortcode' ) );
		add_shortcode( 'no-explanation' , array( $this, 'no_explanation_shortcode' ) );
		
		add_filter( 'the_content', array( $this, 'add_explanatory_dictionary_words' ), 15 );
		
		add_action( 'wp_footer', array( $this, 'add_definitioner' ) );
		
		/**
		 * deprecated
		 */
		add_shortcode( 'explanatory dictionary' , array( $this, 'explanatory_dictionary_shortcode_deprecated' ) );
		add_shortcode( 'no explanation' , array( $this, 'no_explanation_shortcode_deprecated' ) );
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since 4.0.0
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
	
	public static function on_activate() {
		Explanatory_Dictionary_Settings::generate_settings_stylesheet();
	}
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 4.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = self::$plugin_slug_safe;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 4.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( self::$plugin_slug_safe . '-plugin-styles', plugins_url( '../assets/css/public.css', __FILE__ ), array(), self::VERSION );
		wp_register_style( self::$plugin_slug_safe . '-qtip', plugins_url( '../assets/js/qtip/jquery.qtip.min.css', __FILE__ ), array(), '2.2.0', false );
		
		$upload_dir = wp_upload_dir();
		$settings_css = $upload_dir['baseurl'] . '/explanatory-dictionary/settings.css';
		wp_register_style( self::$plugin_slug_safe . '-custom-settings', $settings_css, null, false, false );
	}
	
	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since 4.0.0
	 */
	public function enqueue_scripts() {
		
		// We are not using this one (yet)
		//wp_enqueue_script( self::$plugin_slug_safe . '-plugin-script', plugins_url( '../assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		
		wp_register_script( self::$plugin_slug_safe . '-qtip', plugins_url( '../assets/js/qtip/jquery.qtip.min.js', __FILE__ ), array( 'jquery' ), '2.2.0', true );
		
		// Register the script so we can include it if we need to
		wp_register_script( self::$plugin_slug_safe . '-qtip-script', plugins_url( '../assets/js/qtip.js', __FILE__ ), array( 'jquery', self::$plugin_slug_safe . '-qtip' ), self::VERSION, true );
		
		$settings = Explanatory_Dictionary_Settings::get_settings_list();
		
		$my = $settings['_corner_my_y'] . ' ' . $settings['_corner_my_x'];
		if( 'yes' === $settings['_corner_my_swap'] ) {
			$my = $settings['_corner_my_x'] . ' ' . $settings['_corner_my_y'];	
		}
		
		$at = $settings['_corner_at_y'] . ' ' . $settings['_corner_at_x'];
		$classes = $settings['_theme'];
	
		if( 'yes' == $settings['_enable_rounded'] ) {
			$classes .= ' qtip-rounded ';
		}
		if( 'yes' == $settings['_enable_shadow'] ) {
			$classes .= ' qtip-shadow ';
		}
	
		if( 'no' === $settings['_title_use_theme_settings'] ) {
			$classes .= ' qtip-custom-title ';
		}
		
		if( 'no' === $settings['_content_use_theme_settings'] ) {
			$classes .= ' qtip-custom-content ';
		}
		
		$qtip_settings = array( 
			'my' => $my,
			'at' => $at,
			'corner_adjust' => $settings['_corner_adjust'],
			'classes' => $classes,
		);
		
		wp_localize_script( self::$plugin_slug_safe . '-qtip-script', 'qtip_settings', $qtip_settings );
	}
	
	public static function get_synonyms_post_meta( $post_id ) {
		return get_post_meta( $post_id, Explanatory_Dictionary::$plugin_slug_safe . '_synonyms', true );
	}
	
	/**
	 * Creates the list by the shortcode
	 * 
	 * @since 4.0.0
	 * @param array $atts (Wordress default parameter) 
	 */
	public function explanatory_dictionary_shortcode( $atts ) {
		global $wpdb;
		
		// First we remove the filter that shows the tooltips
		remove_filter( 'the_content', array(
			$this, 'add_explanatory_dictionary_words'
		), 15 );
		
		// Get the different attributes the shorcode can have
		extract( shortcode_atts( array(
			'dictionary' => false,
			'letter' => false,
		), $atts ) );
		
		$has_single_dictionary = isset( $atts['dictionary'] );
		$single_dictionary = $has_single_dictionary ? $atts['dictionary'] : '';
		
		$shortcode_has_letter = isset( $atts['letter'] );
		$shortcode_letter = $shortcode_has_letter ? $atts['letter'] : '';
		
		
		$args = array(
			'post_type' => Explanatory_Dictionary_PostType::$post_type,
			'posts_per_page' => -1,
			'order'=> 'ASC', 
			'orderby' => 'title'
		);
		
		// Add the shortcode dictionary to the arguments
		if( $has_single_dictionary ) {
			$args[Explanatory_Dictionary_PostType::$taxonomy] = $single_dictionary;
		}
		 
		$all_posts = get_posts( $args );
		$posts = $all_posts;
		
		$letters = '';
		if( !$shortcode_has_letter ) {
			$letters = $this->get_letters( $all_posts, $posts, $single_dictionary );
		} else {
			$posts = $this->get_definitions_for_letter( $shortcode_letter );
		}
		
		$file = get_stylesheet_directory() . '/explanatory-dictionary.php';
		
		ob_start();
		if ( file_exists( $file ) ) {
			include( $file );
		} else {
			include( plugin_dir_path( __FILE__ ) . '/../views/explanatory-dictionary.php' );
		}
		return ob_get_clean();
	}
	
	private function get_letters( $all_posts, &$posts, $dictionary ) {
		// TODO: Sort posts by given alphabet
		$current_selected_letter = false;
		
		$dictionary_letter_url = 'explanatory-dictionary-letter';
		if( !empty( $dictionary ) ) {
			$dictionary_letter_url = $dictionary . '-letter';
		}
		
		if ( isset( $_GET[$dictionary_letter_url] ) ) {
			$current_selected_letter = strtolower( $_GET[$dictionary_letter_url] );
			$posts = $this->get_definitions_for_letter( $current_selected_letter );
		}

		foreach ( $posts as $post ) {
			$post->synonyms = self::get_synonyms_post_meta( $post->ID );
		}
		
		$settings = Explanatory_Dictionary_Settings::get_settings_list();
		$use_custom_alphabet = 'yes' == $settings['_use_custom_alphabet'];
		if( $use_custom_alphabet ) {
			$letters = explode( ' ', $settings['_alphabet'] );
				
			$tmp_letters = $letters;
			$hide_unused_letters = $settings['_usedletters'];
			if( true == (bool)$hide_unused_letters ) {
				$counter = 0;
		
				foreach( $tmp_letters as $letter ){
					if( ! $this->letter_has_definition( $letter ) ) {
						unset( $letters[$counter] );
					}
						
					$counter++;
				}
			}
		} else {
			$letters = array();
			foreach( $all_posts as $post ) {
				$title = trim( $post->post_title );
				// use mb_ so we get the correct encoding
				$letter = mb_substr( $title, 0, 1);
				if( !in_array( strtolower( $letter ), $letters ) ) {
					$letters[] = strtolower( $letter );
				}
			}
			// Sort the letters in alphabetic order
			sort( $letters );
		}
		ob_start();
		
		$counter = 0;
		echo '<div class="explanatory-dictionary-alphabet">';
		foreach ( $letters as $letter ) {
			if ( ! isset( $_GET[$dictionary_letter_url] ) || $letter != $_GET[$dictionary_letter_url] ) {
				$url = add_query_arg( $dictionary_letter_url, $letter );
				echo '<a href="' . $url . '">' . $letter . '</a>';
			} else {
				echo '<span class="explanatory-dictionary-letter-selected">' . $letter . '</span>';
			}
			$counter++;
		
			if ( $counter < count( $letters ) ) {
				echo ' | ';
			}
		}
		echo '</div>';
		if ( isset( $_GET[$dictionary_letter_url] ) ) {
			$url = remove_query_arg( $dictionary_letter_url );
			$reset = __( 'Reset list', 'explanatory-dictionary' );
			echo '<a href="' . $url . '">' . $reset . '</a>';
		}
		
		return ob_get_clean();
	}
	
	public function letter_has_definition( $letter ) {
		global $wpdb;
		
		$results = $wpdb->prepare( "
					SELECT `ID`
					FROM " . $wpdb->posts  . "
					WHERE post_type = %s
						AND `post_status` = 'publish'
						AND `post_title` LIKE %s 
					LIMIT 1",
				array( 
					Explanatory_Dictionary_PostType::$post_type,
					$letter . '%'
				)
		);
		$count = $wpdb->query( $results );
		
		return 1 === $count;
	}
	
	public function get_definitions_for_letter( $letter ) {
		global $wpdb;
		
		$results = $wpdb->prepare( "
					SELECT `ID`
					FROM " . $wpdb->posts  . "
					WHERE post_type = %s
						AND `post_status` = 'publish'
						AND `post_title` LIKE %s ",
				array( 
					Explanatory_Dictionary_PostType::$post_type,
					$letter . '%'
				)
		);
		
		$ids = $wpdb->get_col( $results );
		
		$args = array(
			'post_type' => Explanatory_Dictionary_PostType::$post_type,
			'posts_per_page' => -1,
			'post__in' => $ids,
			'order'=> 'ASC', 
			'orderby' => 'title'
		);
		
		$posts = get_posts( $args );
		
		$counter = 0;
		foreach( $posts as $post ) {
			$title = trim( $post->post_title );
			// use mb_ so we get the correct encoding
			// TODO: Fix diatrics search
			/*$first_letter = mb_substr( $title, 0, 1);
			var_dump($first_letter);
			if( $letter != strtolower( $first_letter ) ) {
				unset( $posts[$counter] );
			}*/
			
			$post->synonyms = self::get_synonyms_post_meta( $post->ID );
			$counter++;
		}
		
		return $posts;
	}
	
	/**
	 * Removes the explanation tag from the word/sentence
	 * 
	 * @since 4.0.1
	 * @param array $atts (Wordress default parameter) 
	 */
	public function no_explanation_shortcode( $atts, $content ) {
		return '<span class="explandict-no-explanation">' . $content . '</span>';
	}
	
	/**
	 * Make the words or synonyms show a tooltip
	 * 
	 * @since 4.0.0
	 * @param unknown_type $content
	 */
	public function add_explanatory_dictionary_words( $content ) {
		
		// Check if we can show tooltips here
		if( ! $this->_can_show_tooltips() ) {
			return $content;
		}

		$settings_list = Explanatory_Dictionary_Settings::get_settings_list();
		
		$args = array(
			'post_type' => Explanatory_Dictionary_PostType::get_post_type(),
			'posts_per_page' => -1
		);
		
		$dictionary = get_posts( $args );
		
		if( empty( $dictionary ) ) {
			return $content;
		}
		
		// Temporarily remove content we don't want to filter (images, headings, links)
		$searches = array( 
			'/<span class="explandict-no-explanation".*<\/span>/i',
			'/<article.*<\/article>/i', // used for doc list
			'/<form.*<\/form>/i', // timeplan/other forms
			'/<h\d.*<\/h\d>/i',
			'/<a.*<\/a>/i',
			'/<img[^>]+\>/i',
			'/<input[^>]+\>/i',
		);
		$content = preg_replace_callback( $searches , array( $this, 'store_content_to_avoid' ), $content );
		
		$searches = array(); // search words to replace
		$replacements = array(); // span placeholder text ot replace the term with
		$definitions = array(); // list of definitions that exist in the document
		
		$counter = 0;
		$is_case_sensitive = 'yes' == $settings_list['_case_sensitive'] ? true : false;
		$preg_match_modifiers = 'ui';
		if( $is_case_sensitive ) {
			$preg_match_modifiers = 'u';
		}
		
		// Loop through the words and build search/replace arrays to use in a preg_replace, plus definitions to define at the end of the document
		foreach( $dictionary as $definition ) {
			
			$new_word = preg_quote($definition->post_title, '/');
			$new_word = trim($new_word);
			
			$new_word = wptexturize( $new_word );
			
			// We add it into a sub array because otherwise the keys will be overwritten as it is no case sensitive
			$boundaries = array(
				array( '\b' => '\b' ),
			);
			
			$heavy_search = Explanatory_Dictionary_Settings::get_setting('_heavy_search');
			if( 'yes' == $heavy_search ) {
				$boundaries = array(
					array( '\b' => '\b' ),
					array( '\b' => '\B' ),
					array( '\B' => '\b' ),
					array( '\B' => '\B' ),
				);
			}
			
			$matches = array();
			foreach ( $boundaries as $boundary ) {
				
				if( ! empty( $matches ) ) {
					break;
				}
				
				$search = '/' . key( $boundary ) . $new_word . current( $boundary ) . '/' . $preg_match_modifiers;
				preg_match( $search, $content, $matches );
			}
			
			if( ! empty( $matches ) ) {
				$searches[$counter] = $search;
				
				$replacements[$counter] = '<span class="explanatory-dictionary-highlight" data-definition="explanatory-dictionary-definition-' . $counter . '">$0</span>';
				$definitions[] = array( 
					'id' => $counter, 
					'word' => $definition->post_title, 
					'explanation' => $definition->post_content 
				);
			}
			
			$synonyms_and_forms = self::get_synonyms_post_meta( $definition->ID );
			
			if( !empty( $synonyms_and_forms ) ) {
					
				$extra_key = 10000; // need to use a different key - synonym should be a separate defintion so we get the title right
				
				$synonyms = $synonyms_and_forms;
				
				if( strpos( $synonyms, ',' ) > 0 ) {
					$synonyms = explode(',', $synonyms);
					$new_list = array();
					foreach($synonyms as $synonym) {
						$new_list[] = sanitize_text_field($synonym);	
					}
					$synonyms = $new_list;
				} else {
					$synonyms = array( $synonyms );
				}
				foreach( $synonyms as $synonym_or_form ) {
					$new_synonym_or_form = preg_quote($synonym_or_form, '/');
					
					$synonym_matches = array();
					foreach ( $boundaries as $boundary ) {
					
						if( ! empty( $synonym_matches ) ) {
							break;
						}
					
						$synonym_search = '/' . key( $boundary ) . $new_synonym_or_form . current( $boundary ) . '/' . $preg_match_modifiers;
						preg_match( $synonym_search, $content, $synonym_matches );
					}
					
					if( ! empty( $synonym_matches ) ) {
						$thekey = $extra_key + $counter;
						$searches[$thekey] = $synonym_search;
						
						$word = $synonym_or_form .' (' . $definition->post_title . ')';
						
						$replacements[$thekey] = '<span class="explanatory-dictionary-highlight" data-definition="explanatory-dictionary-definition-' . $thekey . '">$0</span>';
						$definitions[] = array( 
							'id' => $thekey, 
							'word' => $word, 
							'explanation' => $definition->post_content 
						);
						$extra_key++;
					}
				}
			}
			$counter++;
		}
		
		// Run the preg_replace on the content
		ksort( $replacements );
		ksort( $searches );
		$content = preg_replace( $searches, $replacements, $content, $settings_list['_limit'] );
		
		// Reinstate the content that we didn't want to filter
		$this->exclude_array;
		
		foreach( $this->exclude_array as $key => $html ) {
			// Debug with: echo htmlspecialchars($key . '=>' . $html) . '<br /><br /><br />';
			$content = preg_replace( '/\~' . $key . '\~/', $html, $content); // puts the content back in
		}
		
		foreach( $this->exclude_array as $key => $html ) {
			$content = preg_replace( '/\~' . $key . '\~/', $html, $content); // passes again for any matches inside matches!
		}
		
		if( count( $definitions ) > 0 ) {
			//Add the matched definitions
			$defs = '
				<aside id="explanatory-dictionary-page-definitions">
					<h2> ' . __( 'Definitioner', self::$plugin_slug ) . ' </h2>
					<dl>
			';
			
			foreach( $definitions as $definition ) {
				$defs .= '
					<dt class="explanatory-dictionary-definition-' . $definition['id'] . '">' . $definition['word'] . '</dt>
					<dd class="explanatory-dictionary-definition-' . $definition['id'] . '">' . do_shortcode( $definition['explanation'] ) . '</dd>
				';
			}
			// Add the definitions to the end of the_content
			$defs .= '
					</dl>
				</aside>
			';
			
			$this->definitioner = $defs;
			
			// enqueue the script because we have definitions
			wp_enqueue_script( self::$plugin_slug_safe . '-qtip' );
			wp_enqueue_style( self::$plugin_slug_safe . '-qtip' );
			
			wp_enqueue_script( self::$plugin_slug_safe . '-qtip-script' );
			
			if( 'no' === $settings_list['_external_css_file'] ) {
				wp_enqueue_style( self::$plugin_slug_safe . '-custom-settings' );
			}
			
		}
		
		return $content;
	}
	
	/**
	 * Add the definitioner to the end of the page to prevent multiple instances
	 * 
	 * @since 4.0.1
	 */
	public function add_definitioner() {
		if( !empty( $this->definitioner ) ) {
			echo $this->definitioner;
		}
	}
	
	private function _can_show_tooltips() {
		
		$settings_list = Explanatory_Dictionary_Settings::get_settings_list();
		$show_on_home = ( 'yes' == $settings_list['_show_on_homepage'] ) ? true : false;
		
		// Show on the homepage if the user so desires
		if( !$show_on_home && is_front_page() ) {
			return false;
		}
		
		$exclude = $settings_list['_exclude'];
		global $post;
		
		if( is_page() ) {
			if( isset( $exclude['pages'] ) && in_array( $post->ID, $exclude['pages'] ) ) {
				return false;
			}
		} else {
			if( isset( $exclude['posts'] ) && in_array( $post->ID, $exclude['posts'] ) ) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Helper for preg_replace_callback
	 * Adds content we don't want to filter to a global array to reintate later
	 * Replaces the content with a placeholder that has the same key as the array record
	 * 
	 * @since 4.0.0
	 */
	public function store_content_to_avoid( $matches ) {
		$this->exclude_array[] = $matches[0]; // adds matched content to the array
		end($this->exclude_array);
		return '~' . key($this->exclude_array) . '~'; // this is the placeholder
	}
	
	/*----------------------------------------------------------------------------*
	 * DEPRECATED functions
	*----------------------------------------------------------------------------*/


	public function search_results( $content ) {
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		global $wpdb;
		$settings_list = Explanatory_Dictionary_Settings::get_settings_list();
		if ( $settings_list["_search_results"] == 'yes' ) {
	
			if (isset($_GET['s'])) {
				$table_name = $wpdb->prefix . 'explanatory_dictionary';
				$search_term = $_GET['s'];
	
				if ( !empty( $search_term ) ) {
					$results = $wpdb->get_results( "
						SELECT *
						FROM {$table_name}
						WHERE `status` = 1
						AND `word` = '{$search_term}'
						ORDER BY `word`
					" );
					$output = "";
	
					if ( !empty( $results ) ) {
						foreach ( $results as $result ) {
							$output .= $result->word . " - ";
							$output .= $result->explanation;
						}
						return $content = $output;
					} else {
						return $content;
					}
				}
			} else {
				return $content;
			}
		} else {
			return $content;
		}
	}
		
	/**
	 * Get all entries from the database by status
	 *
	 * @since 4.0.0
	 * @param $status int
	 *       	 The status of the entries
	 */
	public function get_all_entries_by_status( $status, $ignore_warning = false ) {
		global $wpdb;
		
		if( false === $ignore_warning ) {
			trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		}
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$result = $wpdb->get_results( $wpdb->prepare( "
			SELECT *
			FROM {$table_name}
			WHERE `status` = %d
		", $status ) );
		
		if ( ! empty( $result ) ) {
			
			$formatted = array();
			foreach ( $result as $row ) {
				$formatted[$row->word] = $row;
			}
			
			return $formatted;
		} else {
			return array();
		}
	}
	
	/**
	 * Get all entries from the database
	 *
	 * @since 4.0.0
	 * @param $status int
	 *       	 The status of the entries
	 */
	public function get_all_entries() {
		global $wpdb;
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$result = $wpdb->get_results( "
			SELECT *
			FROM {$table_name}
		");
		
		if ( ! empty( $result ) ) {
					
			$formatted = array();
			foreach ( $result as $row ) {
				$formatted[$row->word] = $row;
			}
				
			return $formatted;
		} else {
			return array();
		}
	}
	
	/**
	 * Get the number of entries from the database
	 *
	 * @since 4.0.0
	 */
	public function get_number_of_entries( $ignore_warning = false) {
		global $wpdb;
		
		if( false === $ignore_warning ) {
			trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		}
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$wpdb->get_results( "
			SELECT `id`
			FROM {$table_name}
		" );
		
		return $wpdb->num_rows;
	}
	
	/**
	 * Add a new entry to the database
	 *
	 * @since 4.0.0
	 * @param $word string
	 *       	 The word
	 * @param $synonyms text
	 *       	 The synonyms of the word
	 * @param $explanation text
	 *       	 The explanation of the word
	 *
	 */
	 public function add_new_entry( $word, $synonyms, $explanation ) {
		global $wpdb;
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		if( ! empty( $word ) ) {
			$table_name = $wpdb->prefix . 'explanatory_dictionary';
			
			$array_synonyms = array();
			if( !empty( $synonyms ) ) {
				$array_synonyms = explode(',', $synonyms );
				array_walk( $array_synonyms, array( 'self' , 'trim' ) );
				$array_synonyms = array_unique( $array_synonyms );
				if( ! empty( $array_synonyms ) ){
					foreach( $array_synonyms as $key => $value ){
						if( strtolower( $value ) == strtolower( $word ) ){
							unset( $array_synonyms[$key] );
						}
					}
				}
			}
		 	
			foreach( $this->get_all_entries() as $existing ) {
				if( $word == $existing->word ) {
					return __( 'The word you are trying to define already exists', self::$plugin_slug_safe );
				}
				if( !empty( $array_synonyms ) && in_array( $existing->synonyms_and_forms, $array_synonyms ) ) {
					return sprintf( __( 'The synonym for this word is already defined at <i>%s</i> as a synonym', self::$plugin_slug_safe ), $existing->word);
				}
				if( !empty( $array_synonyms ) && in_array( $existing->word, $array_synonyms ) ) {
					return sprintf( __( 'You are trying to define a synonym for this word but <i>%s</i> is already defined as a word', self::$plugin_slug_safe ), $existing->word);
				}
				if( !empty( $existing->synonyms_and_forms ) &&  in_array( $word, maybe_unserialize( $existing->synonyms_and_forms ) ) ) {
					return sprintf( __( 'The word you are trying to define already exists as a synonym at <i>%s</i>', self::$plugin_slug_safe ), $existing->word);
				}
			}
			 	
			if( empty( $array_synonyms ) ) {
				$array_synonyms = '';
			}
			 			
			$insert = $wpdb->insert( $table_name, array(
				'word' => stripcslashes( $word ), 'synonyms_and_forms' => maybe_serialize( $array_synonyms ), 'explanation' => stripcslashes( $explanation )
				), array(
				'%s', '%s', '%s'
			) );
			
			return $insert;
		}
	}
	
	/**
	 * Update an entry in the database
	 *
	 * @since 4.0.0
	 * @param $id int
	 *       	 The id of the entry
	 * @param $word string
	 *       	 The word
	 * @param $synonyms text
	 *       	 The synonyms of the word
	 * @param $explanation text
	 *       	 The explaation of the word
	 *
	 */
	public function update_entry( $entry_id, $word, $synonyms, $explanation, $status ) {
		global $wpdb;
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		if( !empty( $word ) ) {
			$table_name = $wpdb->prefix . 'explanatory_dictionary';
				
			$arrasy_synonyms = array();
			if( !empty( $synonyms ) ) {
				if( strlen( $synonyms ) > 1 ){
					$array_synonyms = explode(',', $synonyms );
					array_walk( $array_synonyms, array( 'self' , 'trim' ) );
					$array_synonyms = array_unique( $array_synonyms );
					if( ! empty( $array_synonyms ) ){
						foreach( $array_synonyms as $key => $value ){
							if( strtolower( $value ) == strtolower( $word ) ){
								unset( $array_synonyms[$key] );
							}
						}
					}
				}
			}
			
			$current = $this->get_entry( $entry_id );
			foreach( $this->get_all_entries() as $existing ) {
				if( $current->id != $existing->id) {
					if( $word == $existing->word && $current->word != $existing->word ) {
						return __( 'The word you are trying to define already exists', self::$plugin_slug_safe );
					}
					if( !empty( $array_synonyms ) && in_array( $existing->synonyms_and_forms, $array_synonyms ) ) {
						return sprintf( __( 'The synonym for this word is already defined at <i>%s</i> as a synonym', self::$plugin_slug_safe ), $existing->word);
					}
					if( !empty( $array_synonyms ) && in_array( $existing->word, $array_synonyms ) ) {
						return sprintf( __( 'You are trying to define a synonym for this word but <i>%s</i> is already defined as a word', self::$plugin_slug_safe ), $existing->word);
					}
					if( !empty( $existing->synonyms_and_forms ) && in_array( $word, maybe_unserialize( $existing->synonyms_and_forms ) ) ) {
						return sprintf( __( 'The word you are trying to define already exists as a synonym at <i>%s</i>', self::$plugin_slug_safe ), $existing->word);
					}
				}
			}
				
			if( empty( $array_synonyms ) ) {
				$array_synonyms = '';
			}
			
			$update = $wpdb->update( $table_name, array(
				'word' => stripcslashes( $word ), 'synonyms_and_forms' => maybe_serialize( $array_synonyms ), 'explanation' => stripcslashes( $explanation ), 'status' => $status
			), array(
				'id' => $entry_id
			), array(
				'%s', '%s', '%s', '%d'
			), array(
				'%d'
			) );
				
			return $update;
		}
	}
	
	/**
	 * Get an entry object by id
	 *
	 * @since 4.0.0
	 * @param $entry_id int
	 *       	 The id for the entry
	 */
	public function get_entry( $entry_id, $ignore_warning = false ) {
		global $wpdb;
		
		if( false === $ignore_warning ) {
			trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		}
			
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$select = $wpdb->get_row( $wpdb->prepare( "
			SELECT *
			FROM `{$table_name}`
			WHERE `id` = %d
		", $entry_id ) );
		
		return $select;
	}

	/**
	* Delete an entry from the database
	*
	* @since 4.0.0
	* @param $entry_id unknown_type
	* @return unknown
	*/
	public function delete_entry( $entry_id ) {
		global $wpdb;
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$delete = $wpdb->delete( $table_name, array(
			'id' => $entry_id
			), array(
			'%d'
		) );
		
		return $delete;
	}
	
	/**
	* Set the status of an entry 1 = active 0 = inactive
	*
	* @since 4.0.0
	* @param $entry_id unknown_type
	* @return unknown
	*/
	public function set_entry_status( $entry_id, $status ) {
		global $wpdb;
		
		trigger_error( 'the method get_all_entries_by_status is deprecated' , E_USER_DEPRECATED );
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$update = $wpdb->update( $table_name, array(
			'status' => $status
		), array(
			'id' => $entry_id
		), array(
			'%d'
		), array(
				'%d'
		) );
		
		return $update;
	}
	
	/**
	 * Get serialized data and return them comma separated
	 * 
	 * @since 4.0.0
	 * @param string $data
	 */
	function synonyms_output( $data = false, $ignore_warning = false ) {
		
		if( false === $ignore_warning ) {
			trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		}
		
		if( !$data ) {
			return;
		}
	
		$data = maybe_unserialize( $data );
		if( is_array( $data ) ) {
			echo implode( ', ', $data );
		} else {
			echo $data;
		}
	}
	


	/**
	 * Get all letters from the database
	 *
	 * @since 4.0.0
	 * @param array $letters The letters that need to be fetched
	 */
	public function get_dictionary( $letters = array() ) {
		global $wpdb;
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$include = '';
		$counter = 1;
		
		if ( ! empty( $letters ) ) {
			$include .= 'AND (';
			foreach ( $letters as $letter ) {
				$include .= "`word` LIKE '{$letter}%' ";
				
				if ( $counter < count( $letters ) ) {
					$include .= "OR ";
				}
				$counter++;
			}
			$include .= ')';
		}
		
		$result = $wpdb->get_results( "
			SELECT *
			FROM {$table_name}
			WHERE `status` = 1
			{$include}
			ORDER BY `word`
		" );
		
		if ( !empty( $result ) ) {
			
			$formatted = array();
			foreach ( $result as $row ) {
				$formatted[] = $row;
			}
			
			return $formatted;
		} else {
			return array();
		}
	}
	
	/**
	 * Check if the letter has words
	 *
	 * @since 4.0.0
	 * @param string $letter The letter to check
	 */
	public function has_words_for_letter( $letter ) {
		global $wpdb;
		
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		$table_name = $wpdb->prefix . 'explanatory_dictionary';
		
		$result = $wpdb->get_row( "
			SELECT `id`
			FROM `{$table_name}`
			WHERE `word` LIKE '{$letter}%'
			AND `status` = 1
		" );
		
		return ( null === $result ? false : true );
	}
	
	/**
	 * A function for displaying messages in the admin.
	 * It will wrap the message in the appropriate <div> with the
	 * custom class entered. The updated class will be added if no $class is
	 * given.
	 *
	 * @since 4.0.0
	 * @param $class string
	 *       	 Class the <div> should have.
	 * @param $message string
	 *       	 The text that should be displayed.
	 */
	function admin_message( $message, $class = 'updated', $link = '' ) {

		
		
		echo '
			<div class="' . ( ! empty( $class ) ? esc_attr( $class ) : 'updated' ) . '">
				<p><strong>' . $message . '</strong></p>
				' . ( ! empty( $link ) ? '<p>' . $link . '</p>' : '' ) . '
			</div>
		';
	}
	


	public function explanatory_dictionary_shortcode_deprecated( $atts ) {	
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
	
		$this->explanatory_dictionary_shortcode($atts);
	}
	
	public function no_explanation_shortcode_deprecated( $atts ) {
		trigger_error( 'the method ' . __FUNCTION__ . ' is deprecated' , E_USER_DEPRECATED );
		
		$this->no_explanation_shortcode($atts);
	}
}