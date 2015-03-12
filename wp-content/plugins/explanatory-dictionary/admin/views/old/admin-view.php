<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */
$class = Explanatory_Dictionary::get_instance();

$entry = $class->get_entry( $_GET['item'], true );

$current_page = admin_url( "edit.php?post_type=explandict&page=explanatory-dictionary-old-data&action=view&item={$_GET['item']}" );
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( 'View an old item', 'explanatory-dictionary' ); ?></h2>
		<div id="poststuff">
			<table class="form-table">
				<tr>
					<th>
						<label for="entry-word"><?php _e( 'Word (word, expression, sentence)', 'explanatory-dictionary' ); ?></label>
					</th>
					<td>
						<input type="text" id="entry-word" name="entry-word" value="<?php echo $entry->word;?>" size="30" />
						<br />
						<span class="description"><?php _e( '<strong>Required:</strong> The word expression, sentence', 'explanatory-dictionary' ); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="entry-synonyms"><?php _e( 'Synonyms and forms', 'explanatory-dictionary' ); ?></label>
					</th>
					<td>
						<textarea cols="100" rows="5" id="entry-synonyms" name="entry-synonyms"><?php echo $class->synonyms_output( $entry->synonyms_and_forms, true );?></textarea>
						<br />
						<span class="description"><?php _e( '(optional) Separate by commas the words (words expressions, sentences) which has the same explanation.', 'explanatory-dictionary' ); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="entry-explanation"><?php _e( 'Explanation', 'explanatory-dictionary' ); ?></label>
					</th>
					<td>
						<textarea cols="100" rows="5" id="entry-explanation" name="entry-explanation"><?php echo $entry->explanation;?></textarea>
						<br />
						<span class="description"><?php _e( "<strong>Required:</strong> The explanation for this word (words expression, sentence)", 'explanatory-dictionary' ); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="entry-status"><?php _e( 'Active', 'explanatory-dictionary' ); ?></label>
					</th>
					<td>
						<input type="checkbox" id="entry-status" name="entry-status" value="1" <?php echo '1' === $entry->status ? 'checked="checked"' : '' ;?> />
						<br />
						<span class="description"><?php _e( 'When checked the entry is active', 'explanatory-dictionary' ); ?></span>
					</td>
				</tr>
			</table><!-- .form-table -->
		</div><!-- #poststuff -->
		<a href="<?php echo admin_url( 'edit.php?post_type=explandict&page=explanatory-dictionary-old-data' ); ?>"><?php _e( '&larr; Return to the overview', 'explanatory-dictionary'); ?></a>
</div>