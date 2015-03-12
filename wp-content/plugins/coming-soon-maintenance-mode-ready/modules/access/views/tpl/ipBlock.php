<form id="cspAdminAccessFormIp">
<table>
	<tr>
		<td width="117"><?php langCsp::_e('IP Address')?>:</td>
		<td>
			<?php echo htmlCsp::selectlist('selectlistCspIp', array('attrs'=>'style="width:340px;"','options' => $this->arrIp))?>
            <div align="left" class="accessDelElement"><a id="delIpCsp" href="javascript: void(0)"><?php langCsp::_e('remove IP Address')?></a></div>
        </td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo htmlCsp::text('ipAddressCsp', array('value' => ''))?></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
			<?php echo htmlCsp::hidden('page', array('value' => 'access'))?> <!--page = для адинки | mod = для сайт-->
			<?php echo htmlCsp::hidden('action', array('value' => 'saveIp'))?> <!--метод-->
			<?php echo htmlCsp::submit('submitIp', array('value' => langCsp::_('add Ip address'), 'attrs' => 'class="button button-primary button-large"'))?>            
        </td>
	</tr>
</table>
</form>
