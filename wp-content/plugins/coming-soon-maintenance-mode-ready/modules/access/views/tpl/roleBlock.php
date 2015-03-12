<form id="cspAdminAccessFormRole">
<table>
  <tr>
    <td>Only users at or above this level will be able to log in:</td>
    <td>
    	<?php $selected = frameCsp::_()->getTable('access')->get('access', array('type_access' => 3)); ?>
		<?php echo htmlCsp::selectbox( 'roleCsp', array('attrs' => 'style="float:left; width:120px; margin-right:8px;"',
														'options' => $this->selectRole,
														'value'=> $selected[0]['access']) ); ?>
        <?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
		<?php echo htmlCsp::hidden('page', array('value' => 'access'))?>
		<?php echo htmlCsp::hidden('action', array('value' => 'saveRole'))?>
        <?php echo htmlCsp::submit('submitRole', array('value' => langCsp::_('Save'), 'attrs' => 'class="button button-primary button-large" style="float:right;"'))?>        
    </td>
  </tr>
</table>
</form>