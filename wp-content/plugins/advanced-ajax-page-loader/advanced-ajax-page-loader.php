<?php
/*
Plugin Name: Advanced AJAX Page Loader
Version: 2.6.8
Plugin URI: http://software.resplace.net/WordPress/AjaxPageLoader.php
Description: Load pages within blog without reloading page, shows loading bar and updates the browsers URL so that the user can bookmark or share the url as if they had loaded a page normally. Also updates there history so they have a track of there browsing habbits on your blog!
Author URI: http://dean.resplace.net
Author: deano1987

/// CONSIDER A DONATION: ///////////////////////////////////////////////////////////////////////////////
//                                                                                                    //
//	I have provided quite allot of free help and several updates now to make it easier to integrate   //
//  other JavaScript with this plugin. If this plugin has saved you time then please consider         //
//  sending me a donation through paypal to:                                                          //
//                                                                                                    //
//  dt_8792@yahoo.co.uk                                                                               //
//                                                                                                    //
////////////////////////////////////////////////////////////////////////////////////////////////////////

*/

//useful variables
$uploads = wp_upload_dir();
$GLOBALS['AAPLimages'] = $uploads['basedir'] . '/AAPL';
$GLOBALS['AAPLimagesurl'] = $uploads['baseurl'] . '/AAPL';

//if(!function_exists('get_option'))
  //require_once('../../../wp-config.php');
  
//install - uninstall
register_activation_hook(__FILE__, 'install_AAPL');
//register_deactivation_hook(__FILE__, 'uninstall_AAPL'); not required yet


//admin panel
if (is_admin()) {
	
	add_action('admin_init', 'admin_init_AAPL');
	add_action('admin_menu', 'admin_menu_AAPL');
  
	function admin_init_AAPL() {
		register_setting('AAPL', 'AAPL_upload_error');
		register_setting('AAPL', 'AAPL_version');
		register_setting('AAPL', 'AAPL_content_id');
		register_setting('AAPL', 'AAPL_search_class');
		register_setting('AAPL', 'AAPL_loading_img');
		register_setting('AAPL', 'AAPL_reload_code');
		register_setting('AAPL', 'AAPL_data_code');                     /* eag */
		register_setting('AAPL', 'AAPL_click_code');
		register_setting('AAPL', 'AAPL_ignore_list');
		register_setting('AAPL', 'AAPL_loading_code');
		register_setting('AAPL', 'AAPL_loading_error_code');
		
		
		register_setting('AAPL', 'AAPL_sponsor');
		register_setting('AAPL', 'AAPL_commercial');
		register_setting('AAPL', 'AAPL_js_debug');
		register_setting('AAPL', 'AAPL_jquery_check');
		register_setting('AAPL', 'AAPL_track_analytics');
		
		register_setting('AAPL', 'AAPL_scroll_top');
		
		//Update all tick box's that are unchecked
		if (get_option('AAPL_sponsor') == '') {
			update_option('AAPL_sponsor', 'false');
		}
		if (get_option('AAPL_commercial') == '') {
			update_option('AAPL_commercial', 'false');
		}
		if (get_option('AAPL_js_debug') == '') {
			update_option('AAPL_js_debug', 'false');
		}
		if (get_option('AAPL_jquery_check') == '') {
			update_option('AAPL_jquery_check', 'false');
		}
		if (get_option('AAPL_track_analytics') == '') {
			update_option('AAPL_track_analytics', 'false');
		}
		
		if (get_option('AAPL_scroll_top') == '') {
			update_option('AAPL_scroll_top', 'false');
		}
		
		update_option('AAPL_upload_error', '');
		update_option('AAPL_loading_img', AAPL_options_validate());
		
		//will this fix various problems? hope so.
		install_AAPL();
		
		//upgrade checks :o
		$AAPLupdate = false;
		if (AAPL_get_version() != get_option('AAPL_version')) {
			//version update - inform of update
			update_option('AAPL_version', AAPL_get_version());
			update_option('AAPL_upload_error', 'Stuff was updated for ' . AAPL_get_version() . ' mate!<br>');
			$AAPLupdate = true;
		} else if (isset($_GET['settings-updated'])) {
			$AAPLupdate = true;
		}
		
		//move important file settings to files
		if ($AAPLupdate === true) {
			//perform updates to files
			$data = get_option('AAPL_reload_code');
			$data2 = get_option('AAPL_click_code');
			$data3 = get_option('AAPL_data_code');                  /* eag */
			
			//this is probably better
			$data = 'function AAPL_reload_code() {' . "\n" . '//This file is generated from the admin panel - dont edit here! ' . "\n" . $data . "\n" . '}'. "\n". "\n";
			$data .= 'function AAPL_click_code(thiss) {' . "\n" . '//This file is generated from the admin panel - dont edit here! ' . "\n" . $data2 . "\n" . '}'. "\n". "\n";
			$data .= 'function AAPL_data_code(dataa) {' . "\n" . '//This file is generated from the admin panel - dont edit here! ' . "\n" . $data3 . "\n" . '}';          /* eag */
			
			$file = fopen(plugin_dir_path(__FILE__) . '/reload_code.js', 'w');
			fwrite($file, $data);
			fclose($file);
		}	
	}
  
	function admin_menu_AAPL() {
		add_options_page('Advanced Ajax Page Loader', 'Advanced Ajax Page Loader', 'manage_options', 'AdvancedAjaxPageLoader', 'options_page_AAPL');
	}

	function options_page_AAPL() {
		include(plugin_dir_path(__FILE__) . '/options.php');  
	}
	
	function AAPL_options_validate() {
		//print_r($_FILES);

		if (isset($_FILES['AAPLuploadloader']['name'])) {
			if ($_FILES['AAPLuploadloader']['error'] > 0) {
				update_option('AAPL_upload_error', 'Error: ' . $_FILES['AAPLuploadloader']['error'] . '<br>');
			} else {
				if (($_FILES['AAPLuploadloader']['type'] == 'image/gif') || ($_FILES['AAPLuploadloader']['type'] == 'image/jpeg') || ($_FILES['AAPLuploadloader']['type'] == 'image/png') || ($_FILES['AAPLuploadloader']['type'] == 'image/apng')) {
					if (file_exists($GLOBALS['AAPLimages'] . '/loaders/' . $_FILES['AAPLuploadloader']['name'])) {
						update_option('AAPL_upload_error', 'Exists: ' . $_FILES['AAPLuploadloader']['name'] . '<br>');
					} else {
						move_uploaded_file($_FILES['AAPLuploadloader']['tmp_name'],
						$GLOBALS['AAPLimages'] . '/loaders/' . $_FILES['AAPLuploadloader']['name']);
						
						update_option('AAPL_loading_img', $_FILES['AAPLuploadloader']['name']);
						return $_FILES['AAPLuploadloader']['name'];
						//echo "Stored in: " . $GLOBALS['AAPLimages'] . '/loaders/' . $_FILES['AAPLuploadloader']['name'];
					}
				} else {
					update_option('AAPL_upload_error', 'Bad type: ' . $_FILES['AAPLuploadloader']['type'] . '<br>');
				}
			}
		}
		
		return get_option('AAPL_loading_img');
		
	}

}

// Set Hook for outputting JavaScript
add_action('wp_head', 'insert_head_AAPL');
add_action('wp_footer', 'insert_foot_AAPL');
add_action('wp_enqueue_scripts', 'enqueue_AAPL');

function enqueue_AAPL() {
	//Make sure we use latest jquery?
	//wp_deregister_script('jquery');
    //wp_register_script('jquery', plugins_url( 'jquery.js' , __FILE__ ));
    wp_enqueue_script('jquery');
}

function insert_foot_AAPL() {
	if (strcmp(get_option('AAPL_sponsor'), 'true') == 0) {
		?>
		<center>Proudly Using: <a href='http://software.resplace.net/WordPress/AjaxPageLoader.php' title="Advanced Ajax Page Loader">AAPL</a></center>
		<?php
	}
}

function insert_head_AAPL() {
	//This goes into the header of the site.
	?>
	<script type="text/javascript">
		checkjQuery = <?php echo get_option('AAPL_jquery_check'); ?>;
		jQueryScriptOutputted = false;
		
		//Content ID
		var AAPL_content = '<?php echo get_option('AAPL_content_id'); ?>';
		
		//Search Class
		var AAPL_search_class = '<?php echo get_option('AAPL_search_class'); ?>';
		
		//Ignore List - this is for travisavery who likes my comments... hello
		var AAPL_ignore_string = new String('<?php echo get_option('AAPL_ignore_list'); ?>'); 
		var AAPL_ignore = AAPL_ignore_string.split(', ');
		
		//Shall we take care of analytics?
		var AAPL_track_analytics = <?php echo get_option('AAPL_track_analytics'); ?>
		
		//Various options and settings
		var AAPL_scroll_top = <?php echo get_option('AAPL_scroll_top'); ?>
		
		//Maybe the script is being a tw**? With this you can find out why...
		var AAPL_warnings = <?php echo get_option('AAPL_js_debug'); ?>;
		
		//This is probably not even needed anymore, but lets keep for a fallback
		function initJQuery() {
			if (checkjQuery == true) {
				//if the jQuery object isn't available
				if (typeof(jQuery) == 'undefined') {
				
					if (! jQueryScriptOutputted) {
						//only output the script once..
						jQueryScriptOutputted = true;
						
						//output the jquery script
						//one day I will complain :/ double quotes inside singles.
						//document.write('<scr' + 'ipt type="text/javascript" src="<?php echo plugins_url( 'jquery.js' , __FILE__ );?>"></scr' + 'ipt>');
					}
					setTimeout('initJQuery()', 50);
				}
			}
		}

		initJQuery();

	</script>

	<script type="text/javascript" src="<?php echo plugins_url( 'ajax-page-loader.js' , __FILE__ );?>"></script>
	<script type="text/javascript" src="<?php echo plugins_url( 'reload_code.js' , __FILE__ );?>"></script>
	
	<script type="text/javascript">
		//urls
		var AAPLsiteurl = "<?php echo get_option('home');?>";
		var AAPLhome = "<?php echo get_option('siteurl');?>";
		
		//reload code here
		var AAPLloadingIMG = jQuery('<img/>').attr('src', '<?php echo $GLOBALS['AAPLimagesurl'] . '/loaders/' . get_option('AAPL_loading_img') ;?>');
		var AAPLloadingDIV = jQuery('<div/>').attr('style', 'display:none;').attr('id', 'ajaxLoadDivElement');
		AAPLloadingDIV.appendTo('body');
		AAPLloadingIMG.appendTo('#ajaxLoadDivElement');
		//My code can either be seen as sexy? Or just a terribly orchestrated hack? Really it's up to you...
		
		//Loading/Error Code
		//now using json_encode - two birds one bullet.
		var str = <?php echo json_encode(get_option('AAPL_loading_code')); ?>;
		var AAPL_loading_code = str.replace('{loader}', AAPLloadingIMG.attr('src'));
		str = <?php echo json_encode(get_option('AAPL_loading_error_code')); ?>;
		var AAPL_loading_error_code = str.replace('{loader}', AAPLloadingIMG.attr('src'));
	</script>
	<?php 
}

function install_AAPL() {
	//This is called when the plugin is activated.
	
	if (strcmp(get_option('AAPL_content_id'), '') == 0) {
		update_option('AAPL_content_id', 'content');
	}
	
	if (strcmp(get_option('AAPL_search_class'), '') == 0) {
		update_option('AAPL_search_class', 'searchform');
	}
	
	if (strcmp(get_option('AAPL_version'), '') == 0) {
		update_option('AAPL_version', AAPL_get_version());
	}
	
	if (strcmp(get_option('AAPL_loading_img'), '') == 0) {
		update_option('AAPL_loading_img', 'WordPress Ball Spin.gif');
	}
	
	if (strcmp(get_option('AAPL_js_debug'), '') == 0) {
		update_option('AAPL_js_debug', 'false');
	}
	
	if (strcmp(get_option('AAPL_sponsor'), '') == 0) {
		update_option('AAPL_sponsor', 'false');
	}
	
	if (strcmp(get_option('AAPL_commercial'), '') == 0) {
		update_option('AAPL_commercial', 'false');
	}
	
	if (strcmp(get_option('AAPL_jquery_check'), '') == 0) {
		update_option('AAPL_jquery_check', 'false');
	}
	
	if (strcmp(get_option('AAPL_track_analytics'), '') == 0) {
		update_option('AAPL_track_analytics', 'false');
	}
	
	if (strcmp(get_option('AAPL_scroll_top'), '') == 0) {
		update_option('AAPL_scroll_top', 'true');
	}
	
	if (strcmp(get_option('AAPL_reload_code'), '') == 0) {
		update_option('AAPL_reload_code', '');
	}
	
	if (strcmp(get_option('AAPL_data_code'), '') == 0) {                    /* eag */
		update_option('AAPL_data_code', '');                            /* eag */
	}                                                                       /* eag */
	
	if (strcmp(get_option('AAPL_click_code'), '') == 0) {
		$data =
			'// highlight the current menu item' . "\n" .
			'jQuery(\'ul.menu li\').each(function() {' . "\n\t" .
				'jQuery(this).removeClass(\'current-menu-item\');' . "\n" .
			'});' . "\n" .
			'jQuery(thiss).parents(\'li\').addClass(\'current-menu-item\');';
		update_option('AAPL_click_code', $data);
	}
	
	if (strcmp(get_option('AAPL_loading_code'), '') == 0) {
		$data = 
			'<center>' . "\n\t" .
				'<p style="text-align: center !important;">Loading... Please Wait...</p>' . "\n\t" .
				'<p style="text-align: center !important;">' . "\n\t\t" .
					'<img src="{loader}" border="0" alt="Loading Image" title="Please Wait..." />' . "\n\t" .
				'</p>' . "\n" .
			'</center>';
		update_option('AAPL_loading_code', $data);
	}
	
	if (strcmp(get_option('AAPL_loading_error_code'), '') == 0) {
		$data = 
			'<center>' . "\n\t" .
				'<p style="text-align: center !important;">Error!</p>' . "\n\t" .
				'<p style="text-align: center !important;">' . "\n\t\t" .
					'<font color="red">There was a problem and the page didnt load.</font>' . "\n\t" .
				'</p>' . "\n" .
			'</center>';
		update_option('AAPL_loading_error_code', $data);
	}
	
	if (strcmp(get_option('AAPL_ignore_list'), '') == 0) {
		update_option('AAPL_ignore_list', "#, /wp-, .pdf, .zip, .rar");
	}
	
	//copy ajax loading images across.
	if (!file_exists($GLOBALS['AAPLimages'])) {
		mkdir($GLOBALS['AAPLimages'], 0777);
	}
	
	AAPL_rcopy(plugin_dir_path(__FILE__) . 'loaders', $GLOBALS['AAPLimages'] . '/loaders');
}


//copy directory
function AAPL_rcopy($src, $dst) {
	if (is_dir($src)) {
		if (!file_exists($dst)) {
			mkdir($dst, 0777);
		}
		
		if (file_exists($dst)) {
			$files = scandir($src);
			foreach ($files as $file) {
				if ($file != '.' && $file != '..') {
					AAPL_rcopy("{$src}/{$file}", "{$dst}/{$file}");
					
					//Check if it exists!
					if (!file_exists("{$dst}/{$file}")) {
						echo 'Sorry! Could not copy "' . $src .'/'. $file . '" to "' . $dst .'/'. $file . '", please check the permissions of the destination directory.<br>'."\n";
					}
				}
			}
		} else {
			echo 'Sorry! Could not create the directory "' . $dst .'/", please check the permissions or create this directory manually and de-activate/activate this plugin.<br>'."\n";
		}
	} else if (file_exists($src)) {
		copy($src, $dst);
	}
}

//get plugin version :)
function AAPL_get_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}
?>