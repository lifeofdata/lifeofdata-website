<?php
/*
	Plugin Name: Storify Stories Slider
	Plugin URI: http://www.tesial.be/
	Description: Display the latest stories of your Storify account within a slider
	Author: Tesial
	Version: 1.1
	Author URI: http://www.tesial.be/
*/
 
/*
	Copyright Tesial (C) 2012 Renaud Laloux (Contact us: http://goo.gl/piab6)
	
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
class StorifyStoriesSliderShortCode {
	
	private static $count = 0;
	public static $defaults = array(
		'name'			=> null,
		'username'		=> null,
		'entries'		=> 3,
		'scroll'		=> 1,
		'orientation'	=> 'horizontal',
		'width'			=> 960,
		'height'		=> 200,
		'type'			=> 'stories',
		'sorting'		=> 'desc'
	);
	
	function StorifyStoriesSliderShortCode() {
		add_action('wp_print_scripts', array(__CLASS__, 'enqueueScripts'));
		add_action('wp_print_styles',  array(__CLASS__, 'enqueueStyles'));
		add_shortcode("storify-stories-slider", array(__CLASS__, "StorifyStoriesSliderShortCode::render"));
	}
	
	function enqueueScripts() {
		wp_enqueue_script('jcarousel', plugins_url('/js/jquery.carousel.min.js', __FILE__), array( 'jquery' ));
		wp_enqueue_script('jcolorbox', plugins_url('/js/jquery.colorbox.min.js', __FILE__), array( 'jquery' ));
		wp_enqueue_script('jprettydate', plugins_url('/js/jquery.prettydate.min.js', __FILE__),   array( 'jquery' ));
		wp_enqueue_script('jstorifystoriesslider', plugins_url('/js/jquery.storify-stories-slider.js', __FILE__), 
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jcarousel', 'jcolorbox', 'jprettydate' ));
	}
	
	function enqueueStyles() {
		wp_enqueue_style('jcolorbox', plugins_url('/css/ui-colorbox/jquery.colorbox.css', __FILE__));
		wp_enqueue_style('jstorifystoriesslider', plugins_url('/css/ui-storify-stories-slider/jquery.storify-stories-slider.css', __FILE__));
	}

	static function render($atts, $content = null) {
		// Extract parameters
		extract(shortcode_atts(StorifyStoriesSliderShortCode::$defaults, $atts));
		
		$entries	 = is_numeric($entries) ? $entries  : $this->defaults['entries'];
		$scroll		 = is_numeric($scroll)  ? $scroll   : $this->defaults['scroll'];
		$height		 = is_numeric($height)  ? $height   : $this->defaults['height'];
		$width		 = is_numeric($width)   ? $width    : $this->defaults['width'];
		$orientation = $orientation == 'horizontal' || $orientation == 'vertical' ? $orientation : $this->defaults['orientation'];
		$scroll      = $scroll <= $entries ? $scroll : $entries;
		$sorting	 = $sorting == 'asc' || $sorting == 'desc' ? $sorting : $this->defaults['sorting'];
		
		// Force the name if empty
		$name = empty($name) ? StorifyStoriesSliderShortCode::$count : $name;
		
		$render = '';
		if (!empty($username)) {
			// There is a small delay implied if there is more than one widget to prevent 500 error from the api.
			$name = preg_replace("/[^a-zA-Z0-9]/", "", $name);

			$render = 
				 "<div id=\"storify-stories-slider-". $name ."\"></div>\n"
				."<script type=\"text/javascript\">\n"
				."setTimeout(function(){"
				."	jQuery(function($){\n"
				."			$('#storify-stories-slider-". $name ."').storifyStoriesSlider({\n"
				."				username: '". $username ."',\n"
				."				entries:   ". $entries .",\n"
				."				scroll: ".$scroll.",\n"
				."				size: { \n"
				."					width:  ".$width.",\n"
				."	 				height: ".$height."\n"
				."				},\n"
				."				orientation: '". $orientation ."',\n"
				."	          type:        '".$type."',\n"
				."				sorting:	 '".$sorting."',\n"
				."				carousel: {\n"
				."			    	vertical: ". ($orientation == 'vertical' ? 'true' : 'false') .",\n"
				."			    	scroll: ".$scroll."\n"
				."			    }\n"
				."			});\n"
				."	});\n"
				."}, ".(StorifyStoriesSliderShortCode::$count * 500).");"
				."</script>";
		}

		StorifyStoriesSliderShortCode::$count++;
		
		return $render;
	}
};

add_action('init', create_function('', 'new StorifyStoriesSliderShortCode();'));
?>