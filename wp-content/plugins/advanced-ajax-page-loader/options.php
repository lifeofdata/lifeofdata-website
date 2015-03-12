<?php
	//Any modification to remove the donation or commercial messages is not allowed unless you get permission.
$uploads = wp_upload_dir();
$GLOBALS['AAPLimages'] = $uploads['basedir'] . '/AAPL';
$GLOBALS['AAPLimagesurl'] = $uploads['baseurl'] . '/AAPL';

if (get_option('AAPL_upload_error')) {
	echo get_option('AAPL_upload_error');
}
?>
<style>
	.hed {
		font-size:15px !important;
		font-weight:bold !important;
		padding:35px 0px 5px 5px !important;
		border-bottom:1px solid black !important;
	}
	.hed2 {
		font-size:15px !important;
		font-weight:bold !important;
		padding:5px 0px 5px 5px !important;
		border-bottom:1px solid black !important;
	}
	th {
		font-weight:bold !important;
	}
	input, input[type="checkbox"], input[type="radio"] {
		margin-right:5px !important;
	}
</style>
<div class="wrap">
	<h2>Advanced Ajax Page Loader <?=AAPL_get_version();?></h2>
	<?php
		$response = '';
		//manual
			?>
			<?php
			if (strcmp(get_option('AAPL_commercial'), "true")==0) {
				?>
				<a style="text-decoration:none;color:black" href="http://software.resplace.net/WordPress/AAPL/commercial/?domain=<?php echo $_SERVER['SERVER_NAME']; ?>" target="_blank">
					<div style="border:1px solid #720921;background-color: #bcd1ff;border: 1px dashed #497ded;padding:5px;color:black">
						<b>SELECTED: PLUGIN PURCHASED!</b><br><br>
						You have selected that this plugin has been purchased, thanks for your support please click here to check if you REALLY purchased it or not :)
						<div style="clear:right"></div>
					</div>
					<iframe id="commercialcheck" style="position:fixed;top:0px;left:10000px" src="http://software.resplace.net/WordPress/AAPL/commercial/?domain=<?php echo $_SERVER['SERVER_NAME']; ?>&amp;report"></iframe>
				</a>
				<?php 
			} else { 
				?>
				
				<table cellpadding="0" cellspacing="5px" style="font-size:13px;">
					<tr>
						<td valign="middle">
							<div style="border:1px solid #720921;color:#720921; background-color:#f9dbe1 ;padding:5px;margin-left:5px;margin-right:5px;">
								<div style="float:right">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="LQ8EHZDUDSM5A">
										<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
										<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
									</form>
								</div>
								<b>Plugin saved you time?</b><br>
								If this plugin has saved you time consider purchasing it for your website, not only will you be helping the development of this plugin but you will also be able to get the latest paid version of AAPL cheaper when paid version is released.
								<div style="clear:right"></div>
							</div>
							
							<div style="margin-top:5px;text-align: left;background-color: #87d6da;border: 1px dashed #479b9e;padding: 2px;margin-bottom: 8px;padding:5px;">
								<div style="float:right;color:#7a1c1c"><b>From as little as &pound;18ph</b></div>
								<b>Need this script installing/modifying?</b><br>
								We can install this script and integrate it into your website for a small fee, or make modifications/add new features that you require.<br>
								<a href="http://dean.resplace.net/freelance/quote/?script=AAPL" target="_blank" title="script install modification services from as little as &pound;12">Ask for a quote</a>.
							</div>
						</td>
					</tr>
				</table>
				<?php 
			}
		
		?>
	

	<form method="post" action="options.php" enctype="multipart/form-data">
		<?php wp_nonce_field('update-options'); ?>
		<?php settings_fields('AAPL'); ?>

		<table class="form-table">

			<tr valign="top">
				<th colspan="2" class="hed">Main Settings:</th>
			</tr>
			
			<tr>
				<td colspan="2" style="width:100%">
					<table>
						<tr>
							<td style="width:100%">
								<table>
									<tr valign="top">
										<th scope="row">Content Element ID:</th>
										<td>
											<input type="text" name="AAPL_content_id" value="<?php echo get_option('AAPL_content_id'); ?>" style="width:200px;" /><br>
											<i>For most themes this should not need to be changed, however if it does you need to find the container which wraps around the page content (not including any menu bars (vertical and/or horizontal), this container might already have an ID attribute, ie: "&lt;div id=""&gt;&lt;/div&gt;" or you may need to assign one.</i>
										</td>
									</tr>

									<tr valign="top">
										<th scope="row">Search Form CLASS:</th>
										<td><input type="text" name="AAPL_search_class" value="<?php echo get_option('AAPL_search_class'); ?>" style="width:200px;" /><br>
										<i>This plugin automatically binds to the search form with the provided class name (default: searchform), but if this is not set for your theme it will just do a normal post search without ajax.</i>
										</td>
									</tr>
									
									<tr valign="top">
										<th scope="row">Loading Image:</th>
										<td>
											Uploaded:<br>
											<script type="text/javascript">
												function AAPLchangeimg(that) {
													document.getElementById('currentl').src="<?php echo $GLOBALS['AAPLimagesurl'] . '/loaders/'; ?>" + that.options[that.selectedIndex].value;
													//$('#currentl').attr('src','' + $('#selectl option:selected').val());
												}
											</script>
											<select id="selectl" name="AAPL_loading_img" onchange="AAPLchangeimg(this);" style="width:200px;">
												<?php
												$files = scandir($GLOBALS['AAPLimages'] . '/loaders');
												
												foreach ($files as $file) {
													if ($file != "." && $file != "..") {
														AAPL_rcopy("$src/$file", "$dst/$file");
														?>
														<option value="<?php echo $file; ?>" <?php if (strcmp(get_option('AAPL_loading_img'), $file)==0) { echo ' SELECTED="SELECTED" ';} ?>><?php echo $file; ?></option>
														<?php
													}
												}
												?>
											</select><br><br>
											Upload:<br>
											<input type="file" name="AAPLuploadloader" style="width:200px;">
											
										</td>
									</tr>
								</table>
							
							</td>
							<td style="width:250px">
							
								<table>
									<tr>
										<td>
											<div style="float:left; border:1px dashed #464646; padding:10px; margin-left:10px;width:250px;height:330px;">
												<div class="hed2">Current Loading Image:</div><br>
												<div style="width:230px;height:320px;overflow:auto;">
													<img id="currentl" src="<?php echo $GLOBALS['AAPLimagesurl'] . '/loaders/' . get_option('AAPL_loading_img'); ?>" alt="" title="" />
												</div>
											</div>
										</td>
									</tr>
								</table>
							
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Features/Options:</th>
			</tr>
			<tr valign="top">
				<th scope="row">Scroll Page</th>
				<td>
					When a page loads, would you like the page to scroll to the top?<br>
					<input id="AAPL_scroll_top" type="checkbox" name="AAPL_scroll_top" value="true" <?php if (strcmp(get_option('AAPL_scroll_top'), "true")==0) { echo ' CHECKED="CHECKED" ';} ?> /><label for="AAPL_scroll_top">Scroll page to top.</label><br>
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Loading Layout:</th>
			</tr>
			<tr valign="top">
				<td colspan="2">
					Here you can change the HTML of the loading content, there are some special tags you can use:<br>
					<i>{loader} - the loader image as defined above.</i>
					<br><br>
					<textarea name="AAPL_loading_code" style="width:100%;height:140px"><?php echo get_option('AAPL_loading_code'); ?></textarea>
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Loading Error Layout:</th>
			</tr>
			<tr valign="top">
				<td colspan="2">
					Here you can change the HTML of the loading content when an error occurs, there are some special tags you can use:<br>
					<i>{loader} - the loader image as defined above.</i>
					<br><br>
					<textarea name="AAPL_loading_error_code" style="width:100%;height:140px"><?php echo get_option('AAPL_loading_error_code'); ?></textarea>
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Reload Code:</th>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<b><a href="http://software.resplace.net/WordPress/AjaxPageLoader.php" target="_blank">Useful reload codes</a>.</b><br>
					Drop any reload code you need below, if you need any help with this then <a href="http://wordpress.org/extend/plugins/advanced-ajax-page-loader/" target="_blank">ask for help on the WordPress forum</a>.<br>
					<i>Make sure function AAPL_reload_code() { } isnt around the code (if you upgraded from 2.5.0)</i>
					<br><br>
					<textarea name="AAPL_reload_code" style="width:100%;height:250px"><?php echo get_option('AAPL_reload_code'); ?></textarea><br>
					<b>jQuery tip:</b> replace all '$' to 'jQuery'.
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Data Ajax Loaded Code:</th>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<b><a href="http://software.resplace.net/WordPress/AjaxPageLoader.php" target="_blank">Useful get data codes</a>.</b><br>
					This is a special code block to retrieve additional information from the content, the page loaded Ajax. For example, the styles of the body tag, to update the <body> not recharge. <br>
                                        <i>You can access the code loaded through the variable "dataa" or "jQuery(dataa)"</i>
					<br><br>
					<textarea name="AAPL_data_code" style="width:100%;height:250px"><?php echo get_option('AAPL_data_code'); ?></textarea><br>
					<b>jQuery tip:</b> replace all '$' to 'jQuery'.
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Click Code:</th>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<b><a href="http://software.resplace.net/WordPress/AjaxPageLoader.php" target="_blank">Useful click codes</a>.</b><br>
					This is a special code block for code needed to be hooked directly to the element interacted, for example if you need to change the class of a menu item that was clicked, if you need any help with this then <a href="http://wordpress.org/extend/plugins/advanced-ajax-page-loader/" target="_blank">ask for help on the WordPress forum</a>.<br>
					<i>You can access the clicked element using "thiss." or "jQuery(thiss)"</i>
					<br><br>
					<textarea name="AAPL_click_code" style="width:100%;height:250px"><?php echo get_option('AAPL_click_code'); ?></textarea><br>
					<b>jQuery tip:</b> replace all '$' to 'jQuery'.
				</td>
			</tr>
			
			<tr valign="top">
				<th colspan="2" class="hed">Misc Settings:</th>
			</tr>
			<tr valign="top">
				<th scope="row">HREF Ignore List</th>
				<td>
					If you need to ignore certain urls then this is useful for you, insert the full URL, or part of a URL to capture a pattern of links, seperate each with a comma.<br>
					<i>default: "#, /wp-, .pdf, .zip, .rar".</i>
					<br><br>
					<textarea name="AAPL_ignore_list" style="width:100%;height:50px"><?php echo get_option('AAPL_ignore_list'); ?></textarea>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Google Analytics</th>
				<td>
					<input id="AAPL_track_analytics" type="checkbox" name="AAPL_track_analytics" value="true" <?php if (strcmp(get_option('AAPL_track_analytics'), "true")==0) { echo ' CHECKED="CHECKED" ';} ?> /><label for="AAPL_track_analytics">Enable Google Analytics Tracking.</label><br>
					<i>This will only apply the tracking code when you AJAX between pages, so you will need to still include the main tracking code either by hand or using a <a href="http://wordpress.org/extend/plugins/google-analytics-for-wordpress/" target="_blank">Google Analytics plugin</a></i>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Plugin Debug</th>
				<td>
					Fiddle with these options if your having problems...<br><br>
					<input id="AAPL_js_debug" type="checkbox" name="AAPL_js_debug" value="true" <?php if (strcmp(get_option('AAPL_js_debug'), "true")==0) { echo ' CHECKED="CHECKED" ';} ?> /><label for="AAPL_js_debug">Enable JavaScript Debug Messages.</label><br>
					<input id="AAPL_jquery_check" type="checkbox" name="AAPL_jquery_check" value="true" <?php if (strcmp(get_option('AAPL_jquery_check'), "true")==0) { echo ' CHECKED="CHECKED" ';} ?> /><label for="AAPL_jquery_check">Enable jQuery check.</label>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Link to us</th>
				<td>
					Show your support for AAPL and link back to us :)<br><br>
					<input id="AAPL_sponsor" type="checkbox" name="AAPL_sponsor" value="true" <?php if (strcmp(get_option('AAPL_sponsor'), "true")==0) { echo ' CHECKED="CHECKED" ';} ?> /><label for="AAPL_sponsor">Enable footer link.</label><br>
					</td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="AAPL_version" value="<?php echo get_option('AAPL_version'); ?>" />

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

	</form>
</div>