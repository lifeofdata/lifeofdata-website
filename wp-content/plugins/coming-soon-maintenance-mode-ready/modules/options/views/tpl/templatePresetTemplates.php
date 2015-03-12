<?php if(!empty($this->tplModules)) { ?>
	<ul class="cspTemplatesList">
	<?php foreach($this->tplModules as $tplMod) { ?>
	<li class="cspTemplatePrevShell cspTemplatePrevShell-existing cspTemplatePrevShell-<?php echo $tplMod->getCode()?>" onclick="return setTemplateOptionCsp('<?php echo $tplMod->getCode()?>');">
		<h2 style="text-align: center; color: #454545"><?php echo $tplMod->getLabel()?></h2><hr />
		<?php echo htmlCsp::img( $tplMod->getPrevImgPath(), false, array('attrs' => 'class="cspAdminTemplateImgPrev"'));?><hr />
		<input type="submit" onclick="return setTemplateOptionCsp('<?php echo $tplMod->getCode()?>');" class="button button-primary button-large" value="<?php langCsp::_e('Apply')?>">
	</li>
	<?php } ?>
	<?php if(!empty($this->tplModulesPromo)) { ?>
		<?php foreach($this->tplModulesPromo as $tplPromo) { ?>
			<li class="cspTemplatePrevShell">
				<h2 style="text-align: center; color: #454545"><?php echo $tplPromo['label']?></h2><hr />
				<?php echo htmlCsp::img( $tplPromo['img'], false, array('attrs' => 'class="cspAdminTemplateImgPrev"'));?><hr />
				<input type="submit" onclick="window.open('<?php echo $tplPromo['link']?>'); return false;" class="button button-primary button-large" value="<?php langCsp::_e('Available in PRO version')?>">
			</li>
		<?php }?>
	<?php }?>
	</ul>
	<div style="clear: both;"></div>
	<div id="cspAskDefaultModParams" style="display: none;" title="<?php langCsp::_e('Set default template settings?')?>">
		<div><?php langCsp::_e('This template has some default setting. If you want to activate them - just check it')?>:</div>
		<div>
			<?php
				$defOptions = array(
					'background_color' => array('label' => 'Background color', 
						'options' => array('bg_color', 'bg_type')),
					'background_image' => array('label' => 'Background image', 
						'options' => array('bg_image', 'bg_type')),
					'fonts' => array('label' => 'Fonts - types and colors', 
						'options' => array('msg_title_color', 'msg_title_font', 'msg_text_color', 'msg_text_font')),
					'logo' => array('label' => 'Logo image', 
						'options' => array('logo_image'),
						'tip' => 'Be careful: if you already uploaded your logo on server - it will be removed. You will be able then upload it one more time.'),
					'background_slides' => array('label' => 'Background slides',
						'options' => array('slider_images')),
				);
			?>
			<?php foreach($defOptions as $optKey => $optData) { ?>
			<div class="cspTplDefOptionCheckShell">
				<span class="cspDefTplOptCheckbox"><?php echo htmlCsp::checkbox($optKey, array('value' => implode(',', $optData['options'])))?></span> - <?php langCsp::_e($optData['label'])?>
				<?php if(isset($optData['tip'])) { ?>
				<br /><i style="font-size: 12px;"><b><?php langCsp::_e($optData['tip'])?></b></i>
				<?php }?>
			</div>
			<?php }?>
		</div>
	</div>
<?php } else { ?>
	<?php langCsp::_e('No template modules were found'); ?>
<?php }?>