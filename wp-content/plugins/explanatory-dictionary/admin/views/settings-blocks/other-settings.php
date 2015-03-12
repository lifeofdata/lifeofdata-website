<table class="form-table">
	<tr>
		<th scope="row">
			<label><?php _e( 'Shortcode', 'explanatory-dictionary' ); ?></label>
		</th>
		<td>
			<input type="text"  class="regular-text" readonly="readonly" value="[explanatory-dictionary]" >
			<p class="description"><?php echo _e( 'Use this shortcode to display the plugin on your page', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_custom_word_styling"><?php _e( 'Use custom styling for terms', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_custom_word_styling" name="settings[_custom_word_styling]" <?php echo ( 'yes' == esc_attr( $settings['_custom_word_styling'] ) ? 'checked="checked"' : '' );?> />
			<p class="description"><?php _e( 'Check if you want to style the terms', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
</table>
<div class="hide-if-custom-word-styling">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="_word_color"><?php _e( 'Term color', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="color-picker" id="_word_color" name="settings[_word_color]" data-default-color="<?php echo esc_attr( $settings['_word_color'] );?>" value="<?php echo esc_attr( $settings['_word_color'] );?>" />
				<p class="description"><?php echo _e( 'Click on the text field to set another color.', 'explanatory-dictionary' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php _e( 'Term style', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="checkbox" value="italic" name="settings[_word_font_style]" id="_font_style" <?php echo ( 'italic' == esc_attr( $settings['_word_font_style'] ) ? 'checked="checked"' : '' );?> />
				<label for="_font_style">Italic</label>
				
				<input type="checkbox" value="bold" name="settings[_word_font_weight]" id="_font_weight" <?php echo ( 'bold' == esc_attr( $settings['_word_font_weight'] ) ? 'checked="checked"' : '' );?> />
				<label for="_font_weight">Bold</label>
				
				<input type="checkbox" value="underline" name="settings[_word_text_decoration]" id="_font_decoration" <?php echo ( 'underline' == esc_attr( $settings['_word_text_decoration'] ) ? 'checked="checked"' : '' );?> />
				<label for="_font_decoration">Underline</label>
			</td>
		</tr>
	</table>
</div>
<table class="form-table">
	<tr>
		<th scope="row"><label for="_search_results"><?php _e( 'Display terms in search results', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_search_results" name="settings[_search_results]" <?php echo ( 'yes' == esc_attr( $settings['_search_results'] ) ? 'checked="checked"' : '' );?> />
			<p class="description"><?php _e( 'Check if you want do display the word and explanation in the search results', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_show_on_homepage"><?php _e( 'Show tooltip on homepage', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_show_on_homepage" name="settings[_show_on_homepage]" <?php echo ( 'yes' == esc_attr( $settings['_show_on_homepage'] ) ? 'checked="checked"' : '' );?> />
			<p class="description"><?php _e( 'Check if you want do display the tooltips on the homepage', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_case_sensitive"><?php _e( 'Make definitions case sensitive', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_case_sensitive" name="settings[_case_sensitive]" <?php echo ( 'yes' == esc_attr( $settings['_case_sensitive'] ) ? 'checked="checked"' : '' );?> />
			<p class="description"><?php _e( 'Check if you want do make the definitions case sensitive', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_heavy_search"><?php _e( 'Heavier search on words to match', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_heavy_search" name="settings[_heavy_search]" <?php echo ( 'yes' == esc_attr( $settings['_heavy_search'] ) ? 'checked="checked"' : '' );?> />
			<p class="description"><?php _e( 'Checking this allows for more words to be found but can also cause parts of words to show as definition', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_use_custom_alphabet"><?php _e( 'Use custom alphabet', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="checkbox" value="yes" id="_use_custom_alphabet" name="settings[_use_custom_alphabet]" <?php echo ( 'yes' == esc_attr( $settings['_use_custom_alphabet'] ) ? 'checked="checked"' : '' );?> />
			<p class="description"><?php _e( 'Check if you want to use a custom alphabet', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
</table>
<div class="hide-if-custom-alphabet">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="_alphabet"><?php _e( 'Explanatory Dictionary Alphabet', 'explanatory-dictionary' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php if( !empty ( $settings['_alphabet'] ) ) { echo esc_attr( $settings['_alphabet'] ); } ?>" id="settings[_alphabet]" name="settings[_alphabet]" <?php if( !empty( $settings['_usedletters'] ) ) { ?>readonly="readonly" style="background-color:#EDEDED;"<?php } ?>>
				<p class="description"><?php echo _e( 'Write here (separate by spaces) the alphabet of your explanatory dictionary (Example: A B C D E F G ...).', 'explanatory-dictionary' ); ?></p>
	
				<input type="checkbox" value="true" id="usedletters" name="settings[_usedletters]" <?php if ( !empty ( $settings['_usedletters'] ) ) echo "checked='checked'";  ?> >
				<p class="description"><?php echo _e( 'If checked the unused letters will not be displayed.', 'explanatory-dictionary' ); ?></p>        
			</td>
		</tr>
	</table>
</div>