<form actiom="" method="post" id="cspSubscribeForm">
	<label for=""><?php echo $this->enterEmailMsg?></label>:
		<?php if($this->sub_name_enable==1){
		echo htmlCsp::text('name', array('attrs' => 'id="cspSubscribeName" placeholder="Your Name" '));
	} ?> 
	<?php echo htmlCsp::text('email')?><br />
	<div>
		<?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
		<?php echo htmlCsp::hidden('page', array('value' => 'subscribe'))?>
		<?php echo htmlCsp::hidden('action', array('value' => 'create'))?>
		<?php echo htmlCsp::submit('create', array('value' => langCsp::_('Subscribe')))?>
	</div>
	<div id="cspSubscribeCreateMsg"></div>
</form>
