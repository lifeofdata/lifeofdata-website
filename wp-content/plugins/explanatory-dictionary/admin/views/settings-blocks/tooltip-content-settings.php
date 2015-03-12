<table class="form-table">
	<tr>
		<th scope="row"><label for="_content_use_theme_settings"><?php _e( 'Use styling for content from tooltip', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input data-class="qtip-custom-content" type="checkbox" value="yes" id="_content_use_theme_settings" name="settings[_content_use_theme_settings]" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_content_use_theme_settings' );?> />
			<p class="description"><?php _e( 'Check if you want to use the theme content styling', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
</table>
<div class="hide-if-theme-content-styling">
	<table class="form-table">
		<tr>
			<th scope="row"><label><?php _e( 'Content text align', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="radio" name="settings[_content_text_align]" id="_text_align_left" value="left" <?php echo ( 'left' == esc_attr( $settings['_content_text_align'] ) ? 'checked="checked"' : '' );?> />
				<label for="_content_text_align"><?php _e( 'Left', 'explanatory-dictionary' ); ?></label>
				
				<input type="radio" name="settings[_content_text_align]" id="_text_align_right" value="center" <?php echo ( 'center' == esc_attr( $settings['_content_text_align'] ) ? 'checked="checked"' : '' );?> />
				<label for="_content_text_align"><?php _e( 'Center', 'explanatory-dictionary' ); ?></label>
				
				<input type="radio" name="settings[_content_text_align]" id="_text_align_right" value="right" <?php echo ( 'right' == esc_attr( $settings['_content_text_align'] ) ? 'checked="checked"' : '' );?> />
				<label for="_content_text_align"><?php _e( 'Right', 'explanatory-dictionary' ); ?></label>
				
				<input type="radio" name="settings[_content_text_align]" id="_text_align_justify" value="justify" <?php echo ( 'justify' == esc_attr( $settings['_content_text_align'] ) ? 'checked="checked"' : '' );?> /> 
				<label for="_content_text_align"><?php _e( 'Justify', 'explanatory-dictionary' ); ?></label> 
				<p class="description"><?php _e( 'Select the align of explanation tooltip text.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_content_font_size"><?php _e( 'Content font size', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_content_font_size'] );?>" id="_content_font_size" name="settings[_content_font_size]" />
				<p class="description"><?php _e( 'This option sets the font size (Example: 15px, 10pt, 5em, 10% ...) of the explanation tooltip text.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_content_color"><?php _e( 'Content text color', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="color-picker" id="_content_color" name="settings[_content_color]" data-default-color="<?php echo esc_attr( $settings['_content_color'] );?>" value="<?php echo esc_attr( $settings['_content_color'] );?>" />
				<p class="description"><?php _e( 'Click on the text field to set another color.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_content_padding"><?php _e( 'Content padding', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_content_padding'] );?>" id="_content_padding" name="settings[_content_padding]" />
				<p class="description"><?php _e( 'This option sets the explanation tooltip text padding (Example: 5px 10px, 2pt 5pt 3pt 6pt, 0.5em ...) from borders.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
	</table>
</div>