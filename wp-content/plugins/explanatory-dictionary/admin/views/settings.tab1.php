<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */
?>
<div class="wrap">
	<?php echo screen_icon('icon-options-general'); ?>
	<h2>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h2>
	<?php echo $tab_frame; ?>
	<div>
		<form action="" method="post" name="form0">
			<?php wp_nonce_field( Explanatory_Dictionary_Helpers::get_nonce('tab1'), 'tab1_nonce' ); ?>
			<?php do_settings_sections( Explanatory_Dictionary::$plugin_slug.'-general-settings' ); ?>
			<p class="submit">
				<?php submit_button( __( 'Update settings', 'explanatory-dictionary' ), 'primary', 'submit', false ); ?>
				<?php 
				submit_button( 
					__( 'Reset settings', 'explanatory-dictionary' ), 
					'secondary', 
					'reset', 
					false, 
					array (
						'onClick' => "return confirm('" . __( 'Are you sure you want to reset all settings to default?', 'explanatory-dictionary' ) . "');" 
					) 
				); ?>
			</p>
		</form>
	</div>
</div>
