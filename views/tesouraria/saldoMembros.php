<?php 
require_once ("views/secretaria/menuTopDados.php");
require_once 'models/tes/histFinMembro.php';
?>
<table>
		<caption>Hist�rico Financeiro de D�zimos e Ofertas - Ano de refer�ncia: 
		<?php echo $ano;?> - Valores em Real(R$)</caption>
		<colgroup>
				<col id="Mes">
				<col id="D�zimos">
				<col id="Ofertas">
				<col id="Miss�es">
				<col id="Senhoras">
				<col id="Mocidade">
				<col id="Infantil">
				<col id="Ensino">
			</colgroup>
		<thead>
			<tr>
				<th scope="col">M�s</th>
				<th scope="col">D�zimos</th>
				<th scope="col">Ofertas</th>
				<th scope="col">Miss�es</th>
				<th scope="col">Senhoras</th>
				<th scope="col">Mocidade</th>
				<th scope="col">Infantil</th>
				<th scope="col">Ensino</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ($_GET['tipo']==1) {
				echo $nivel1;//Valor veio do script /models/saldos.php
			}else {
				echo $nivel1;//Valor veio do script /models/saldos.php
			}				
			?>
		</tbody>
		<tfoot>
			<?php 
				printf("<tr class='total'>"); 
				echo ('<td id="moeda" >Totais: .....</td><td id="moeda">'.number_format($totDizimo,2,',','.').'</td>
					<td id="moeda">'.number_format($totOfertaCultos,2,',','.').'</td><td id="moeda">'.number_format($totMissoes,2,',','.').'</td>');
				echo ('<td id="moeda">'.number_format($totSenhoras,2,',','.').'</td><td id="moeda">'.number_format($totMocidade,2,',','.').'</td>
				<td id="moeda">'.number_format($totInfantil,2,',','.').'</td><td id="moeda">'.number_format($totEnsino,2,',','.').'</td></tr>');
			?>
		</tfoot>
	</table>
	Em: <?php echo date('d/m/Y');?>