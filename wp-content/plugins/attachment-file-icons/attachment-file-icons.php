<?php
/* 	
Plugin Name: Attachment File Icons (AF Icons) (SLOD Fork)
Description: A plugin to display file type icons adjacent to files added to pages/posts/widgets. Feature to upload icons for different file types provided. Please refer plugin overview page for details. (SLOD Fork)
Author: Praveen Rajan (SLOD Fork)
Version: 1.3.SLOD 
License: GPLv2
	Copyright 2014  Praveen Rajan

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    
		SLOD Fork: Added global $_slod_in_post_body; is this is set to true, the rewriting of icons is disabled.
*/
if (!class_exists("AttachmentFileIcons"))
{
	class AttachmentFileIcons {
		
		//stores plugin url
		var $plugin_url;
		var $plugin_path;
		var $afi_table;
		
		/**
		 * Constructor of plugin
		 * @return void
		 */
		function AttachmentFileIcons(){
			
			$this->plugin_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));
			$this->plugin_path = WP_CONTENT_DIR . '/plugins/' .	dirname( plugin_basename(__FILE__))  ;
			
			global $wpdb;
			$sub_name_type = 'afi_types';
	        $this->afi_table  = $wpdb->prefix . $sub_name_type;
			
			//adds admin menu options to manage
			add_action('admin_menu', array(&$this, 'admin_menu'));
			
			//TODO: Match media icons
			// add_filter( 'wp_get_attachment_image_attributes',  array(&$this,  'afi_alter_attachment_image'));
			
			//adds option to pages/posts content
			add_filter('the_content', array(&$this, 'Attachment_File_Parse'));
			
			//adds option to widget content
			add_filter('widget_text', array(&$this, 'Attachment_File_Parse'));
			
			//adds scripts and css stylesheets
			add_action('wp_print_scripts', array(&$this, 'Attachment_Header_Code'));
			
			//adds contextual help
			add_action('admin_init',  array(&$this, 'add_cvg_contextual_help'));
		
			//add localization support
			// add_action('plugins_loaded', array(&$this, 'afi_localization_init'));	
			
			add_action('init', array(&$this, 'afi_localization_init'));
			
		}
		
		/**
		 * Function to add main menu and submenus to admin panel
		 * @return adds menu
		 */
		function admin_menu() {
			
			add_menu_page(__('Attachment File Icons', 'attachment-file-icons'), 'AF Icons', 'manage_options', 'afi-overview' , array(&$this, 'afi_overview'), $this->plugin_url .'/afi_small.png');
			add_submenu_page( 'afi-overview', __('Attachment File Icons Overview', 'attachment-file-icons'), __('Manage', 'attachment-file-icons'), 'manage_options', 'afi-overview',array(&$this, 'afi_overview'));
		}
		
		/**
		 * Function to add contextual help for plugin pages.
		 */
		function add_cvg_contextual_help() {
			
			$help_array = array('toplevel_page_afi-overview', 'af-icons_page_afi-add');
			
			foreach($help_array as $help) {
				
				add_filter('contextual_help', array(&$this, 'afi_contextual_help') , $help, 2);
			}
		}
		
		
		/**
		 * Function to add contextual help for each menu
		 * 
		 * @param $contextual_help - Contextual Help
		 * @param $screen_id - Screen Id
		 */
		function afi_contextual_help( $contextual_help, $screen_id) {
			
			$help_content = '<p><b>Instructions to use Attachment File Icons<i>(AF Icons)</i>:</b></p>';
			$help_content .= '<p><ol><li>Upload file to posts/pages using media upload icon.</li>'.
							 '<li>Use button \'Insert into post\' to add uploaded file to posts/pages content.</li>'.
							 '<p>Alternatively: </p><li>Add any link of files to posts/pages/widgets content.</li>'.	
							 '<li>View the post/page to see the effect of AF Icons*.</li></ol></p>'.
							 '<p style="padding-left:40px;">Sample Preview: <a href="#"><img src="' . $this->plugin_url . '/afi_small.png" /></a> <a href="#">AF Icon File</a></p>';
			
			$help_content = __($help_content, 'attachment-file-icons');
			$screen = get_current_screen();

			$help_array = array('toplevel_page_afi-overview', 'af-icons_page_afi-add');
			
			if(in_array($screen->base, $help_array)) {
			
				$screen->add_help_tab( array(
			        'id'      => $screen_id,
			        'title'   => __( 'Overview', 'attachment-file-icons' ),
			        'content' => $help_content,
			    ));
				
			    return $contextual_help;
			}
		}


		/**
		 * Function to show overview page of plugin
		 */
		function afi_overview(){
			
			global $wpdb;
			
			if(isset($_POST['uploadafi'])){
				$status = true;
				$error_message = '';
				if((trim($_POST['extension_name'])) == ''){
					$error_message .= 'File extension left blank.';
					$status = false;
				}	
				$icon_result = $wpdb->get_var("SELECT icon_extension FROM " .  $this->afi_table . " WHERE icon_extension = '". trim($_POST['extension_name']) . "' ");
				if($icon_result){
					$error_message .= 'Extension type already exist.';
					$status = false;
				}
				if($_FILES['afifiles']['error'][0] == 4) {
					$error_message .= 'No icon file uploaded.';
					$status = false;
				}
				if($status){
					$message = $this->upload_icons();
					if($message == 'success'){
						AttachmentFileIcons::show_video_message("Successfully added icon for file type.");
					}else {
						AttachmentFileIcons::show_video_error($message);
					}	
				}else {
					AttachmentFileIcons::show_video_error($error_message);
				}		
			}

			//Section to delete a single icon
			if(isset($_POST['TB_iconsingle']) && !empty($_POST['TB_iconsingle']) && $_POST['TB_DeleteSingle'] == 'OK') {
				$id = $_POST['TB_iconsingle'];
				$icon_result = $wpdb->get_var("SELECT icon_name FROM " .  $this->afi_table . " WHERE id = '$id' ");
				$status = false;
				if (function_exists('is_multisite') && is_multisite()) {
					
					$old_blog = $wpdb->blogid;
					// Get all blog ids
					$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id);
						AttachmentFileIcons::_afi_delete($id);
					}
					switch_to_blog($old_blog);
					$status = true;
				}else {
					
					$wpdb->query("DELETE FROM ". $this->afi_table ." WHERE id = '$id'");
					$status = true;	
				}
				
				if(	$status ){
					@unlink($this->plugin_path . '/mime/' . $icon_result);
				}
				
				$message = 'Icon ' . $icon_result . ' deleted successfully.';
				AttachmentFileIcons::show_video_message($message);
			}
			
			wp_enqueue_script( 'postbox' );
			
			add_meta_box('afi_overview_details', __('Overview - Available Icons', 'attachment-file-icons'), array($this, 'afi_overview_template') , 'afi-overview-template', 'left', 'core');
			add_meta_box('afi_add_details', __('Upload Icons', 'attachment-file-icons'), array($this, 'afi_add_template'), 'afi-overview-template', 'right', 'core');
			?>
				<script type="text/javascript">
					function showDialogDelete(id) {
						jQuery("#delete_icon_single_value").val(id);
						tb_show("", "#TB_inline?width=200&height=100&inlineId=delete_icon_single_inner&modal=true", false);
					}
					
				</script>
				<script type="text/javascript">
						//<![CDATA[
						jQuery(document).ready( function($) {
							// postboxes setup
							postboxes.add_postbox_toggles('afi-overview-template');
						});
						//]]>
				</script>
				
				<div class="wrap">
					<?php screen_icon( 'attachment-file-icons' );?>
					<h2><?php _e( 'Attachment File Icons', 'attachment-file-icons') ?></h2>	
					<div id="dashboard-widgets-container" class="afi-overview-template">
					    <div id="dashboard-widgets" class="metabox-holder">
							<div id="post-body">
								<div id="dashboard-widgets-main-content">
									<div class="postbox-container" id="main-container" style="width:50%;">
										<?php do_meta_boxes('afi-overview-template', 'left', ''); ?>
									</div>
									<div class="postbox-container" id="side-container" style="width:50%;">
										<?php do_meta_boxes('afi-overview-template', 'right', ''); ?>
									</div>
								</div>
							</div>
					    </div>
					</div>
				</div>
				
				<div id="delete_icon_single" style="display: none;" >
					<div id="delete_icon_single_inner">
					<form id="form-delete-icon_single" method="POST" accept-charset="utf-8" action="<?php echo admin_url('admin.php?page=afi-overview') ; ?>">
						<input type="hidden" id="delete_icon_single_value" name="TB_iconsingle" value="" />
						<table width="100%" border="0" cellspacing="3" cellpadding="3" >
							<tr valign="top">
								<td><strong><?php _e('Delete Icon?', 'attachment-file-icons'); ?></strong></td>
							</tr>
						  	<tr align="center">
						    	<td colspan="2" class="submit">
						    		<input class="button-primary" type="submit" name="TB_DeleteSingle" value="<?php _e('OK', 'attachment-file-icons'); ?>" />
						    		&nbsp;
						    		<input class="button-secondary" type="reset" value="&nbsp;<?php _e('Cancel', 'attachment-file-icons'); ?>&nbsp;" onclick="tb_remove()"/>
						    	</td>
							</tr>
						</table>
					</form>
					</div>
				</div>
			<?php 
		}
		
		function afi_overview_template() {
		
			global $wpdb;
			
			$icon_types = $wpdb->get_results("SELECT * FROM ".  $this->afi_table . " ORDER BY icon_extension", ARRAY_A);
			?>
			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th scope="col"><?php _e('File Extension', 'attachment-file-icons'); ?></th><th scope="col"><?php _e('Icon', 'attachment-file-icons'); ?></th><th scope="col"><?php _e('Action', 'attachment-file-icons'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" ><?php _e('File Extension', 'attachment-file-icons'); ?></th><th scope="col" ><?php _e('Icon', 'attachment-file-icons'); ?></th><th scope="col"><?php _e('Action', 'attachment-file-icons'); ?></th>
					</tr>
				</tfoot>
				<tbody>
				<?php if($icon_types){
						foreach($icon_types as $icon) {
							$class = ( !isset($class) || $class == 'class="alternate"' ) ? '' : 'class="alternate"';
							?>
							<tr <?php echo $class; ?> >
								<td><?php echo $icon['icon_extension'];	?></td>
								<td><img src="<?php echo $this->plugin_url . '/mime/' . $icon['icon_name'];?>" alt="<?php echo $icon['icon_extension'];	?>" /></td>
								<td>
									<a onclick="showDialogDelete(<?php echo $icon['id'] ?>);" href="#" class="delete"><?php _e('Delete', 'attachment-file-icons'); ?></a>
								</td>
							</tr>
						<?php }?>	
				<?php }?>
				</tbody>
			</table>
			<?php
		}
		
		function afi_add_template() {
			
			?>
			<form name="uploadafi" id="uploadafi_form" method="POST" enctype="multipart/form-data" action="<?php echo admin_url('admin.php?page=afi-overview'); ?>" accept-charset="utf-8" >
				<table class="widefat" cellspacing="0">
					
					<tbody>
						<tr valign="top"> 
							<th scope="row"><?php _e('Extension of File', 'attachment-file-icons') ;?></th> 
							<td><input type="text" size="5" name="extension_name" value="" id="extension_name"/></td>
						</tr>
						<tr valign="top"> 
							<th scope="row"><?php _e('Upload Icon', 'attachment-file-icons') ;?></th>
							<td><span id='spanButtonPlaceholder'></span><input type="file" name="afifiles[]" id="afifiles" size="35" class="afifiles"/>
							<br/>
							<i>( <?php _e('Allowed format: png ', 'attachment-file-icons');?><br/><?php _e('Allowed dimension: 16 X 16 px', 'attachment-file-icons') ;?> )</i></td>
						</tr> 
						<tr valign="top">
							<td></td>
							<td>
								<input class="button-primary" type="submit" name="uploadafi" id="uploadafi_btn" value="<?php _e('Upload Icon','attachment-file-icons') ;?>" />
							</td>
						</tr> 
					</tbody>
				</table>
			</form>
			<?php
		}
		
		
		/**
		 * Function to delete data from database in multisite.
		 * @param $id - icon id
		 */
		function _afi_delete($id) {
			
			global $wpdb;
	        $wpdb->query("DELETE FROM $this->afi_table WHERE id = '$id'");
	        
	        return;
		}
		
		/**
		 * Function for uploading 
		 * 
		 * @return void
		 */
		function upload_icons() {
		
			$afifiles = $_FILES['afifiles'];
			$message = '';
			$status = true;
			if (is_array($afifiles)) {
				foreach ($afifiles['name'] as $key => $value) {
					// look only for uploded files
					if ($afifiles['error'][$key] == 0) {
						$temp_file = $afifiles['tmp_name'][$key];
						$image_info = getimagesize($temp_file);
						if($image_info[0] != 16 || $image_info[1] != 16){
							$message .= '<p>Icon file does not meet the specified dimension.</p>';
							$status = false;
							break;
						}
						//clean filename and extract extension
						$filepart = AttachmentFileIcons::fileinfo( $afifiles['name'][$key] );
						$filename = trim($_POST['extension_name']) . '-icon.' .$filepart['extension'];
						$dest_file = $this->plugin_path . '/mime/' . $filename;
						//check for folder permission
						if ( !is_writeable($this->plugin_path . '/mime/') ) {
							$message .= '<p>Unable to write to directory ' . $this->plugin_path . '/mime/ Is this directory writable by the server?</p>';
							$status = false;
							break;
						}
						// save temp file to gallery
						if ( !@move_uploaded_file($temp_file, $dest_file) ){
							$message .= '<p>Error, the file could not moved to : '.$dest_file.'</p>';
							$status = false;
							break;
						} 
						if ( !AttachmentFileIcons::chmod($dest_file) ) {
							$message .= '<p>Error, the file permissions could not set</p>';
							$status = false;
							break;
						}
					}else {
						$message .= '<p>Error uploading file(Missing \'temp\' folder).</p>';
						$status = false;
						break;
					}
				}
				if($status){
					
					global $wpdb;
			        
					$icon_data = array( 'icon_extension' => trim($_POST['extension_name']), 'icon_name' => $filename );
					
					if (function_exists('is_multisite') && is_multisite()) {
						
						$old_blog = $wpdb->blogid;
						// Get all blog ids
						$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
						foreach ($blogids as $blog_id) {
							switch_to_blog($blog_id);
							AttachmentFileIcons::_afi_add($icon_data);
						}
						switch_to_blog($old_blog);
						$status = true;
					}else {
						$wpdb->insert( $this->afi_table, $icon_data ); 
						$status = true;	
					}
					
					if($status)
						return 'success';
				}
			}
			return $message;
		}
		
		/**
		 * Function to add data to database in multisite.
		 * @param $icon_data - icon data
		 */
		function _afi_add($icon_data) {
			
			global $wpdb;
	        $wpdb->insert( $this->afi_table, $icon_data ); 
		}
	
		/**
		 * Function to install afi plugin
		 */
		function afi_install(){
			global $wpdb;
			
			if (function_exists('is_multisite') && is_multisite()) {
				// check if it is a network activation - if so, run the activation function for each blog id
				if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
					$old_blog = $wpdb->blogid;
					// Get all blog ids
					$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id);
						$this->_afi_activate();
					}
					switch_to_blog($old_blog);
					return;
				}
			}
			$this->_afi_activate();
		}		
		
		/**
		 * Function to create database for plugin.
		 */
		function _afi_activate() {
			
			global $wpdb;
			if($wpdb->get_var("SHOW TABLES LIKE '$this->afi_table'") != $this->afi_table) {
				$sql = "CREATE TABLE " . $this->afi_table . " (
						 	  `id` bigint(20) NOT NULL auto_increment,
							  `icon_extension` varchar(255) NOT NULL,
							  `icon_name` mediumtext,
							  PRIMARY KEY  (`id`),
							  UNIQUE KEY `icon_extension` (`icon_extension`)
						);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			$icons_exist = AttachmentFileIcons::scandir_mime($this->plugin_path . '/mime/');
			$initial_icons = array();
			foreach($icons_exist as $icons) {
				$temp_value = explode('-', $icons);
				$initial_icons[] = array( 'icon_extension' => $temp_value[0], 'icon_name' => $icons );
			}
			foreach($initial_icons as $icon) {
				$wpdb->insert( $this->afi_table, $icon ); 
			}
		}
		
		/**
		 * Function to uninstall plugin
		 */
		function afi_uninstall(){
			
			global $wpdb;
			if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
				if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
					$old_blog = $wpdb->blogid;
					// Get all blog ids
					$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id);
						$this->_afi_deactivate();
					}
					switch_to_blog($old_blog);
					return;
				}	
			} 
			$this->_afi_deactivate();
		}
		
		/**
		 * Function to delete tables of plugins
		 */
		function _afi_deactivate() {
			
			global $wpdb;
		  	$wpdb->query("DROP TABLE IF EXISTS $this->afi_table");
		}
		
		
		/**
		 * Function to add document icon to each attachment parsed.
		 * @param $matches - input content
		 * @return string - replaced output string
		 */		
		function Attachment_File_Render($matches){
			
			global $wpdb;
			$sub_name_type = 'afi_types';
	        $this->afi_table  = $wpdb->prefix . $sub_name_type;
			$icon_types = $wpdb->get_results("SELECT * FROM  $this->afi_table", ARRAY_A);
			$ext_array = array(); 
			
			foreach($icon_types as $icon) { 	
				$ext_array[] = $icon['icon_extension']; 
			}  
			
			$arguments = array();
			
			$arguments = $this->Attachment_File_Split($matches[0]);
			$file_link = $arguments['href'];
			$file_ext = "";
			
			foreach($ext_array as $ext) {
				
				$pattern = '/.' . $ext . '/i';
				if (preg_match($pattern, $file_link)) {
					
					$file_ext = $ext;
					break;
				}
			}
			
			if($file_ext != "") {
				
				$image_name = '';
				$sAttachmentString = "";
				
				foreach($icon_types as $icon) {
					if($icon['icon_extension'] == $file_ext){
						$image_name = $icon['icon_name'];
						break;
					}
				}
				
				$sAttachmentString .= "<div class='afi-document'>";
				$sAttachmentString .= "<div class='afi-document-icon'><a href='$file_link'>";
				$sAttachmentString .= "<img src='".$this->plugin_url."/mime/".$image_name."'/>";
				$sAttachmentString .= "</a></div>";
				$sAttachmentString .= "<div class='afi-document-link'>" . $matches[0] . "</div>";
				$sAttachmentString .= "</div><div class='afi-clear'></div>";
				
				return $sAttachmentString;
				
			}else {
				
				return $matches[0]; 
			}
			
		}


		
		/**
		 * Function to split arguments parsed
		 * @param $argument_string - argument to be split
		 * @return array of argument
		 */
		function Attachment_File_Split($argument_string){
			
		    preg_match_all('/(?:[^ =]+?)=(?:["\'].+?["\']|[^ ]+)/', $argument_string, $items);
		    
		    $args = array();
		    foreach ($items[0] as $item){
		        $parts = explode("=", $item);
		        $name = $parts[0];
		        $value = implode("=", array_slice($parts, 1));
		        $args[$name] = strip_tags(trim($value, "\"'"));
		    }
		    return $args;
		}
	
		/**
		 * Function to match the expression
		 * @param $content - input string
		 * @return string - replaced output string
		 */
		function Attachment_File_Parse($content) {
			global $_slod_in_post_body;
			if($_slod_in_post_body != true) {
				$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; 
		 	 	$content = preg_replace_callback("/$regexp/siU",  array(&$this, 'Attachment_File_Render'),  $content);
		 	}
			return $content;
		}
		
		/**
		 * Function to add style to wordpress template.
		 * @return void
		 */
		function Attachment_Header_Code(){
			wp_enqueue_script('jquery.multifile', $this->plugin_url . '/jquery.multifile.js', 'jquery');
			wp_enqueue_script('thickbox',null,array('jquery'));
    		echo '<link rel="stylesheet" href="'. site_url() . '/' . WPINC . '/js/thickbox/thickbox.css" type="text/css" media="screen" />';
			echo '<link rel="stylesheet" href="'. $this->plugin_url . '/attachment-file.css" type="text/css" media="screen" />';
		}
		
		/**
		 * Function to get fileinfo 
		 * 
		 * @param string $name The name being checked. 
		 * @return array containing information about file
		 */
		static function fileinfo( $name ) {
			
			//Sanitizes a filename replacing whitespace with dashes
			$name = sanitize_file_name($name);
			//get the parts of the name
			$filepart = pathinfo ( strtolower($name) );
			if ( empty($filepart) )
				return false;
			// required until PHP 5.2.0
			if ( empty($filepart['filename']) ) 
				$filepart['filename'] = substr($filepart['basename'],0 ,strlen($filepart['basename']) - (strlen($filepart['extension']) + 1) );
			$filepart['filename'] = sanitize_title_with_dashes( $filepart['filename'] );
			$filepart['extension'] = $filepart['extension'];
			//combine the new file name
			$filepart['basename'] = $filepart['filename'] . '.' . $filepart['extension'];
			return $filepart;
		}
		
		/**
		 * Set correct file permissions (taken from wp core)
		 * 
		 * @param string $filename
		 * @return bool $result
		 */
		static function chmod($filename = '') {
	
			$stat = @ stat(dirname($filename));
			$perms = $stat['mode'] & 0007777;
			$perms = $perms & 0000666;
			if ( @chmod($filename, $perms) )
				return true;
				
			return false;
		}
		
		/**
		 * Scan folder for icons
		 * 
		 * @param string $dirname
		 * @return array $files list of video filenames
		 */
		static function scandir_mime( $dirname ) { 
			$ext = array('png', 'PNG'); 
			$files = array(); 
			if( $handle = opendir( $dirname ) ) { 
				while( false !== ( $file = readdir( $handle ) ) ) {
					$info = pathinfo( $file );
					// just look for video with the correct extension
	                if ( isset($info['extension']) )
					    if ( in_array( strtolower($info['extension']), $ext) )
						   $files[] = utf8_encode( $file );
				}		
				closedir( $handle ); 
			} 
			sort( $files );
			return ( $files ); 
		}
		
		/**
		* Show success messages
		*/
		static function show_video_message($message) {
			
			$message_print = '<div class="wrap"><div class="updated fade" id="message"><p>';
			$message_print .= __($message, 'attachment-file-icons');
			$message_print .= '</p></div></div>';
			
			echo $message_print;
		}
		
		/**
		* Show error messages
		*/
		function show_video_error($message) {
			
			$message_print = '<div class="wrap"><h2></h2><div class="error" id="error"><p>';
			$message_print .= __($message, 'attachment-file-icons');
			$message_print .= '</p></div></div>';
	
			echo $message_print;	
		}
		
		/**
		 * Function to enable localization - i18n
		 */
		function afi_localization_init() {
			
			$plugin_dir = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages';
			
 			load_plugin_textdomain( 'attachment-file-icons', false, $plugin_dir );
		} 
		
		
		function afi_alter_attachment_image($attr) {
			
			
		}

	}
}

if (class_exists("AttachmentFileIcons")) {
	
	//Creates object of plugin class
	$AttachmentIcons = new AttachmentFileIcons();
}


if (isset($AttachmentIcons)){
	register_activation_hook( __FILE__, array(&$AttachmentIcons,'afi_install') );
	register_deactivation_hook(__FILE__,  array(&$AttachmentIcons,'afi_uninstall'));
}
?>