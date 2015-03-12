<form action="" method="post" id="cspSubscribeForm">
	<div class="cspFormHint"><?php echo $this->enterEmailMsg?><span class="cspFormHintCorner"></span></div>
	<?php
		if(	$this->sub_name_enable == 1 ){
			echo htmlCsp::text('name', array('attrs' => 'id="cspSubscribeName" placeholder="Your Name" '));
		} 
	?>
	<?php echo htmlCsp::text('email', array('attrs' => 'id="subscribe_email" placeholder="Your Email"'))?>
		<?php echo htmlCsp::hidden('reqType', array('value' => 'ajax'))?>
		<?php echo htmlCsp::hidden('page', array('value' => 'subscribe'))?>
		<?php echo htmlCsp::hidden('action', array('value' => 'create'))?>
		<?php echo htmlCsp::submit('create', array('value' => langCsp::_('Subscribe'), 'attrs' => 'id="cspSubscribeButton"'))?>
	<div id="cspSubscribeCreateMsg"></div>
</form>
