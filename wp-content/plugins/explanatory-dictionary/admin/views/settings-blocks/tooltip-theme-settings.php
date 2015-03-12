<!-- <style>
.qtip-custom {
	background-color: <?php echo $settings['_content_background'];?>;
	border-color: <?php echo $settings['_border_color'];?>;
	border-style: solid;
	border-width: <?php echo $settings['_border_width'];?>px;
	color: <?php echo $settings['_title_color'];?>;
}

.qtip-custom .qtip-titlebar {
	background-color: <?php echo $settings['_title_background'];?>;
	font-weight: <?php echo $settings['_title_font_weight'];?>;
	font-style: <?php echo $settings['_title_font_style'];?>;
	text-decoration: <?php echo $settings['_title_font_decoration'];?>;
	border-width: 0 0 1px;
}

.qtip-custom .qtip-content {
	text-align: <?php echo $settings['_content_text_align'];?>;
	padding: <?php echo $settings['_content_padding'];?>;
	font-size: <?php echo $settings['_content_font_size'];?>;
	color: <?php echo $settings['_content_color'];?>;
}

.qtip-custom.qtip-rounded {
	border-radius: <?php echo $settings['_border_radius'];?>px;
}

.qtip-custom.qtip-rounded .qtip-titlebar {
	border-radius: <?php echo $settings['_border_radius'] - 1;?>px <?php echo $settings['_border_radius'] - 1;?>px 0 0;
}
</style> -->
<table class="form-table">
	<tr>
		<th scope="row"><label for="_external_css_file"><?php _e( 'External CSS file', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_external_css_file" name="settings[_external_css_file]" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_external_css_file' );?> />
			<p class="description"><?php _e( 'Check if you want to use an external css file. If external CSS file is checked, the style set in the "Explanatory Dictionary Options" will be ignored. To use the style set in "Explanatory Dictionary Options", uncheck this field.', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
</table>
<table id="qtip-themes" class="form-table">
	<tr class="qtip-themes-basic">
		<th scope="row">
			<label><?php _e( 'Basic', 'explanatory-dictionary' ); ?></label>
		</th>
		<td>
		<p class="description"><?php echo _e( 'Select a theme for the tooltip or create a custom one.' , 'explanatory-dictionary' ); ?></p>
			<div class="qtip-container">
				<div class="qtip-default qtip" data-style="qtip-default" data-title="<?php _e( 'Default', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'Default yellow style', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-default" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-default' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-light" data-style="qtip-light" data-title="<?php _e( 'Light', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'Light style, for the minimalists', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-light" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-light' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-dark" data-style="qtip-dark" data-title="<?php _e( 'Dark', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'An opposing dark style', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-dark" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-dark' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-red" data-style="qtip-red" data-title="<?php _e( 'Red', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'A bold attention drawing style', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-red" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-red' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-blue" data-style="qtip-blue" data-title="<?php _e( 'Blue', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'Placid blue, for those informative messages', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-blue" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-blue' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-green" data-style="qtip-green" data-title="<?php _e( 'Green', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'A generic but tasteful green style', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-green" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-green' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-custom" data-style="qtip-custom" data-title="<?php _e( 'Custom', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'A custom styled tooltip', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" id="qtip-custom-theme" class="radio" name="settings[_theme]" value="qtip-custom" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-custom' );?> />
				
			</div>
			<input data-class="qtip-rounded" id="qtip-styling-rounded" type="checkbox" value="yes" name="settings[_enable_rounded]" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_enable_rounded' );?> />
			<label for="qtip-styling-rounded"><?php _e( 'Rounded', 'explanatory-dictionary' ); ?></label>
			<input data-class="qtip-shadow" id="qtip-styling-shadow" type="checkbox" value="yes" name="settings[_enable_shadow]" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_enable_shadow' );?> />
			<label for="qtip-styling-shadow"><?php _e( 'Shadow', 'explanatory-dictionary' ); ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label><?php _e( 'CSS3', 'explanatory-dictionary' ); ?></label>
		</th>
		<td>
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-youtube qtip-shadow" data-style="qtip-youtube" data-title="<?php _e( 'YouTube', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( "Google's YouTube style", 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-youtube" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-youtube' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-tipsy qtip-shadow" data-style="qtip-tipsy" data-title="<?php _e( 'Tipsy', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'Great minimalist Tipsy style', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-tipsy" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-tipsy' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-bootstrap qtip-shadow" data-style="qtip-bootstrap" data-title="<?php _e( 'Bootstrap', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( "Bootstrap your qTip's with this style", 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-bootstrap" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-bootstrap' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-tipped qtip-shadow" data-style="qtip-tipped" data-title="<?php _e( 'Tipped', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'One of the many Tipped library styles', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-tipped" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-tipped' );?> />
			</div>
			
			<div class="qtip-container">
				<div class="qtip-default qtip qtip-jtools qtip-shadow" data-style="qtip-jtools" data-title="<?php _e( 'jTools', 'explanatory-dictionary' ); ?>" oldtitle="<?php _e( 'jTools-style tooltips', 'explanatory-dictionary' ); ?>" title="">
					<div class="qtip-titlebar"></div>
					<div class="qtip-content"></div>
				</div>
				<input type="radio" class="radio" name="settings[_theme]" value="qtip-jtools" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_theme', 'qtip-jtools' );?> />
			</div>
		</td>
	</tr>
</table>
<div class="show-if-custom">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="_border_width"><?php _e( 'Tooltip border width', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_border_width'] );?>" id="_border_width" name="settings[_border_width]" />
				<p class="description"><?php echo _e( 'This option sets the border size (Example: 1, 2, 5 ...) of the explanation tooltip.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_border_color"><?php _e( 'Tooltip border color', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="color-picker" id="_border_color" name="settings[_border_color]" data-default-color="<?php echo esc_attr( $settings['_border_color'] );?>" value="<?php echo esc_attr( $settings['_border_color'] );?>" />
				<p class="description"><?php echo _e( 'Click on the text field to set another border color.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_title_background"><?php _e( 'Tooltip title background color', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="color-picker" id="_title_background" name="settings[_title_background]" data-default-color="<?php echo esc_attr( $settings['_title_background'] );?>" value="<?php echo esc_attr( $settings['_title_background'] );?>" />
				<p class="description"><?php echo _e( 'Click on the text field to set another color.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_content_background"><?php _e( 'Tooltip content background color', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="color-picker" id="_background" name="settings[_content_background]" data-default-color="<?php echo esc_attr( $settings['_content_background'] );?>" value="<?php echo esc_attr( $settings['_content_background'] );?>" />
				<p class="description"><?php echo _e( 'Click on the text field to set another color.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_border_radius"><?php _e( 'Tooltip border radius', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_border_radius'] );?>" id="_border_radius" name="settings[_border_radius]" />
				<p class="description"><?php echo _e( 'This option sets the explanation tooltip border radius (Example: 1, 2, 5 ...).', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<table class="form-table">
	<tr>
		<th scope="row"><label for="_border_width"><?php _e( 'Tooltip location', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<div class="qtip-example-viewport" style="width:600px;position: relative; border:1px dashed #ccc;">
				<div class="qtip-example" style="width: 500px;border:1px solid #000;margin: 50px auto; position:relative;">
					<table>
						<tr>
							<td><?php _e( 'Tooltip position', 'explanatory-dictionary' ); ?></td>
							<td>
								<select id="corner-my-y">
									<option value="top" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_my_y', 'top' );?>><?php _e( 'Top', 'explanatory-dictionary' ); ?></option>
									<option value="center" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_my_y', 'center' );?>><?php _e( 'Center', 'explanatory-dictionary' ); ?></option>
									<option value="bottom" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_my_y', 'bottom' );?>><?php _e( 'Bottom', 'explanatory-dictionary' ); ?></option>
								</select>
								<select id="corner-my-x">
									<option value="left" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_my_x', 'left' );?>><?php _e( 'Left', 'explanatory-dictionary' ); ?></option>
									<option value="center" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_my_x', 'center' );?>><?php _e( 'Center', 'explanatory-dictionary' ); ?></option>
									<option value="right" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_my_x', 'right' );?>><?php _e( 'Right', 'explanatory-dictionary' ); ?></option>
								</select>
								<label for="corner-my-swap"><?php _e( 'Swap', 'explanatory-dictionary' ); ?></label>
								<input id="corner-my-swap" name="settings[_corner_my_swap]" value="yes" type="checkbox" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_corner_my_swap', 'yes' );?>>
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Tooltip position of term', 'explanatory-dictionary' ); ?></td>
							<td>
								<select id="corner-at-y">
									<option value="top" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_at_y', 'top' );?>><?php _e( 'Top', 'explanatory-dictionary' ); ?></option>
									<option value="center" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_at_y', 'center' );?>><?php _e( 'Center', 'explanatory-dictionary' ); ?></option>
									<option value="bottom" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_at_y', 'bottom' );?>><?php _e( 'Bottom', 'explanatory-dictionary' ); ?></option>
								</select>
								<select id="corner-at-x">
									<option value="left" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_at_x', 'left' );?>><?php _e( 'Left', 'explanatory-dictionary' ); ?></option>
									<option value="center" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_at_x', 'center' );?>><?php _e( 'Center', 'explanatory-dictionary' ); ?></option>
									<option value="right" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_at_x', 'right' );?>><?php _e( 'Right', 'explanatory-dictionary' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Tooltip position adjustment', 'explanatory-dictionary' ); ?></td>
							<td>
							<select id="corner-adjust">
								<option value="none" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_adjust', 'none' );?>><?php _e( 'None', 'explanatory-dictionary' ); ?></option>
								<option value="flipinvert" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_adjust', 'flipinvert' );?>><?php _e( 'Flip (invert adjust.x/y)', 'explanatory-dictionary' ); ?></option>
								<option value="flip" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_adjust', 'flip' );?>><?php _e( 'Flip', 'explanatory-dictionary' ); ?></option>
								<option value="shift" <?php echo Explanatory_Dictionary_Helpers::is_selected( '_corner_adjust', 'shift' );?>><?php _e( 'Shift', 'explanatory-dictionary' ); ?></option>
							</select>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<input type="hidden" id="corner-my-y-input" value="<?php echo esc_attr( $settings['_corner_my_y'] );?>" name="settings[_corner_my_y]" />
			<input type="hidden" id="corner-my-x-input" value="<?php echo esc_attr( $settings['_corner_my_x'] );?>" name="settings[_corner_my_x]" />
			<input type="hidden" id="corner-at-y-input" value="<?php echo esc_attr( $settings['_corner_at_y'] );?>" name="settings[_corner_at_y]" />
			<input type="hidden" id="corner-at-x-input" value="<?php echo esc_attr( $settings['_corner_at_x'] );?>" name="settings[_corner_at_x]" />
			<input type="hidden" id="corner-adjust-input" value="<?php echo esc_attr( $settings['_corner_adjust'] );?>" name="settings[_corner_adjust]" />
		</td>
	</tr>
</table>