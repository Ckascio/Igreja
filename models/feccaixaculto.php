<?php

controle ('tes');
$ultimolanc = 0;
$roligreja =(int) $_POST['igreja'];
$dizimista = new dizresp($roligreja);

//inicializa vari�veis
$totalDeb = 0;
$totalCred = 0;
$corlinha = false;

	$ultimolanc = mysql_query('SELECT max(lancamento) AS lanc FROM lancamento');//Traz o valor do ultimo lan�amento
	$lancmaior = mysql_fetch_array($ultimolanc);
	$ultimolanc = (int)$lancmaior['lanc']+1;//Acrescenta uma unidade no ultimo lan�amento p usar no lan�amento
	$idlancmis = $ultimolanc + 1;//id do lan�amento das provis�es

//Foi criado a tabela lanchist exclusivamente para o hist�rico dos lan�amentos
//Antes de come�ar os lan�amentos verificar se h� inconcist�ncia nos saldo antes de continuar
//Criar uma classe que retorne falso ou verdadeiro
//Analizar os valores para lan�ar o d�zimo para COMADEP e SEMAD

$referente = ($_POST['hist']<>'') ? $_POST['hist']:$_POST['histsug'];//Atribui a vari�vel o hist�rico do lan�amento
$referente = mysql_escape_string($referente);
$data = br_data($_POST['data'], 'Data do lan�amento inv�lida!');

if ($dizmista->totalgeral()>'0' && $referente!='' && checadata($_POST['data'])) {

	//Faz o lan�amento do d�bito para tabela lancamento
	$tablanc = mysql_query('SELECT devedora,tipo,SUM(valor) AS valor,credito FROM dizimooferta
			WHERE lancamento="0" AND igreja = "'.$roligreja.'" GROUP BY credito,tipo');
	$exibideb = '<tr><td colspan="4">Debito</td></tr>';
	$exibicred = '<tr><td colspan="4">Credito</td></tr>';

	$caixaCentral ='';$caixaEnsino = '';$caixaInfantil ='';
	$caixaMissoes = '';$caixaMocidade = '';$caixaOutros = '';
	$caixaSenhoras = '';

	while ($tablancarr = mysql_fetch_array($tablanc)) {
		$debitar = $tablancarr['devedora'];
		$devedora 	= new DBRecord('contas',$debitar,'acesso');
		$credora 	= new DBRecord('contas',$tablancarr['credito'],'acesso');
		$contcaixa 	= new atualconta($devedora->codigo(),$ultimolanc,$credora->id());
		$valor 		= $tablancarr['valor'];
		$contcaixa->atualizar($valor,'D',$roligreja,$data); //Faz o lan�amento na tabela lancamento e atualiza o saldo
		$valorTotal += $valor;

		//Para nivel2='4.2'(Receitas n�o Operacionais) n�o h� provis�o para COMADEP ou Miss�es
		if ($tablancarr['devedora']=='2' && $credora->nivel2()!='4.2') {
			//provis�o para fundo de Miss�es de 40%
			$provmissoes += round(($valor*0.4),2);
		}elseif ($credora->nivel2()!='4.2') {
			//provis�o para Conven��o de 10%
			$provcomadep += round(($valor*0.1),2);
		}
		//Exibi lan�amento
		$caixa = new DBRecord('contas',$tablancarr['devedora'],'acesso');
		$totalDeb = $totalDeb + $valor;
		require 'help/tes/exibirLancamento.php';//monta a tabela para exibir
	}

	$exibideb .= $exibiCentral.$exibiMissoes.$exibiSenhoras.$exibiMocidade.$exibiInfantil.$exibiEnsino.$exibi;
   	//Lan�a provis�es conta Despesa
	$semaddesp = new atualconta('3.1.6.001.005',$idlancmis,11);
   	if ($provmissoes>0) {
   		$semaddesp->atualizar($provmissoes,'D',$roligreja,$data); //Faz o lan�amento, se possuir valor, da provis�o de miss�es - Despesa
   	}
	$cor = $corlinha ? 'class="odd"' : 'class="dados"';
	$conta = new DBRecord('contas','3.1.6.001.005','codigo');//Exibi lan�amento da provis�o SEMAD
	$exibideb .= sprintf("<tr $cor ><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;</td><td id='moeda'>%s&nbsp;%s</td></tr>",
			$conta->codigo(),$conta->titulo(),number_format($provmissoes,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$totalDeb = $totalDeb + $provmissoes;

	$corlinha = !$corlinha;
	$provcomad = new atualconta('3.1.1.001.007',$idlancmis,10);
	if ($provcomadep>0) {
		$provcomad->atualizar($provcomadep,'D',$roligreja,$data); //Faz o lan�amento, se possuir valor, da provis�o de Comadep - Despesa
	}
	$cor = $corlinha ? 'class="odd"' : 'class="dados"';
	$conta = new DBRecord('contas','3.1.1.001.007','codigo');//Exibi lan�amento da provis�o SEMAD
	$exibideb .= sprintf("<tr $cor ><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;
					</td><td id='moeda'>%s&nbsp;%s</td></tr>",$conta->codigo(),$conta->titulo()
					,number_format($provcomadep,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$totalDeb = $totalDeb + $provcomadep;
	$corlinha = !$corlinha;
	$exibideb .= sprintf("<tr class='total'><td>Total debitado</td><td id='moeda'>R$ %s</td><td></td><td></td></tr>",number_format($totalDeb,2,',','.'));
	//esta vari�vel � levada p/ o script views/exibilanc.php

	//Faz o leiaute do lan�amento do cr�dito e lan�a para tabela lancamento
	$tablanc_c = mysql_query('SELECT SUM(valor) AS valor,credito FROM dizimooferta WHERE lancamento="0" AND igreja = "'.$roligreja.'" GROUP BY credito');

	while ($tablancarrc = mysql_fetch_array($tablanc_c)) {

		$credora = new DBRecord('contas',$tablancarrc['credito'],'acesso');
		$contcaixa = new atualconta($credora->codigo(),$ultimolanc);
		$contcaixa->atualizar($tablancarrc['valor'],'C',$roligreja,$data); //Faz o lan�amento na tabela lancamento e atualiza o saldo

		$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$caixa = new DBRecord('contas',$tablancarrc['credito'],'acesso');//Exibi lan�amento
		$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td></tr>",
		$caixa->codigo(),$caixa->titulo(),number_format($tablancarrc['valor'],2,',','.'),number_format($caixa->saldo(),2,',','.'),$caixa->tipo());
		$totalCred = $totalCred + $tablancarrc['valor'];
		$corlinha = !$corlinha;

	}
	//Lan�a provis�es conta credora no Ativo
	$histProvisao = '';
	$provsemad = new atualconta('1.1.1.001.007',$idlancmis);
	if ($provmissoes>0) {
		$provsemad->atualizar($provmissoes,'C',$roligreja,$data); //Faz o lan�amento, se possuir valor, da provis�o de miss�es - Ativo
		$histProvisao = 'Valor provisionado para SEMAD sobre a receita nesta data';
	}
	$cor = $corlinha ? 'class="odd"' : 'class="dados"';
	$conta = new DBRecord('contas','7','acesso');//Exibi lan�amento da provis�o SEMAD
	$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td></tr>",
	$conta->codigo(),$conta->titulo(),number_format($provmissoes,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$totalCred = $totalCred + $provmissoes;

	$corlinha 	= !$corlinha;
	$provcomad 	= new atualconta('1.1.1.001.006',$idlancmis); //Faz o lan�amento da provis�o de Comadep - Ativo
	if ($provcomadep) {
		$provcomad->atualizar($provcomadep,'C',$roligreja,$data);//Faz o lan�amento, se possuir valor, da provis�o da COMADEP - Ativo
		if ($histProvisao=='') {
			$histProvisao = 'Valor provisionado para COMADEP sobre a receita nesta data';
		}else {
			$histProvisao = 'Valor provisionado para SEMAD e COMADEP sobre a receita nesta data';
		}
	}

	$cor 		= $corlinha ? 'class="odd"' : 'class="dados"';
	$conta 		= new DBRecord('contas','6','acesso');//Exibi lan�amento da provis�o COMADEP
	$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td></tr>",
	$conta->codigo(),$conta->titulo(),number_format($provcomadep,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$totalCred 	= $totalCred + $provcomadep;

	$exibicred .= sprintf("<tr class='total'><td colspan='2'>Total Creditado</td><td id='moeda'>R$ %s</td><td></td></tr>",number_format($totalCred,2,',','.'));
	//esta vari�vel � levada p/ o script views/exibilanc.php que chamado ao final deste loop numa linha abaixo

	//Atualiza a tabela dizimooferta de acordo com a igreja selecionada inserido o id do lan�amento no campo lan�amento
	$atualdizoferta = mysql_query("SELECT id FROM dizimooferta WHERE lancamento='0' AND igreja='$roligreja' ") or die (mysql_error());
	while ($lanc = mysql_fetch_array($atualdizoferta)) {
			$ofetdiz = new DBRecord('dizimooferta',$lanc['id'],'id');
			$ofetdiz->lancamento = $ultimolanc;
			$ofetdiz->UpdateID();
		}

	//Lan�a o hist�rico do lan�amento
	$InsertHist = sprintf("'','%s','%s','%s'",$ultimolanc,$referente,$roligreja);
	$lanchist = new incluir($InsertHist, 'lanchist');
	$lanchist->inserir();

	//echo "Miss�es: $provmissoes, Comadep: $provcomadep";
	//inserir o hist�rico do lan�amento das provis�es na tabela lanchist

	//Lan�a o hist�rico do lan�amento das provis�es
	$HistProv = sprintf("'','%s','%s','%s'",$idlancmis,$histProvisao,$roligreja);
	$lanchist = new incluir($HistProv, 'lanchist');
	$lanchist->inserir();
	$dtLanc = new DateTime (br_data($_POST['data']));
	$exibiRodape .= '<tr class="success"><td colspan="3">Data: '.$dtLanc->format('d/m/Y').'</td>';
	$linkImpDia   = './controller/modeloPrint.php/?tipo=1&rec=0&igreja='.$roligreja;
	$linkImpDia  .= '&ano='.$dtLanc->format('Y').'&mes='.$dtLanc->format('m').'&dia='.$dtLanc->format('d');
	$exibiRodape .= '<td><a target=_blank href="'.$linkImpDia.'" >';
	$exibiRodape .= '<button type="button" class="btn btn-primary btn-xs" tabindex="'.++$ind.'">';
	$exibiRodape .= '<span class="glyphicon glyphicon-print"></span> Imprimir este dia...</button></a></td></tr>';
	//Rodap� lo lan�amento
	require_once 'views/exibilanc.php'; //Exibi a tabela com o lan�amento conclu�do

}else {
	 //Fim do 1� if linha 7
	if ($referente=='' && $dizmista->totalgeral()=='') {
		$mensagem = 'N�o existe nada a ser lan�ado!';
	}elseif ($referente=='') {
		$mensagem = 'Voc� n�o informou o motivo do lan�amento!' ;
	}else {
		$mensagem = 'N�o exite valores a ser lan�ado!';
	}

	echo '<script>alert("'.$mensagem.'");location.href="./?escolha=tesouraria/receita.php";</script>';
	echo $mensagem;

}

?>
	<div class="row form-group">
		<div class="col-xs-4">
			<label>&nbsp;</label>
			<a href="<?php echo $linkLancamento;?>&rec=1">
 				<button type="button" class="btn btn-primary" tabindex="<?PHP echo ++$ind; ?>" >
 					Pr�ximo culto: <?php echo $igrejaSelecionada->razao();?></button>
 			</a>
		</div>
		<div class="col-xs-3">
			<label>Pr�xima Igreja: </label>
				<select name="igreja" id="igreja" class="form-control" onchange="MM_jumpMenu('parent',this,0)" tabindex="<?PHP echo ++$ind; ?>" >
					<?php
						$linkAcesso  = 'escolha=tesouraria/receita.php&menu=top_tesouraria';
						$linkAcesso .= '&rec=1&igreja=';
						$bsccredor = new List_sele('igreja', 'razao', 'rolIgreja');
						$listaIgreja = $bsccredor->List_Selec_pop($linkAcesso,'');
						//echo $listaIgreja;
					?>
			</select>
		</div>
