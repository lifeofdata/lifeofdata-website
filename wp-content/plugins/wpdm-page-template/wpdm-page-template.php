<?php
/*
Plugin Name: WPDM Page Template
Plugin URI: http://wpdownloadmanager.com/downloads/add-ons/
Description: This plugin enables wpdm to add page templates of the page type.
Author: Shaon
Version: 1.0
Author URI: http://wpdownloadmanager.com
*/


class wpdm_page_template {

    function wpdm_page_template() {
        add_action( 'init', array(&$this, 'wpdm_page_template_init') );
        add_action( 'admin_init', array(&$this, 'wpdm_page_template_admin_init') );
        add_action( 'save_post', array(&$this, 'wpdm_page_template_save_post') );
        add_filter( 'template_include', array(&$this, 'wpdm_page_template_template_include') );
        add_action( 'template_redirect', array(&$this, 'wpdm_page_template_template_redirect') );
        add_filter( 'body_class', array(&$this, 'wpdm_page_template_body_classes') );
    }

    function wpdm_page_template_init() {
        if ( function_exists('load_plugin_textdomain') ) {
            if ( !defined('WP_PLUGIN_DIR') ) {
                load_plugin_textdomain( 'wpdm-page-template', str_replace( ABSPATH, '', dirname(__FILE__) ) );
            } else {
                load_plugin_textdomain( 'wpdm-page-template', false, dirname( plugin_basename(__FILE__) ) );
            }
        }
    }

    function wpdm_page_template_admin_init() {


                add_meta_box( 'pagetemplatediv', __('Page Template', 'wpdm-page-template'), array(&$this, 'wpdm_page_template_meta_box'), 'wpdmpro', 'side', 'core');

    }



    function wpdm_page_template_meta_box($post) {
        $template = get_post_meta($post->ID, '_wp_page_template', true);
        ?>
        <label class="screen-reader-text" for="page_template"><?php _e('Page Template', 'wpdm-page-template') ?></label><select name="page_template" id="page_template">
            <option value='default'><?php _e('Default Template', 'wpdm-page-template'); ?></option>
            <?php page_template_dropdown($template); ?>
        </select>
    <?php
    }

    function wpdm_page_template_save_post( $post_id ) {
        if ( !empty($_POST['page_template']) ) :
            if ( $_POST['page_template'] != 'default' ) :
                update_post_meta($post_id, '_wp_page_template', $_POST['page_template']);
            else :
                delete_post_meta($post_id, '_wp_page_template');
            endif;
        endif;
    }

    function wpdm_page_template_template_include($template) {
        global $wp_query, $post;

        if ( is_singular() && !is_page() ) :
            $id = get_queried_object_id();
            $new_template = get_post_meta( $id, '_wp_page_template', true );
            if ( $new_template && file_exists(get_query_template( 'page', $new_template )) ) :
                $wp_query->is_page = 1;
                $templates[] = $new_template;
                return get_query_template( 'page', $templates );
            endif;
        endif;
        return $template;
    }

    function wpdm_page_template_template_redirect() {
        $options = get_option('wpdm_page_template');
        if ( empty($options['enforcement_mode']) ) return;

        global $wp_query;

        if ( is_singular() && !is_page() ) :
            wp_cache_delete($wp_query->post->ID, 'posts');
            $GLOBALS['post']->post_type = 'page';
            wp_cache_add($wp_query->post->ID, $GLOBALS['post'], 'posts');
        endif;
    }

    function wpdm_page_template_body_classes( $classes ) {
        if ( is_singular() && is_page_template() ) :
            $classes[] = 'page-template';
            $classes[] = 'page-template-' . sanitize_html_class( str_replace( '.', '-', get_page_template_slug( get_queried_object_id() ) ) );
        endif;
        return $classes;
    }


}
global $wpdm_page_template;
$wpdm_page_template = new wpdm_page_template();
?>