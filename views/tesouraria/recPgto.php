<table>
		<caption>
		<?php		
		
		if ($recLink!='' || !empty($recLink)) {
			echo '<a href="'.$recLink.'" ';
			echo 'target="_black" title="Imprimir demonstrativo">';
			echo '<button class="btn btn-default glyphicon glyphicon-print"> </button></a>&nbsp;';
			$imprimir = '';
		}else {
			$imprimir = '<script type="text/javascript">window.print();</script>';
		}
		
		echo $titTabela;
		?>
		</caption>
		<colgroup>
				<col id="Igreja">
				<col id="Fun��o">
				<col id="Nome">
				<col id="Aux�lio/Sal�rio">
				<col id="Dia Pgto">
			</colgroup>
		<thead>
			<tr>
				<th scope="col">Nome</th>
				<th scope="col">Fun��o</th>
				<th scope="col">Igreja</th>
				<th scope="col">Aux�lio/Sal�rio</th>
				<th scope="col">Dia Pgto</th>
			</tr>
		</thead>
		<tbody>
			<?php
				echo $nivel1;
			?>
		</tbody>
		<tfoot>
			<?php 
				echo '<tr id="total">'; 
				echo '<td colspan="3" id="moeda" >Total Geral ---> </td>';
				printf("<td colspan='2' id='moeda'>R$ %s </td></tr>",number_format($debito,2,',','.'));
			?>
		</tfoot>
	</table>
	<?php 
		echo $imprimir;
	?>