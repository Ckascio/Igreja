<?php
$linkLancamento  = './?escolha=tesouraria/receita.php&menu=top_tesouraria';
$linkLancamento .= '&igreja='.$_POST['igreja'];
require_once 'views/tesouraria/menu.php';//Sub-Menu de links

#Analisa se ser� lan�ado em contas a pagar e fazer o reconhecimento da despesas
list($anoVenc,$mesVen,$diaVenc) = explode('-',$vencimento);
$data = br_data($_POST['data'], 'Data do lan�amento inv�lida!');
list($anoPgto,$mesPgto,$diaPgto) = explode('-', $data);
if ((($mesPgto>$mesVen && $anoPgto==$anoVenc) ||$anoPgto>$anoVenc) && $vencimento!='') {
	$ctaPagar = true;
} else {
	$ctaPagar = false;
}

controle ('tes');
$provmissoes=0;
$ultimolanc = 0;
$roligreja =(int) $_POST['rolIgreja'];

$novoLanc  = '<a href="./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec=2&igreja='.$roligreja.'"';
$novoLanc .= '><button class="btn btn-primary active" autofocus="autofocus" > <span class="glyphicon glyphicon-save-file" >';
$novoLanc .= '</span>&nbsp;Novo Lan&ccedil;amento</button></a>';

$igLanc = new DBRecord('igreja', $roligreja, 'rol');
#$motivoComplemento = (empty($_POST['nome'])) ? $_POST['credor']:$_POST['nome'];
if (!empty($_POST['nome'])) {
	$motivoComplemento = ' ('.$_POST['nome'].')';
} elseif (!empty($_GET['nome'])) {
	$motivoComplemento = ' ('.$_GET['nome'].')';
} else {
	$motivoComplemento = '';
}

if ($_POST['valor']<='0' || $_POST['acessoDebitar']<1 || $_POST['acessoCreditar']<1) {
	$dizimista = false;
}else {
	$status = true;
	$multa = (empty($multaUS)) ? strtr( str_replace(array('.'),array(''),$_POST['multa']), ',.','.,' ):$multaUS;
	$valor = (empty($valor_us)) ? strtr( str_replace(array('.'),array(''),$_POST['valor']), ',.','.,' ):($valor_us);
	$debitar = $_POST['acessoDebitar'];
	$creditar =  $_POST['acessoCreditar'];
}

//inicializa vari�veis
$totalDeb = 0;
$totalCred = 0;
//$corlinha = false;

	$credora 	= new DBRecord('contas',$creditar,'acesso');
	$sldAntCred = number_format($credora->saldo(),2,',','.');
	$devedora 	= new DBRecord('contas',$debitar,'acesso');
	$sldAntDev = number_format($devedora->saldo(),2,',','.');

	if ($multa>'0') {
		$ctaMulta 	= new DBRecord('contas','571','acesso');//Conta Multas diversas
		$histmulta = ($motivoComplemento=='') ? 'Multa':'Multa '.$motivoComplemento;
	} else {
		$ctaMulta = false;
	}

	if ($credora->tipo()=='D' && ($credora->saldo()-($valor+$multa))<'0') {
	 $msgErro = 'Saldo n�o permitido para Conta: '.$credora->titulo().' que ficaria com o valor de '.($credora->saldo()-$valor);
	}elseif ($devedora->tipo()=='C' && ($devedora->saldo()-$valor)<'0'){
	 $msgErro = 'Saldo n�o permitido para Conta: '.$devedora->titulo().' que ficaria com o valor de '.($devedora->saldo()-$valor);
	}elseif ($debitar==$creditar){
	 $msgErro = 'Contas de Credito e D�bito iguais, refa�a o lan�amento!';
	}else {
	 $msgErro='';
	}

	if ($ctaMulta) {
		if ($ctaMulta->tipo()=='C' && ($ctaMulta->saldo()-$multa<'0')){
	 		$msgErro .= 'Saldo n�o permitido para Conta: '.$ctaMulta->titulo().' que ficaria com o valor de '.($ctaMulta->saldo()-$multa);
		}
	}

	if ($credora->nivel4()=='1.1.1.001') {
	 ;//testar se cta de caixa e n�o permitir o lancamento se ficar negativo e a de despesas tb
	}

	$ultimoLancNumero = mysql_query('SELECT max(lancamento) AS lanca FROM lanc');//Traz o valor do ultimo lan�amento
	$lancmaior = mysql_fetch_array($ultimoLancNumero);
	$ultimolanc = (int)$lancmaior['lanca']+1;//Acrescenta uma unidade no ultimo lan�amento p usar no lan�amento

//Foi criado a tabela lanchist exclusivamente para o hist�rico dos lan�amentos
//Antes de come�ar os lan�amentos verificar se h� inconcist�ncia nos saldo antes de continuar
//Criar uma classe que retorne falso ou verdadeiro
//Analizar os valores para lan�ar o d�zimo para COMADEP e SEMAD

$referente = (strlen($_POST['referente'])>'4') ? $_POST['referente']:false;//Atribui a vari�vel o hist�rico do lan�amento

if ($status && $referente && checadata($_POST['data']) && $msgErro=='') {

	//Faz o lan�amento do d�bito da tabela lancamento
	$exibideb = '<tr class="warning"><td colspan="5">Debito</td></tr>';
	$exibicred = '<tr class="warning"><td colspan="5">Credito</td></tr>';

	$caixaCentral ='';$caixaEnsino = '';$caixaInfantil ='';
	$caixaMissoes = '';$caixaMocidade = '';$caixaOutros = '';
	$caixaSenhoras = '';
	//echo $credora->id().'<h1> tste </h>';
	/*
	* Se o pgto tiver vencimento de m�s anterior ao pgto � feita
	* a apropria��o em contas a pagar no m�s de refer�ncia e o lan�amento tb do
	* pgto da cta caixa e cta a pagar
	*/
	if ($ctaPagar) {
		$ctaPagar = new DBRecord ('contas','2.1.1.001.099','codigo');
		$sldAntPagar = number_format($ctaPagar->saldo(),2,',','.');
		$contApgtoAprop 	= new atualconta($devedora->codigo(),$ultimolanc+1,$ctaPagar->id());#devedora a Contas a pagar
		$contApgtoAprop->atualizar($valor,'D',$roligreja,$vencimento);

		$contcaixa = new atualconta($ctaPagar->codigo(),$ultimolanc+1,'');
		$contcaixa->atualizar($valor,'C',$roligreja,$data);

		//$contApgtoAprop 	= new atualconta($ctaPagar->codigo(),$ultimolanc+1,$devedora->id());
		//$contApgtoAprop->atualizar($valor,'C',$roligreja,$vencimento);

		//Lan�a o hist�rico do lan�amento
		$histAPagar .= 'Reconhecido despesa nesta data e pago em '.$_POST['data'];
		$InsertHist = sprintf("'','%s','%s','%s'",$ultimolanc+1,$histAPagar,$roligreja);
		$lanchist = new incluir($InsertHist, 'lanchist');
		$lanchist->inserir();

		$exibideb .= sprintf("<tr><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
				$devedora->codigo(),$devedora->titulo(),number_format($valor,2,',','.'),
				number_format($devedora->saldo(),2,',','.'),$devedora->tipo(),$sldAntPagar);
		$totalDeb +=$valor;

		$devedora = $ctaPagar;
		$debitar = $devedora->acesso();

		//$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$exibicred .= sprintf("<tr><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
		$ctaPagar->codigo(),$ctaPagar->titulo(),number_format($valor,2,',','.'),number_format($ctaPagar->saldo(),2,',','.'),$ctaPagar->tipo()
		,$sldAntPagar);
		$totalCred +=$valor;
		//$corlinha = !$corlinha;
		//$cor = $corlinha ? 'class="odd"' : 'class="dados"';

	}

		$contcaixa 	= new atualconta($devedora->codigo(),$ultimolanc,$credora->id());
		$histLac = $referente.$motivoComplemento;
		$contcaixa->atualizar($valor,'D',$roligreja,$data); //Faz o lan�amento na tabela lancamento e atualiza o saldo
		$ctaVencida = '';


		$valorTotal += $valor;
//print_r($credora);
		if ($credora->nivel2()=='4.1') {
			//Receitas operacionais faz provis�o automaticamente
			//exceto lan�amento direto para despesas n�o operacionais
			if ($debitar=='2') {
				//Provis�o para Miss�es
				$provmissoes += $valor*0.4;

			}elseif ($devedora->nivel4()=='1.1.1.001' && $devedora->acesso()>0 && $devedora->tipo()=='D') {
				//Para tipo 8 n�o h� provis�o para COMADEP ou Miss�es
				$provcomadep += $valor*0.1;
				$ctaComadep = new DBRecord('contas','3.1.1.001.007','codigo');
				$sldAntComadep = number_format($ctaComadep->saldo(),2,',','.');
			}

		}

	//Exibi lan�amento
	//Faz lan�ameto de multa caso exista
	if ($ctaMulta) {
		$ctaMulta = new DBRecord('contas',$ctaMulta->codigo(),'codigo');
		$multaAtraso = new atualconta($ctaMulta->codigo(),$ultimolanc,$credora->id());
		$multaAtraso->atualizar($multa,'D',$roligreja,$data);
		$totalMulta += $multa;
		$lancMulta=true;
		$exibideb .= sprintf("<tr class='odd' ><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
		$ctaMulta->codigo(),$ctaMulta->titulo(),number_format($multa,2,',','.'),number_format($ctaMulta->saldo(),2,',','.'),$ctaMulta->tipo()
		,$ctaMulta->saldo());
	}
		$caixa = new DBRecord('contas',$debitar,'acesso');
		$totalDeb = $totalDeb + $valor + $multa;
		require 'help/tes/exibirLancamento.php';//monta a tabela para exibir

	$exibideb .= $exibiCentral.$exibiMissoes.$exibiSenhoras.$exibiMocidade.$exibiInfantil.$exibiEnsino.$exibi;

   	//Lan�a provis�es conta Despesa
   	if ($provmissoes>0) {
		$semaddesp = new atualconta('3.1.6.001.005',$ultimolanc,'11');//SEMAD (Sec de Miss�es) provis�o e despesa
		$semaddesp->atualizar($provmissoes,'D',$roligreja,$data); //Faz o lan�amento da provis�o de miss�es - Despesa
		$histTextProv =' e provis�o para SEMAD sobre a receita';

		$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$conta = new DBRecord('contas','3.1.6.001.005','codigo');//Exibi lan�amento da provis�o SEMAD
		$antProvSemad = number_format($conta->saldo()-$provmissoes,2,',','.');
		$exibideb .= sprintf("<tr><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
				$conta->codigo(),$conta->titulo(),number_format($provmissoes,2,',','.'),
				number_format($conta->saldo(),2,',','.'),$conta->tipo(),$antProvSemad);
		$totalDeb += $provmissoes;
		$corlinha = !$corlinha;
   	}

	$provcomad = new atualconta('3.1.1.001.007',$ultimolanc,'10');//Conven��o estadual COMADEP
	if ($provcomadep>0) {
		$provcomad->atualizar($provcomadep,'D',$roligreja,$data); //Faz o lan�amento da provis�o de Comadep - Despesa
		$totalDeb += $provcomadep;
		if ($histTextProv!='') {
			$histTextProv = ', provis�o para COMADEP e SEMAD sobre a receita';
		} else {
			$histTextProv = ' e provis�o para COMADEP sobre a receita';
		}

		$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$conta = new DBRecord('contas','3.1.1.001.007','codigo');//Exibi lan�amento da provis�o SEMAD
		$exibideb .= sprintf("<tr><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;
						</td><td id='moeda'>%s&nbsp;%s</td></td><td class='text-right'>%s</td></tr>",$conta->codigo(),$conta->titulo()
						,number_format($provcomadep,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo()
						,$sldAntComadep);
		$corlinha = !$corlinha;
	}
	$exibideb .= sprintf("<tr  class='primary'><td>Total debitado</td><td id='moeda'>R$ %s</td><td colspan='3'></td></tr>"
		,number_format($totalDeb,2,',','.'));
	//esta vari�vel � levada p/ o script views/exibilanc.php

	//Faz o leiaute do lan�amento do cr�dito da tabela lancamento
		$contcaixa = new atualconta($credora->codigo(),$ultimolanc,'');
		$contcaixa->atualizar($multa+$valor,'C',$roligreja,$data); //Faz o lan�amento na tabela lancamento e atualiza o saldo

		//$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$caixa = new DBRecord('contas',$creditar,'acesso');//Exibi lan�amento
		$exibicred .= sprintf("<tr><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
		$caixa->codigo(),$caixa->titulo(),number_format($valor+$multa,2,',','.'),number_format($caixa->saldo(),2,',','.'),$caixa->tipo()
		,$sldAntCred);
		$totalCred += $valor+$multa;
		//$corlinha = !$corlinha;

	//Lan�a provis�es conta credora no Ativo
	$lancprovmissoes=false;
	if ($provmissoes>0) {
		//Faz o lan�amento da provis�o de miss�es - Ativo
		$ctaSemad = new DBRecord('contas','7','acesso');//Conta provis�o SEMAD
		$sldAntSemad = number_format($ctaSemad->saldo(),2,',','.');

		$provsemad = new atualconta('1.1.1.001.007',$ultimolanc);
		$provsemad->atualizar($provmissoes,'C',$roligreja,$data);
		$totalCred += $provmissoes;
		$lancprovmissoes=true;

		//$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$conta = new DBRecord('contas','7','acesso');//Exibi lan�amento da provis�o SEMAD
		$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
		$conta->codigo(),$conta->titulo(),number_format($provmissoes,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo(),
		$sldAntSemad);
		//$corlinha 	= !$corlinha;
	}


	if ($provcomadep>0) {
		$ctaProvcomad = new DBRecord('contas','6','acesso');//Exibi lan�amento da provis�o COMADEP
		$sldAntProv = number_format($ctaProvcomad->saldo(),2,',','.');
		$provcomad 	= new atualconta('1.1.1.001.006',$ultimolanc); //Faz o lan�amento da provis�o de Comadep - Ativo
		$provcomad->atualizar($provcomadep,'C',$roligreja,$data);//Faz o lan�amento da provis�o da COMADEP - Ativo
		$lancprovmissoes=true;

		//$cor 		= $corlinha ? 'class="odd"' : 'class="dados"';
		$conta 		= new DBRecord('contas','6','acesso');//Exibi lan�amento da provis�o COMADEP
		$exibicred .= sprintf("<tr><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td><td class='text-right'>%s</td></tr>",
		$conta->codigo(),$conta->titulo(),number_format($provcomadep,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo()
		,$sldAntProv);
		$totalCred 	+= $provcomadep;
	}

	//esta vari�vel � levada p/ o script views/exibilanc.php que chamado ao final deste loop numa linha abaixo
	$exibicred .= sprintf("<tr class='primary'><td colspan='2'>Total Creditado</td><td id='moeda'>R$ %s</td><td colspan='2'></td></tr>",number_format($totalCred,2,',','.'));

	//echo "Miss�es: $provmissoes, Comadep: $provcomadep";
	//inserir o hist�rico do lan�amento das provis�es na tabela lanchist

	//Lan�a o hist�rico do lan�amento das provis�es $provmissoes>0 $provcomadep>0
	if ($lancprovmissoes) {
	$HistProv = sprintf("'','%s','%s','%s'",$ultimolanc,$histTextProv,$roligreja);
	//$lanchist = new incluir($HistProv, 'lanchist');
	//$lanchist->inserir();
	}

	//Lan�a o hist�rico do lan�amento
	$referente .= $histTextProv.$ctaVencida;
	$InsertHist = sprintf("'','%s','%s','%s'",$ultimolanc,$referente,$roligreja);
	$lanchist = new incluir($InsertHist, 'lanchist');
	$lanchist->inserir();

	require_once 'views/exibilanc.php'; //Exibi a tabela com o lan�amento conclu�do

}else {
	 //Fim do 1� if linha 7
	if ($referente=='' && !$status) {
		$mensagem = 'N�o existe nada a ser lan�ado!';
	}elseif ($referente=='') {
		$mensagem = 'Voc� n�o informou o motivo do lan�amento com um m�nimo de 5 caracteres!' ;
	}elseif ($msgErro!='') {
		$mensagem = $msgErro;
	}else {
		$mensagem = 'N�o exite valores a ser lan�ado!';
	}

	echo '<script>alert("'.$mensagem.'");window.history.go(-1);</script>';
	echo $mensagem;

}

echo $novoLanc;