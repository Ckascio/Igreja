<?php
$tbodytab = new limplista($mesref);
if ($_GET['limpeza']!='4') {
?>
<table id="listTable" >
	<caption>Rela��o do Material de Limpeza Total para: <?php echo $mesref;?></caption>
	
		<colgroup>
			<col id="item">
			<col id="Unidade">
			<col id="Discrimina��o">
			<col id="Solicitado">
			<col id="albumCol"/>
		</colgroup>
	<thead>
		<tr>
			<th scope="col">item</th>
			<th scope="col">Unidade</th>
			<th scope="col">Discrimina��o</th>
			<th scope="col">Total</th>
			<th scope="col">Entregue</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			echo $tbodytab->TotMaterial();
		?>
	</tbody>
</table>
<div style="page-break-before: always;"> </div>
<table id="listTable" >
	<caption>Rela��o do Material de Limpeza Total para Entrega - <?php echo $mesref;?></caption>
	
		<colgroup>
			<col id="item">
			<col id="Unidade">
			<col id="Discrimina��o">
			<col id="Solicitado">
			<col id="albumCol"/>
		</colgroup>
	<thead>
		<tr>
			<th scope="col">item</th>
			<th scope="col">Unidade</th>
			<th scope="col">Discrimina��o</th>
			<th scope="col">Total</th>
			<th scope="col">Entregue</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			echo $tbodytab->TotMatEntregar();
		?>
	</tbody>
</table>
<div style="page-break-before: always;"> </div>
<table id="listTable" >
	<caption>Material de Limpeza Total Adquirido no Mercado Autorizado  - <?php echo $mesref;?></caption>	
		<colgroup>
			<col id="item">
			<col id="Unidade">
			<col id="Discrimina��o">
			<col id="Solicitado">
			<col id="albumCol"/>
		</colgroup>
	<thead>
		<tr>
			<th scope="col">item</th>
			<th scope="col">Unidade</th>
			<th scope="col">Discrimina��o</th>
			<th scope="col">Total</th>
			<th scope="col">Entregue</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			echo $tbodytab->TotMatPegar();
		?>
	</tbody>
</table>
<div style="page-break-before: always;"> </div>
<?php 
}
	require_once $todascongreg;
	if ($dadoscong->matlimpeza()=='0') {
		//Assinatura do tesoureiro(a) e elador(a)
	?>
	<div id="added-div-2">
      <h2><?PHP  print $dadoscong->cidade()." - ".$dadoscong->uf().", ".data_extenso (date('d/m/Y'));?></h2>
      <p>&nbsp;</p>
      <p class="bottom">&nbsp;</p>
	  <div id="pastor"><?PHP echo 'Joseilton C Bruce';?><br />Tesoureiro </div>
	  <div id="secretario"><?PHP echo '_____________________';?><br />Zelador(a)</div>
	</div>
	<?php 
	}
	?>
