<?php
/*
Plugin Name: Advanced Audiboo embed placement
Plugin URI: http://github.com/gandalfar/audioboo-advanced
Description: Provides audioboo_embed() function that creates Audioembed if post has one
Version: 1.1
Author: Jure Cuhalev
Author URI: http://www.jurecuhalev.com/blog
License: GPLv2 or later
*/


add_action("admin_init", "aboo_init");
add_action('save_post', 'aboo_save');

$count = 0;
//[audioboo mp3_url]
function audioboo_shortcode( $atts ){
  global $count;
  $count += 1;
  $audioboo_shortcode = '-'.$count;

  extract( shortcode_atts( array(
    'url' => '',
  ), $atts ) );

  $response = '<span id="audioboo_shortcode'.$count.'"></span>'.
              '<script type="text/javascript">'.
              'jQuery(document).ready(function(){'.
              'var audioboo_clip = /\d+/.exec(\''.$url.'\');'.
              'jQuery(\'#audioboo_shortcode'.$count.'\').audioboo(audioboo_clip);'.
              '});</script>';
  return $response;
}
add_shortcode( 'audioboo', 'audioboo_shortcode' );

function aboo_init(){
        add_meta_box("aboo_adv", "Audioboo widget", "aboo_meta", "post", "normal", "high");
        add_meta_box("aboo_adv", "Audioboo widget", "aboo_meta", "page", "normal", "high");
}

function aboo_meta(){
    global $post;
    $custom = get_post_custom($post->ID);
    $aboo_adv = $custom["aboo_adv"][0];

    ?>
	<div class="inside">
		<p>
			<label for="aboo_url" style="font-weight: bold;">Audioboo URL:</label>
			<input type="text" name="aboo_adv" class="form-input-tip" size="80" autocomplete="off" value<?php if($aboo_adv !== null) { echo "='".$aboo_adv."'";} ?>
		</p>
		<p class="howto">Example URL: http://audioboo.fm/boos/306812-radio-2</br>
      or use: [audioboo url="http://audioboo.fm/boos/306812-radio-2"] in your post.
      </p>
        </div>
        <?php
}


function aboo_save(){
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    return $post_id;
    global $post;
    if($post->post_type == "post" || $post->post_type == "page") {
                update_post_meta($post->ID, "aboo_adv", $_POST["aboo_adv"]);
    }
}

class AudiobooAdvWidget extends WP_Widget {
    function AudiobooAdvWidget() {
        parent::WP_Widget(false, $name = 'Audioboo Widget');
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);

        if (is_page() || is_single()){
		Global $wp_query;
		$post_id = $wp_query->get_queried_object_id();
		$custom = get_post_custom($post->ID);
		$aboo_adv = $custom["aboo_adv"][0];

		if($aboo_adv !== null && $aboo_adv !== '') {
                ?>
                      <?php echo $before_widget; ?>
                          <?php if ( $title )
                                echo $before_title . $title . $after_title; ?>
                                <span id="audioboo_dynamic"></span>
                                <script type="text/javascript">
                            jQuery(document).ready(function(){
                              var audioboo_clip = /\d+/.exec('<?php echo $aboo_adv; ?>');
                              jQuery('#audioboo_dynamic').audioboo(audioboo_clip);
                            });
                                </script>
                      <?php echo $after_widget; ?>
                <?php
		}
            }
    }

    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

        </p>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("AudiobooAdvWidget");'));


function add_scripts() {
    if ( !is_admin() ) {
        wp_enqueue_script("swfobject");
        wp_enqueue_script('jquery-swfobject.script', '/wp-content/plugins/audioboo-advanced/jquery.swfobject.1-1-1.min.js');
        wp_enqueue_script('jquery.audioboo.script', '/wp-content/plugins/audioboo-advanced/jquery.audioboo.js');
    }
}
add_action('wp_enqueue_scripts', 'add_scripts');

?>
