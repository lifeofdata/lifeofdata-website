<table width="100%">
    <tr class="cspBodyCells">
        <td>
            <div class="cspLeftCol">
			<?php echo htmlCsp::hidden('opt_values[favico]', array('value' => $this->optsModel->get('favico'),'attrs'=>'id="favico_value_input"'))?>
                <?php langCsp::_e('Favico for site')?>:
                <?php echo htmlCsp::ajaxfile('favico', array('url' =>uriCsp::_(array('baseUrl' => admin_url('admin-ajax.php'), 'page' => 'options', 'action' => 'saveFavico', 'reqType' => 'ajax')),'value'=> $this->optsModel->get('favico'),
						
					'onSubmit' => 'favicoPreLoading',
					'onComplete' => 'favicoUploadComplete'))?>
            </div>
        </td>
		<td>
		<a id='removeFavicoImage'>Remove</a>
			<div class='showUploadedFavico'>
				
				<img src='<?php echo frameCsp::_()->getModule('options')->getFavicoFullPath()?>'/>
				<div class='loader'>
					
				</div>
				<div id="cspAdminMetaOptionsMsg" class="cspSuccessMsg"></div>
			</div>
			
		</td>
    </tr>
</table>
