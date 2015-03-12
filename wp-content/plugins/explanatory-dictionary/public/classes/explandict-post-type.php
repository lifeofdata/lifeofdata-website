<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */

class Explanatory_Dictionary_PostType {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	public static $post_type = 'explandict';
	public static $taxonomy = 'explandict_dictionary';

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
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'create_explandict_post_type' ) ); // create locations post type
		add_action( 'init', array( $this, 'create_explandict_taxonomy' ) );
		add_action( 'init', array( $this, 'update_explandict_post_type' ) );
		
		add_action( 'save_post', array($this, 'explandict_save_custom_meta') ); // Save custom meta boxes
		
		add_filter( 'manage_posts_columns', array( $this, 'manage_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'synonyms_column' ), 10, 2 );
		
		add_filter( 'redirect_post_location', array( $this, 'update_post_redirect' ) );
		
		add_action( 'admin_notices', array( $this, 'explandict_admin_notice' ) );
	}
	
	public function update_explandict_post_type() {
		global $wp_post_types;
		
		$settings = Explanatory_Dictionary_Settings::get_settings_list();
		$exclude_from_search = true;
		if( 'yes' == $settings['_search_results'] ) {
			$exclude_from_search = false;
		}
		
		$wp_post_types[self::$post_type]->exclude_from_search = $exclude_from_search;
	}

	/**
	 * Creates the posttype location for adding the different locations.
	 *
	 * @since     1.0.0
	 */
	public function create_explandict_post_type() {
		global $wp_version;
		
		$icon = 'dashicons-book';
		
		if( $wp_version < '3.8' ) {
			$icon = plugin_dir_url(__FILE__) . '../assets/images/book_open.ico';
		}
		
		// Create posttype
		$labels = array(
			'name'                => _x( 'Explanatory Dictionary', 'explanatory-dictionary' ),
			'singular_name'       => _x( 'Explanatory Dictionary', 'explanatory-dictionary' ),
			'menu_name'           => _x( 'Explanatory Dictionary', 'explanatory-dictionary' ),
			'name_admin_bar'      => _x( 'Term', 'add new on admin bar', 'explanatory-dictionary' ),
			'add_new'             => _x( 'Add New Term', 'explanatory-dictionary' ),
			'add_new_item'        => __( 'Add New Term', 'explanatory-dictionary' ),
			'edit_item'           => __( 'Edit Term', 'explanatory-dictionary' ),
			'new_item'            => __( 'New Term', 'explanatory-dictionary' ),
			'view_item'           => __( 'View Term', 'explanatory-dictionary' ),
			'search_items'        => __( 'Search Terms', 'explanatory-dictionary' ),
			'not_found'           => __( 'No Terms found', 'explanatory-dictionary' ),
			'not_found_in_trash'  => __( 'No Terms found in Trash', 'explanatory-dictionary' ),
			'parent_item_colon'   => __( 'Parent Term:', 'explanatory-dictionary' ),
		);
		
		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => null,
			'menu_icon'           => $icon,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'register_meta_box_cb'=> array( $this, 'add_explandict_meta_boxes' ),
			'supports'            => array( 'title', 'editor' ),
		);

		register_post_type( self::$post_type, $args );
	}
	
	public function create_explandict_taxonomy() {
		
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Dictionaries', 'explanatory-dictionary' ),
			'singular_name'     => _x( 'Dictionary', 'explanatory-dictionary' ),
			'search_items'      => __( 'Search Dictionaries', 'explanatory-dictionary' ),
			'all_items'         => __( 'All Dictionaries', 'explanatory-dictionary' ),
			'parent_item'       => __( 'Parent Dictionary', 'explanatory-dictionary' ),
			'parent_item_colon' => __( 'Parent Dictionary:', 'explanatory-dictionary' ),
			'edit_item'         => __( 'Edit Dictionary', 'explanatory-dictionary' ),
			'update_item'       => __( 'Update Dictionary', 'explanatory-dictionary' ),
			'add_new_item'      => __( 'Add New Dictionary', 'explanatory-dictionary' ),
			'new_item_name'     => __( 'New Dictionary Name', 'explanatory-dictionary' ),
			'menu_name'         => __( 'Dictionaries', 'explanatory-dictionary' ),
		);
	
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'explanatory-dictionary' ),
		);
	
		register_taxonomy( self::$taxonomy, array( self::$post_type ), $args );
		
		// see http://codex.wordpress.org/Function_Reference/register_taxonomy#Usage why we added this rule
		register_taxonomy_for_object_type( self::$taxonomy, self::$post_type );
	}

	public function add_explandict_meta_boxes() {
		add_meta_box( 
			Explanatory_Dictionary::$plugin_slug_safe . '_synonyms', 
			__( 'Synonyms', 'explanatory-dictionary' ), 
			array( $this, 'synonym_meta_box' ),
			self::$post_type, 
			'normal', 
			'default', 
			null
		);
	}

	public function synonym_meta_box( $post ) {
		$synonyms = Explanatory_Dictionary::get_synonyms_post_meta( $post->ID );
		
		include_once( plugin_dir_path( __FILE__ ) . '../../admin/views/meta-boxes/synonyms.php' );
	}

	public function explandict_save_custom_meta( $post_id ) {

		if ( ! isset( $_POST ) || empty( $_POST ) || ! isset( $_POST['post_type'] ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      		return $post_id;
		}

      	if ( self::$post_type != $_POST['post_type'] ) {
      		return $post_id;
      	}

      	if ( self::$post_type == $_POST['post_type'] ) {
      		if ( ! current_user_can( 'edit_post', $post_id ) )
        		return $post_id;
      	} else {
      		if ( ! current_user_can( 'edit_post', $post_id ) )
        		return $post_id;
      	}
      	
      	$term_synonyms = sanitize_text_field( $_POST['term-synonyms'] );
      	
      	if( empty( $term_synonyms ) ) {
      		delete_post_meta( $post_id, Explanatory_Dictionary::$plugin_slug_safe . '_synonyms' );
      		
      		return $post_id;
      	}
      	
      	if( strpos( $term_synonyms, ',' ) ) {
      		$synonyms = explode( ',', $term_synonyms );
      		foreach( $synonyms as $key => $value ) {
      			$synonyms[$key] = sanitize_text_field($value);
      		}
      	} else {
      		$synonyms = array( $term_synonyms );
      	}
      	// We also want to queue the current post because we need to check the title
      	$args = array(
      		'post_type' => self::$post_type,
      		'post_per_page' => -1,
      	);
      	
      	$posts = get_posts( $args );
      	
      	foreach( $posts as $single_post ) {
      		$meta = Explanatory_Dictionary::get_synonyms_post_meta( $single_post->ID );
      		$key = Explanatory_Dictionary::$plugin_slug_safe . '_synonyms';
      		$single_post->$key = $meta;
      		
      		if( $single_post->ID != $post_id) {
      			if( in_array( $single_post->post_title, $synonyms ) ) {
      				// The title exists in the synonyms so we can't update it
      				$_POST['synonyms_error'] = Explanatory_Dictionary_Helpers::SYNONYMS_ERROR_IN_TITLE;
      				return false;
      			}
      			
      			// Check if the post has multipe synonyms
      			if( strpos( $single_post->$key, ',' ) ) {
		      		$single_post_synonyms = explode( $single_post->$key, ',' );
		      		foreach( $single_post_synonyms as $single_post_synonym ) {
		      			$single_post_synonym = trim($single_post_synonym);
		      			
		      			// Check if one of the synonyms is in the array of synonyms the user want's to add 
		      			if( in_array( $single_post_synonym, $synonyms ) ) {
	      					// The synonym exists in the synonyms so we can't update it
      						$_POST['synonyms_error'] = Explanatory_Dictionary_Helpers::SYNONYMS_ERROR_IN_SYNONYM;
	      					return false;
	      				}
		      		}
      			} else {
      				
      				// Check if one of the synonyms is in the array of synonyms the user want's to add 
	      			if( in_array( $single_post->$key, $synonyms ) ) {
      					// The synonym exists in the synonyms so we can't update it
      					$_POST['synonyms_error'] = Explanatory_Dictionary_Helpers::SYNONYMS_ERROR_IN_SYNONYM;
      					return false;
      				}
      			}
      			
      		} else {
      			// This is the current post and we only need to check the title
      			
      			if( in_array( $single_post->post_title, $synonyms ) ) {
      				// The title exists in the synonyms so we can't update it
      				$_POST['synonyms_error'] = Explanatory_Dictionary_Helpers::SYNONYMS_ERROR_IN_TITLE;
      				return false;
      			}
      		}
      	}
      	
      	update_post_meta( $post_id, Explanatory_Dictionary::$plugin_slug_safe . '_synonyms', $term_synonyms );
   	}
   	
   	public function manage_posts_columns( $columns ) {
   		$insert_at = 2;
   		// With this contraption we can add the column anywhere we want
		return array_merge( array_slice( $columns, 0, $insert_at ), array( 'synonyms' => __( 'Synonyms', 'explanatory-dictionary' ) ), array_slice( $columns, $insert_at ) );
   	}
   	
	public function synonyms_column( $column_name, $post_id ) {
	    if ( 'synonyms' == $column_name) {
	        echo Explanatory_Dictionary::get_synonyms_post_meta( $post_id );
	    }
	}
	
	public function update_post_redirect( $location ){
	    if( isset( $_POST['synonyms_error'] ) ) {
	    	$location = add_query_arg( array( 'synonyms_error' => (int)$_POST['synonyms_error'] ), $location );
	    }
	    
	    return $location;
	}
	
	public function explandict_admin_notice() {
		if( isset ($_GET['synonyms_error'] ) ) {
			Explanatory_Dictionary_Helpers::get_message( $_GET['synonyms_error'] );
		}
	}
	
	public static function get_post_type() {
		return self::$post_type;
	}
}