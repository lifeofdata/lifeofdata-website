<?php echo htmlCsp::checkboxHiddenVal('opt_values[soc_im_enable_link]', array('checked' => $this->optsModel->get('soc_im_enable_link')))?>
<label for="<?php echo 'opt_valuessoc_im_enable_link_check'?>" class="button button-large"><?php langCsp::_e('Enable Link to Account')?></label>
<table width="100%">
	<tr class="cspBodyCells">
		<td>
            <div class="cspLeftCol">
                <?php langCsp::_e('Username')?>:
                <?php echo htmlCsp::text('opt_values[soc_im_account]', array('value' => $this->optsModel->get('soc_im_account')))?>
            </div>
		</td>
    </tr>
</table>
