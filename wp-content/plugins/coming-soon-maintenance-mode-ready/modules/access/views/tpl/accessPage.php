<div class="wrap">
	<div class="metabox-holder">
		<div class="postbox-container" style="width: 100%;">
			<div class="meta-box-sortables ui-sortable">
                <div id="MSG_EL_ID_Ip"></div>
                
				<div id="idCspMainSubOpts" class="postbox cspAdminTemplateOptRow cspAvoidJqueryUiStyle" style="display: block">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><?php langCsp::_e( 'IP Address whitelist' )?></h3>
					<div class="inside">
						<?php echo $this->blockList[0]; ?>
					</div>
				</div>
                
                <div id="MSG_EL_ID_User"></div>
				<div id="idCspMainSubOpts" class="postbox cspAdminTemplateOptRow cspAvoidJqueryUiStyle" style="display: block">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><?php langCsp::_e( 'Users whitelist' )?></h3>
					<div class="inside">
						<?php echo $this->blockList[1]; ?>
					</div>
				</div>
                
                <div id="MSG_EL_ID_Role"></div>
				<div id="idCspMainSubOpts" class="postbox cspAdminTemplateOptRow cspAvoidJqueryUiStyle" style="display: block">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><?php langCsp::_e( 'Restrict By Role' )?></h3>
					<div class="inside">
						<?php echo $this->blockList[2]; ?>
					</div>
				</div>
                
			</div>
		</div>
	</div>
</div>
<div style="clear: both;"></div>