<table >
		<caption>Lan�amento Conclu�do</caption>
		
			<colgroup>
				<col id="Conta">
				<col id="D�bito">
				<col id="Cr�dito">
				<col id="Valor (R$)">
				<col id="albumCol"/>
			</colgroup>
		<thead>
			<tr>
				<th scope="col">Conta</th>
				<th scope="col">D�bito (R$)</th>
				<th scope="col">Cr�dito (R$)</th>
				<th scope="col">Saldo Atual</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php 
					echo $exibideb;//Valor retirado do script models/feccaixaculto.php
					echo $exibicred;//Valor retirado do script models/feccaixaculto.php
				?>
			</tr>
		</tbody>
	</table>
