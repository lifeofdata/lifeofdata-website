<?php wp_nonce_field( Explanatory_Dictionary_Helpers::get_nonce('synonyms'), 'synonyms_nonce' ); ?>
<div class="location_meta_field_100">
	<label for="term-synonyms"><?php _e( 'Synonyms and forms:', 'explanatory-dictionary' ); ?></label><br>
	<input type="text" id="term-synonyms" name="term-synonyms" value="<?php echo esc_attr( $synonyms ); ?>" size="25" class="widefat" />
</div>
<div class="clear"></div>