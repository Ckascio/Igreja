<?php

	require_once 'help/tes/saldoIgrejas.php';	
?>
</div>
<table style="width:100%">
	<caption>
		<?php echo $cong;?>
		Hist�rico Financeiro de D�zimos e Ofertas - Ano de refer�ncia:
		<?php echo $ano;?>
		- Valores em Real(R$)
	</caption>
	<colgroup>
		<?php echo $colgroup;?>
	</colgroup>
	<thead>
		<tr>
			<?php echo $tabThead;?>
		</tr>
	</thead>
	<tbody>
			<?php echo $linha;?>
	</tbody>
	<tfoot>
			<?php echo $tabFoot;?>
	</tfoot>
</table>