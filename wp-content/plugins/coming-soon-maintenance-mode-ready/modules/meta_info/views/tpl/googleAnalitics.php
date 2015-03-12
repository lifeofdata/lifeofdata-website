<table width="100%">
    <tr class="cspBodyCells">
        <td>
            <div class="GAnaliticsLabel">
                <?php langCsp::_e('Enter Google Analitics code here')?>:
				<small>
					<i>
						<a href='https://support.google.com/analytics/answer/1008080?hl=en' target='_blank'>
							where to get the code?
						</a>
					</i>
				</small>
                <?php echo htmlCsp::textarea('opt_values[google_analitics]', array('value' => $this->optsModel->get('google_analitics'),'rows'=>'5','cols'=>'60'))?>
            </div>
        </td>
    </tr>
</table>