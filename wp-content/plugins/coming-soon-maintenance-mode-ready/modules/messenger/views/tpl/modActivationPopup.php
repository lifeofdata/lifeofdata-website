<div id="toeModActivationPopupShellCsp" style="display: none;">
	<center>
		<form id="toeModActivationPopupFormCsp">
			<label>
				<?php langCsp::_e('Enter your activation key here')?>:
				<?php echo htmlCsp::text('activation_key')?>
			</label>
			<?php echo htmlCsp::hidden('page', array('value' => 'options'))?>
			<?php echo htmlCsp::hidden('action', array('value' => 'activatePlugin'))?>
			<?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
			<?php echo htmlCsp::hidden('plugName')?>
			<?php echo htmlCsp::hidden('goto')?>
			<?php echo htmlCsp::submit('activate', array('value' => langCsp::_('Activate')))?>
			<br />
			<div id="toeModActivationPopupMsgCsp"></div>
		</form>
	</center>
	<i><?php langCsp::_e('To get your keys - go to')?>
		<a target="_blank" href="http://readyshoppingcart.com/my-account/my-orders/">http://readyshoppingcart.com/my-account/my-orders/</a>
		<?php langCsp::_e('and copy & paste key from your ordered extension here.')?>
	</i>
</div>