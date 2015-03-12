<form id="cspAdminAccessFormUser">
<table>
	<tr>
		<td width="117"><?php langCsp::_e('Users')?>:</td>
		<td>
			<?php echo htmlCsp::selectlist('selectlistCspUser', array('attrs'=>'style="width:340px;"','options' => $this->arrUser))?>
            <div align="left" class="accessDelElement"><a id="delUserCsp" href="javascript: void(0)"><?php langCsp::_e('remove User')?></a></div>
        </td>
	</tr>
	<tr>
		<td></td>
		<td>
                <?php echo htmlCsp::selectbox( 'userCsp', array('attrs' => '', 'options' => $this->selectUser) ); ?>
        </td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
			<?php echo htmlCsp::hidden('page', array('value' => 'access'))?> 
			<?php echo htmlCsp::hidden('action', array('value' => 'saveUser'))?>
			<?php echo htmlCsp::submit('submitUser', array('value' => langCsp::_('add User'), 'attrs' => 'class="button button-primary button-large"'))?>            
        </td>
	</tr>
</form>
</table>
