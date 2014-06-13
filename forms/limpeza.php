
<?php 
	$igreja = ($_POST['igreja']>0) ? $_POST['igreja']:$_GET['igreja'];
	if ($_POST['item']!='' && $_POST['']!='quant') {
		require_once 'models/cadlimpeza.php';
	}
	$data = (checadata($_GET['data'])) ? $_GET['data']:date('d/m/Y');
	
	if (empty($_GET['mes']) && empty($_GET['ano'])) {
		$periodo = periodoLimp($mesref);
	}else {
		$periodo = periodoLimp($_GET['mesref']);
	}
	
	//Incluir tabela com resumo do pedido
?>
<fieldset>
<legend>Solicita��o de Material de Limpeza, para: <?php echo $periodo['0'];?></legend>
<form method='post' name='limpeza' >
	<table style="width: 100%;">
			<tr>
				<td><label>Item</label>
					<?php 
						$item = new List_sele('limpeza', 'discrim', 'item');
						echo $item->List_Selec(++$ind,'',' class="form-control" ');
					?>
				</td>
				<td><label>Quantidade</label>
					<input type="text" name="quant" placeholder="Quantidade" class="form-control" tabindex="<?PHP echo ++$ind;?>" />
				</td>
				<td><label>Igreja</label>
					<?php 
						$item = new List_sele('igreja', 'razao', 'igreja');
						echo $item->List_Selec(++$ind,$igreja,' class="form-control" requrided="requrided"');
					?>
				</td>
			</tr>
			<tr>
				<td>
					<label>Data:</label>
					<input type="text" name="data" class="form-control" required='required' id='data'
						tabindex="<?PHP echo ++$ind;?>" value="<?php echo $data;?>" maxlength="10" />
				</td>
				<td>&nbsp;<br />
					<a href="./?escolha=controller/limpeza.php&menu=top_tesouraria&limpeza=2&<?php echo $linkperido;?>">
					<button type="button" class="btn btn-primary" >Mostrar totalizador</button></a>
				</td>
				<td>&nbsp;<br />
					<input type="submit"  class="btn btn-primary" name="Submit" value="Enviar..." tabindex="<?PHP echo ++$ind; ?>"/>
					<input type="hidden" name="mes" value="<?PHP echo $_GET['mes'];?>"/>
					<input type="hidden" name="ano" value="<?PHP echo $_GET['ano'];?>"/>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<a href='./?escolha=controller/limpeza.php&menu=top_tesouraria&limpeza=5'><button type="button" class="btn btn-primary">Alterar Per�odo...</button></a>
<a href='./controller/limpeza.php?limpeza=6' target="_blank">
<button type="button" class="btn btn-primary">Material Dispon�vel...</button></a>
<a href='./controller/limpeza.php?limpeza=7' target="_blank">
<button type="button" class="btn btn-primary">Lista para Pedido...</button></a>
</fieldset>

<?php
	if ( $igreja >0) {
		//Lista materiais da congrega��o
		require_once 'views/tabLimpeza.php';
	}
?>
