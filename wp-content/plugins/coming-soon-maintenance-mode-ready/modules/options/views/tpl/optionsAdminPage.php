<div id="cspAdminOptionsTabs">
    <h1>
        <?php langCsp::_e('Ready! Coming Soon plugin')?>
    </h1>
	<ul>
		<?php foreach($this->tabsData as $tId => $tData) { ?>
			<li class="<?php echo $tId?>">
				<a href="#<?php echo $tId ?>"><?php langCsp::_e($tData['title'])?></a>
				<?php if(isset($tData['promo']) && $tData['promo']) { ?>
					<small><?php langCsp::_e('Available In PRO')?></small>
				<?php }?>
			</li>
		<?php }?>
	</ul>
	<?php foreach($this->tabsData as $tId => $tData) { ?>
	<div id="<?php echo $tId?>">
		<?php echo $tData['content']; ?>
	</div>
	<?php }?>
</div>
<div id="cspAdminTemplatesSelection"><?php echo $this->presetTemplatesHtml?></div>