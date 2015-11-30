<?php
//print_r($ctaDespesa->dadosArray());
$ctaDespesa = new tes_despesas();
foreach ($ctaDespesa->dadosArray() as $chave => $valor) {
//Verifica se foi enviado dados para lan�amento, testando e executando
    if ($_POST['acesso'.$chave]>0 && $_POST['disponivel'.$chave]>0 && checadata ($_POST['data'.$chave]) && $_POST['rolIgreja'.$chave]>0 && !empty($_POST['hist'.$chave]) && $_POST['valor'.$chave]>0) {

    	$rolIgreja = $_POST['rolIgreja'.$chave];
    	echo '<script> alert ('.$_POST['acesso'.$chave].')</script>';
    	$valor = (empty($valor_us)) ? strtr( str_replace(array('.'),array(''),$_POST['valor'.$chave]), ',.','.,' ):($valor_us);
		$debitar = $_POST['acesso'.$chave];
		$creditar =  $_POST['disponivel'.$chave];
		//print_r($_POST);

		$referente = (strlen($_POST['hist'.$chave])>'4') ? $_POST['hist'.$chave]:false;//Atribui a vari�vel o hist�rico do lan�amento
		$data = br_data($_POST['data'.$chave]);
		//echo '<br />chave : '.$chave.' - data-> '.$_POST['data'.$chave].' - dt_US:-> '.$data;
		//echo '<br />hist ->'.$_POST['hist'.$chave].' -acesso-> '.$_POST['acesso'.$chave];
		//echo '<br />rolIgreja ->'.$_POST['rolIgreja'.$chave].' -valor ->'.$_POST['valor'.$chave].'<br />';
        # chama o script respons�vel pelo lan�amento
       require 'models/tes/lancModPlanilha.php';
    }
}
$exibicred .= sprintf("<tr class='total'><td>Totais</td><td id='moeda'>R$ %s</td><td id='moeda'>R$ %s</td><td></td></tr>",number_format($totalDeb,2,',','.'),number_format($totalCred,2,',','.'));

$ctaDespesa = new tes_despesas();
$bsccredor = new tes_listDisponivel();
$arrayDesp = $ctaDespesa->despesasArray($mesEstatisca,$ano);
//Monta as linhas da tabela respons�vel pelas despesas ja lan�adas no m�s
$bgcolor = 'class="active"';
$cor= true;
$provmissoes=0;
$ultimolanc = 0;

//inicializa vari�veis
$totalDeb = 0;
$totalCred = 0;
//print_r($arrayDesp);
foreach ($arrayDesp as $keyDesp => $vlrDesp) {

	$linkPagar  = '<a target="_blanck" href="./?escolha=tesouraria/agenda.php&menu=top_tesouraria&id='.$vlrDesp['id'].'"';
	$linkPagar .= '><small class="text-muted glyphicon glyphicon-new-window"></small</a>';
	$bgcolor = $cor ? 'class="active"' : '';
	if ($vlrDesp['vencimento']!='' && $vlrDesp['dtpgto']!='00/00/0000') {
		$vencPgto  = '<small class="text-success glyphicon glyphicon-ok"></small> Pago em: '.$vlrDesp['dtpgto'];
		$vencPgto .= ' -> Venc.: '.$vlrDesp['vencimento'].' '.$linkPagar;
		$titleMsg = ', paga, obrigado!';
	}elseif ($vlrDesp['dtpgto']=='00/00/0000') {
		$vencPgto  = '<small class="text-danger btn-xs glyphicon glyphicon-warning-sign"> </small>Venc.: '.$vlrDesp['vencimento'];
		$vencPgto .= ' '.$linkPagar;
		$bgcolor = 'class="danger"';
		$titleMsg = ', ainda n&atilde;o paga!';
	}
	else {
		$vencPgto = '';
	}
	if ($vencPgto=='') {
		$linhaTab  = '<tr '.$bgcolor.' title="'.$vlrDesp['titulo'].'"><td> Lan&ccedil;ado em: '.$vlrDesp['data'].'</td><td>';
		$linhaTab .= '<kbd>'.$vlrDesp['igreja'].'</kbd> -> '.$vlrDesp['referente'];
		$linhaTab .= '</td><td class="text-right">'.number_format($vlrDesp['valor'],2,',','.');
		$linhaTab .= ' '.$vlrDesp['sld'].'</td><tr>';
		$linha[$vlrDesp['acesso']] .= $linhaTab;
	} else {
		$linhaTab  = '<tr '.$bgcolor.' title="'.$vlrDesp['titulo'].$titleMsg.'"><td>'.$vencPgto.'</td><td>';
		$linhaTab .= '<kbd>'.$vlrDesp['igreja'].'</kbd> -> '.$vlrDesp['referente'];
		$linhaTab .= '</td><td class="text-right">'.number_format($vlrDesp['valor'],2,',','.');
		$linhaTab .= ' '.$vlrDesp['sld'].'</td><tr>';
		$linha[$vlrDesp['acesso']] .= $linhaTab;
	}
	$cor = !$cor;
}

$acesso = (empty($_GET['acesso'])) ? '' : $_GET['igreja'] ;
$listaFonte = $bsccredor->List_Selec($acesso);
$dia1 ='';$listDesp = '';
$igreja = (empty($_GET['igreja'])) ? '' : $_GET['igreja'] ;
$cor=true;
$lancar = '<br /><br /><button class="btn btn-primary">Lan&ccedil;ar!</button>';

//print_r($ctaDespesa->dadosArray());
foreach ($ctaDespesa->dadosArray() as $chave => $valor) {
	//Vari�veis para montagem do form

	$dataLan = '<label>Data do lan&ccedil;amento</label>'.
			'<input name="data'.$chave.'" class="form-control dataclass" value="'.date('d/m/Y').'"';
	$campoHist = '<label>Hit&oacute;rico</label><textarea name="hist'.$chave.'" class="form-control"></textarea>';
	$bsccredor = new List_sele('igreja', 'razao','rolIgreja'.$chave);
	$listaIgreja = $bsccredor->List_Selec('',$igreja,'class="form-control" autofocus="autofocus" ');
	$campoValor = '<label>Valor</label><input name="valor'.$chave.'" class="form-control"/>';
	$conta ='<input name="acesso'.$chave.'" type="hidden" value="'.$valor['acesso'].'">';

//Fecha a tabela se mudou de grupo de conta
if ($codigo5!=$valor['codigo'] && strlen($valor['codigo'])=='9') {
	$listDesp .= $cabDespesa.$dia1.'</tbody></table></div></form>';
	$dia1='';$cabDespesa='';
}

	if (strlen($valor['codigo'])=='13') {
		$bgcolor = $cor ? 'class="dados"' : 'class="odd"';
		//lista dos caixas dispon�veis para pgto
		$fontesPgto  = '<label>Caixas c/ Saldo:</label>';
		$fontesPgto .= '<select name="disponivel'.$chave.'" class="form-control" >';
		$fontesPgto .= $listaFonte;
		$fontesPgto .= '</select>';
		//Lista das despesas dispon�veis
		$dia1 .='<tbody><tr class="sub label-info">
		<th colspan="4"><strong>'.$valor['codigo'].'</strong> - '.$valor['titulo'].'</th>
		</tr>';
		$dia1 .='<tr '.$bgcolor.'><td rowspan="2">'.$valor['titulo'].$conta
		.'</abbr><p>'.$fontesPgto.'</p>'.$campoHist.'</td></tr><tr '.$bgcolor.'><td>'.$dataLan.
		'<br /><br /><label><strong>Igreja</strong></label>'.$listaIgreja.
		'</td><td>'.$campoValor.$lancar.'</td></tr>';
		$dia1 .= $linha[$valor['acesso']];
		$cor = !$cor;
	} elseif (strlen($valor['codigo'])=='9') {
		$cabDespesa  = '<form  method="post"><div class="panel panel-info" ><div class="panel-body"><strong>';
		$cabDespesa .= $valor['codigo'].'</strong> - '.$valor['titulo'].'</div><table id="horario" ';
		$cabDespesa .= 'class="table table-hover">';
	}/*elseif (strlen($valor['codigo'])=='5') {
		$codigo5 = $valor['codigo'];
	}
echo($valor['codigo']).' **-> '.strlen($valor['codigo']).' === ';*/

}

//�ltimo grupo do array, completando a tabela
if ($cabDespesa!='') {
	$listDesp .= $cabDespesa.$dia1.'</form></tbody></table>';
}

//esta vari�vel � levada p/ o script views/exibilanc.php que chamado ao final deste loop numa linha abaixo
if ($exibideb!='') {
	require_once 'views/exibilanc.php';//monta a tabela para exibir
}

$nivel1 = $listDesp;
