<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */
?>

<?php echo $letters; ?>
<br />
<?php foreach ( $posts as $entry ) : ?>
	<div class="explanatory-dictionary-entry">
		<span class="explanatory-dictionary-entry-word"><?php echo $entry->post_title; ?></span>&nbsp;-&nbsp;
		<span class="explanatory-dictionary-entry-explanation"><?php echo $entry->post_content; ?></span>
		<?php if( !empty( $entry->synonyms ) ) :?>
			<br /><small>- Synonyms: <span class="explanatory-dictionary-entry-synonyms"><?php echo $entry->synonyms; ?></span></small>
		<?php endif;?>
	</div>
<?php endforeach;?>































