<div class="wrap">
	<div id="dashboard-widgets-<?php echo $this->code?>" class="metabox-holder">
		<div id="postbox-container-1-<?php echo $this->code?>" class="postbox-container" style="width: 100%;">
			<div id="normal-sortables-<?php echo $this->code?>" class="meta-box-sortables ui-sortable">
				
				<div id="id_<?php echo $this->code?>_Csp" class="postbox cspAdminTemplateOptRow" style="display: block;">
					<div class="handlediv" title="<?php langCsp::_e( 'Click to toggle' )?>"><br></div>
					<h3 class="hndle"><?php langCsp::_e(array($this->subMod->getLabel(), 'options'))?></h3>
					<div class="inside">
						<form class="cspEngineSubscribeOptionsForm" code="<?php echo $this->code?>">
							<table>
								<?php dispatcherCsp::doAction('subscribe_'. $this->code. '_SettingsStart')?>
								<tr>
									<td colspan="2">
										<?php echo htmlCsp::checkboxHiddenVal('opt_values['. $this->code. '_enabled]', array('checked' => $this->optsModel->get($this->code. '_enabled')))?>
										<label for="opt_values<?php echo $this->code?>_enabled_check" class="button button-large"><?php langCsp::_e('Enabled')?></label>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo htmlCsp::checkboxHiddenVal('opt_values['. $this->code. '_is_main]', array('checked' => $this->optsModel->get($this->code. '_is_main')))?>
										<label for="opt_values<?php echo $this->code?>_is_main_check" class="button button-large"><?php langCsp::_e('Main subscribe engine')?></label>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<i style="font-size: 12px;">
											<?php langCsp::_e('If this option is enabled - '. $this->subMod->getLabel(). ' will be main subscribe engine, so after each subscription subscriber will be managed by '. $this->subMod->getLabel());?>
										</i>
									</td>
								</tr>
								<?php dispatcherCsp::doAction('subscribe_'. $this->code. '_SettingsEnd')?>
								<tr>
									<td>
										<?php echo htmlCsp::hidden('page', array('value' => 'options'))?>
										<?php echo htmlCsp::hidden('action', array('value' => 'saveGroup'))?>
										<?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
										<?php echo htmlCsp::submit('save', array('value' => langCsp::_('Save')))?>
									</td>
									<td class="cspEngineSubscribeOptionsFormMsg"></td>
								</tr>
							</table>
						</form>
					</div>
				</div>

				<div id="id_<?php echo $this->code?>_ExportCsp" class="postbox cspAdminTemplateOptRow" style="display: block;">
					<div class="handlediv" title="<?php langCsp::_e( 'Click to toggle' )?>"><br></div>
					<h3 class="hndle"><?php langCsp::_e($this->subMod->getLabel(). ' synchronization')?></h3>
					<div class="inside">
						<table>
							<tr>
								<td><?php langCsp::_e('Synchronize with '. $this->subMod->getLabel())?>:</td>
								<td>
									<?php echo htmlCsp::button(array('value' => langCsp::_('Synchronize'), 'attrs' => 'class="cspEngineSubscribeSyncButt" code="'. $this->code. '"'))?>
									<span class="cspEngineSubscribeSyncMsg"></span>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<i style="font-size: 12px;">
										<?php langCsp::_e('This will synchronize subscribers list under your '. $this->subMod->getLabel(). ' account and site');?>
									</i>
								</td>
							</tr>
							<tr>
								<td><?php langCsp::_e('Export to '. $this->subMod->getLabel())?>:</td>
								<td>
									<?php echo htmlCsp::button(array('value' => langCsp::_('Export'), 'attrs' => 'class="cspEngineSubscribeExportButt" code="'. $this->code. '"'))?>
									<span class="cspEngineSubscribeSyncMsg"></span>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<i style="font-size: 12px;">
										<?php langCsp::_e('This will export subscriber to your '. $this->subMod->getLabel(). ' account from site');?>
									</i>
								</td>
							</tr>
							<tr>
								<td><?php langCsp::_e('Import from '. $this->subMod->getLabel())?>:</td>
								<td>
									<?php echo htmlCsp::button(array('value' => langCsp::_('Import'), 'attrs' => 'class="cspEngineSubscribeImportButt" code="'. $this->code. '"'))?>
									<span class="cspEngineSubscribeSyncMsg"></span>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<i style="font-size: 12px;">
										<?php langCsp::_e('This will import subscribers from your '. $this->subMod->getLabel(). ' account to site');?>
									</i>
								</td>
							</tr>
						</table>
					</div>
				</div>

			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>