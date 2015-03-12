<?php echo htmlCsp::checkboxHiddenVal('opt_values[soc_yt_enable_link]', array('checked' => $this->optsModel->get('soc_yt_enable_link')))?>
<label for="<?php echo 'opt_valuessoc_yt_enable_link_check'?>" class="button button-large"><?php langCsp::_e('Enable Link to Account')?></label>
<table width="100%">
	<tr class="cspBodyCells">
		<td>
            <div class="cspLeftCol">
                <?php langCsp::_e('Channel Name or Channel ID or Link to your Channel')?>:
                <?php echo htmlCsp::text('opt_values[soc_yt_account]', array('value' => $this->optsModel->get('soc_yt_account')))?>
            </div>
		</td>
    </tr>
</table>
<br />
<br />

<?php echo htmlCsp::checkboxHiddenVal('opt_values[soc_yt_enable_subscribe]', array('checked' => $this->optsModel->get('soc_yt_enable_subscribe')))?>
<label for="<?php echo 'opt_valuessoc_yt_enable_subscribe_check'?>" class="button button-large"><?php langCsp::_e('Enable Subscribe button')?></label>
<table width="100%">
	<tr class="cspBodyCells">
		<td>
            <div class="cspLeftCol">
                <?php langCsp::_e('Layout')?>:
                 <?php echo htmlCsp::selectbox('opt_values[soc_yt_sub_layout]', array(
                    'value' => $this->optsModel->get('soc_yt_sub_layout'), 
                    'options' => array('default' => 'default', 'full' => 'full')))?>
            </div>
			<div class="cspRightCol">
                <?php langCsp::_e('Theme')?>:
                 <?php echo htmlCsp::selectbox('opt_values[soc_yt_sub_theme]', array(
                    'value' => $this->optsModel->get('soc_yt_sub_theme'), 
                    'options' => array('default' => 'default', 'dark' => 'dark')))?>
            </div>
		</td>
    </tr>
</table>