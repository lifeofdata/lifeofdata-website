<table class="form-table">
	<tr>
		<th scope="row"><label for="_hide_title_from_tooltip"><?php _e( 'Hide title from tooltip', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_hide_title_from_tooltip" name="settings[_hide_title_from_tooltip]" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_hide_title_from_tooltip' );?> />
			<p class="description"><?php _e( 'Check if you want to hide the title of the term when the tooltip is shown', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
</table>
<div class="hide-if-hidden-title">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="_title_use_theme_settings"><?php _e( 'Use styling for title from tooltip', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input data-class="qtip-custom-title" type="checkbox" value="yes" id="_title_use_theme_settings" name="settings[_title_use_theme_settings]" <?php echo Explanatory_Dictionary_Helpers::is_checked( '_title_use_theme_settings' );?> />
				<p class="description"><?php _e( 'Check if you want to use the theme title styling', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<div class="hide-if-hidden-title hide-if-theme-title-styling">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="_title_font_size"><?php _e( 'Title font size', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<span>
					<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_title_font_size'] );?>" id="_title_font_size" name="settings[_title_font_size]" />
					<!--<input type="checkbox" />
					<?php _e( 'Inherit from theme', 'explanatory-dictionary' ); ?>
				--></span>
				
				<p class="description"><?php _e( 'This option sets the font size (Example: 15px, 10pt, 5em, 10% ...) of the explanation tooltip text.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="_title_color"><?php _e( 'Title text color', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="color-picker" id="_title_color" name="settings[_title_color]" data-default-color="<?php echo esc_attr( $settings['_title_color'] );?>" value="<?php echo esc_attr( $settings['_title_color'] );?>" />
				<p class="description"><?php _e( 'Click on the text field to set another color.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php _e( 'Title style', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="checkbox" value="italic" name="settings[_title_font_style]" id="_title_font_style" <?php echo ( 'italic' == esc_attr( $settings['_title_font_style'] ) ? 'checked="checked"' : '' );?> />
				<label for="_title_font_style"><?php _e( 'Italic', 'explanatory-dictionary' ); ?></label>
				
				<input type="checkbox" value="bold" name="settings[_title_font_weight]" id="_title_font_weight" <?php echo ( 'bold' == esc_attr( $settings['_title_font_weight'] ) ? 'checked="checked"' : '' );?> />
				<label for="_title_font_weight"><?php _e( 'Bold', 'explanatory-dictionary' ); ?></label>
	
				<input type="checkbox" value="underline" name="settings[_title_text_decoration]" id="_title_text_decoration" <?php echo ( 'underline' == esc_attr( $settings['_title_text_decoration'] ) ? 'checked="checked"' : '' );?> />
				<label for="_title_text_decoration"><?php _e( 'Underlined', 'explanatory-dictionary' ); ?></label>
			</td>
		</tr>
	</table>
</div>