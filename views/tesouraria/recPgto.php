<table id="horario"  class="table table-bordered">
		<caption>
		<?php
		if ($recLink!='' && !empty($recLink)) {
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
			<?php
				echo $nivel1;
			?>
		<tfoot>
			<?php 
				echo '<tr id="total">'; 
				echo '<td colspan="3" id="moeda" >Total Geral ---> </td>';
				printf("<td colspan='2' id='moeda'>R$ %s </td></tr>",number_format($debito,2,',','.'));
			?>
		</tfoot>
	</table>
	
	
	<table id="horario" class="table table-striped table-bordered">
	<tbody>
		<tr class="sub">
			<th scope="col" colspan="2"></th>
		</tr>
		
	 <tr>
	<td>Todos os ve�culos sobre a via tem condi��es de lomo��o pr�pria?</td>
	<td>
	<div class="has-success"><label class="radio-inline">
	<input type="radio" name="item40" value="S" tabindex='.++$ind.' >
	Sim</label>
	<label class="radio-inline">
	<input type="radio" name="item40" class="dica" data-toggle="modal" data-target="#myModal" value="N">
	N&atilde;o<span>Enviar viatura para o local do acidente!</span></label></div></td>
	
	</tr>
	 <tr>
	<td colspan="2">N�o pode se locomover, qual o motivo?
		<input type="text" name="item41" class="form-control">
	</td>
	</tr>

  </tbody></table>
	
	
	<?php 
		echo $imprimir;
	?>
	
<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
//<![CDATA[
           
	$(document).ready(function() {
		var mais = '<a href="#"><img src="img/mais.gif" alt="Revelar/ocultar cidades" class="maismenos" /></a>'
			$('table#horario tbody tr:not(.sub):even').addClass('impar');			
			$('table#horario tbody tr:not(.sub)').hide();	 
			$('.sub th').css({borderBottom: '1px solid #333'}).prepend(mais);
				$('img', $('.sub th'))
					.click(function(event){
						event.preventDefault();
						if (($(this).attr('src')) == 'img/menos.gif'){
						$(this).attr('src', 'img/mais.gif')
						.parents()
						.siblings('tr').hide(); 
						} else {
						$(this).attr('src', 'img/menos.gif')
						.parents().siblings('tr').show();
						}; 
				});
		});
// ]]>	
</script>