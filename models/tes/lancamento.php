<?php

controle ('tes');
$provmissoes=0;
$ultimolanc = 0;
$roligreja =(int) $_POST['rolIgreja'];

if ($_POST['valor']<='0' || $_POST['acessoDebitar']<1 || $_POST['acessoCreditar']<1) {
	$dizimista = false;
}else {
	$status = true;
	$valor = (empty($valor_us)) ? strtr( str_replace(array('.'),array(''),$_POST['valor']), ',.','.,' ):$valor_us;
	$debitar = $_POST['acessoDebitar'];
	$creditar =  $_POST['acessoCreditar'];
}

//inicializa vari�veis
$totalDeb = 0;
$totalCred = 0;
$corlinha = false;

	$credora 	= new DBRecord('contas',$creditar,'acesso');
	$devedora 	= new DBRecord('contas',$debitar,'acesso');

	if ($credora->tipo()=='D' && ($credora->saldo()-$valor)<'0') {
	 $msgErro = 'Saldo n�o permitido para Conta: '.$credora->titulo().' que ficaria com o valor de '.($credora->saldo()-$valor);
	}elseif ($devedora->tipo()=='C' && ($devedora->saldo()-$valor)<'0'){
	 $msgErro = 'Saldo n�o permitido para Conta: '.$debitar->titulo().' que ficaria com o valor de '.($debitar->saldo()-$valor);
	}else {
	 $msgErro='';
	}


	if ($credora->nivel4()=='1.1.1.001') {
	 ;//testar se cta de caixa e n�o permitir o lancamento se ficar negativo e a de despesas tb
	}

	$ultimoLancNumero = mysql_query('SELECT max(lancamento) AS lanc FROM lancamento');//Traz o valor do ultimo lan�amento
	$lancmaior = mysql_fetch_array($ultimoLancNumero);
	$ultimolanc = (int)$lancmaior['lanc']+1;//Acrescenta uma unidade no ultimo lan�amento p usar no lan�amento

//Foi criado a tabela lanchist exclusivamente para o hist�rico dos lan�amentos
//Antes de come�ar os lan�amentos verificar se h� inconcist�ncia nos saldo antes de continuar
//Criar uma classe que retorne falso ou verdadeiro
//Analizar os valores para lan�ar o d�zimo para COMADEP e SEMAD

$referente = (strlen($_POST['referente'])>'4') ? $_POST['referente']:false;//Atribui a vari�vel o hist�rico do lan�amento

$data = br_data($_POST['data'], 'Data do lan�amento inv�lida!');

if ($status && $referente && checadata($_POST['data']) && $msgErro=='') {

	//Faz o lan�amento do d�bito da tabela lancamento
	$exibideb = '<tr><td colspan="4">Debito</td></tr>';
	$exibicred = '<tr><td colspan="4">Credito</td></tr>';

	$caixaCentral ='';$caixaEnsino = '';$caixaInfantil ='';
	$caixaMissoes = '';$caixaMocidade = '';$caixaOutros = '';
	$caixaSenhoras = '';
	echo $credora->id().'<h1> tste </h>';

		$contcaixa 	= new atualconta($devedora->codigo(),$ultimolanc,$credora->id());
		$contcaixa->atualizar($valor,'D',$roligreja,$referente); //Faz o lan�amento na tabela lancamento e atualiza o saldo
		$valorTotal += $valor;
//print_r($credora);
		if ($credora->nivel2()=='4.1') {
			//Receitas operacionais faz provis�o automaticamente
			if ($debitar=='2') {
				//Provis�o para Miss�es
				$provmissoes += $valor*0.4;
			}elseif ($devedora->nivel4()=='1.1.1.001' && $devedora->acesso()>0 && $devedora->tipo()=='D') {
				//Para tipo 8 n�o h� provis�o para COMADEP ou Miss�es
				$provcomadep += $valor*0.1;
			}

		}

		//Exibi lan�amento
		$caixa = new DBRecord('contas',$debitar,'acesso');
		$totalDeb = $totalDeb + $valor;
		require 'help/tes/exibirLancamento.php';//monta a tabela para exibir


	$exibideb .= $exibiCentral.$exibiMissoes.$exibiSenhoras.$exibiMocidade.$exibiInfantil.$exibiEnsino.$exibi;

   	//Lan�a provis�es conta Despesa
   	if ($provmissoes>0) {
		$semaddesp = new atualconta('3.1.6.001.005',$ultimolanc,'11');//SEMAD (Sec de Miss�es) provis�o e despesa
		$semaddesp->atualizar($provmissoes,'D',$roligreja,'Valor provisionado para SEMAD sobre a receita nesta data'); //Faz o lan�amento da provis�o de miss�es - Despesa
   	}
	$cor = $corlinha ? 'class="odd"' : 'class="dados"';
	$conta = new DBRecord('contas','3.1.6.001.005','codigo');//Exibi lan�amento da provis�o SEMAD
	$exibideb .= sprintf("<tr $cor ><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;</td><td id='moeda'>%s&nbsp;%s</td></tr>",
			$conta->codigo(),$conta->titulo(),number_format($provmissoes,2,',','.'),
			number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$totalDeb += $provmissoes;
	$corlinha = !$corlinha;

	$provcomad = new atualconta('3.1.1.001.007',$ultimolanc,'10');//Conven��o estadual COMADEP
	if ($provcomadep>0) {
		$provcomad->atualizar($provcomadep,'D',$roligreja,'Valor provisionado para COMADEP sobre a receita nesta data'); //Faz o lan�amento da provis�o de Comadep - Despesa
		$totalDeb += $provcomadep;
	}
	$cor = $corlinha ? 'class="odd"' : 'class="dados"';
	$conta = new DBRecord('contas','3.1.1.001.007','codigo');//Exibi lan�amento da provis�o SEMAD
	$exibideb .= sprintf("<tr $cor ><td>%s - %s</td><td id='moeda'>%s</td><td>&nbsp;
					</td><td id='moeda'>%s&nbsp;%s</td></tr>",$conta->codigo(),$conta->titulo()
					,number_format($provcomadep,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$corlinha = !$corlinha;
	$exibideb .= sprintf("<tr class='total'><td>Total debitado</td><td id='moeda'>R$ %s</td><td></td><td></td></tr>",number_format($totalDeb,2,',','.'));
	//esta vari�vel � levada p/ o script views/exibilanc.php

	//Faz o leiaute do lan�amento do cr�dito da tabela lancamento
		$contcaixa = new atualconta($credora->codigo(),$ultimolanc,'');
		$contcaixa->atualizar($valor,'C',$roligreja); //Faz o lan�amento na tabela lancamento e atualiza o saldo

		$cor = $corlinha ? 'class="odd"' : 'class="dados"';
		$caixa = new DBRecord('contas',$creditar,'acesso');//Exibi lan�amento
		$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td></tr>",
		$caixa->codigo(),$caixa->titulo(),number_format($valor,2,',','.'),number_format($caixa->saldo(),2,',','.'),$caixa->tipo());
		$totalCred += $valor;
		$corlinha = !$corlinha;

	//Lan�a provis�es conta credora no Ativo
	if ($provmissoes>0) {
		//Faz o lan�amento da provis�o de miss�es - Ativo
		$provsemad = new atualconta('1.1.1.001.007',$ultimolanc);
		$provsemad->atualizar($provmissoes,'C',$roligreja);
		$totalCred += $provmissoes;
	}

	$cor = $corlinha ? 'class="odd"' : 'class="dados"';
	$conta = new DBRecord('contas','7','acesso');//Exibi lan�amento da provis�o SEMAD
	$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td></tr>",
	$conta->codigo(),$conta->titulo(),number_format($provmissoes,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$corlinha 	= !$corlinha;

	if ($provcomadep>0) {
		$provcomad 	= new atualconta('1.1.1.001.006',$ultimolanc); //Faz o lan�amento da provis�o de Comadep - Ativo
		$provcomad->atualizar($provcomadep,'C',$roligreja);//Faz o lan�amento da provis�o da COMADEP - Ativo
	}
	$cor 		= $corlinha ? 'class="odd"' : 'class="dados"';
	$conta 		= new DBRecord('contas','6','acesso');//Exibi lan�amento da provis�o COMADEP
	$exibicred .= sprintf("<tr $cor ><td>%s - %s</td><td>&nbsp;</td><td id='moeda'>%s</td><td id='moeda'>%s&nbsp;%s</td></tr>",
	$conta->codigo(),$conta->titulo(),number_format($provcomadep,2,',','.'),number_format($conta->saldo(),2,',','.'),$conta->tipo());
	$totalCred 	= $totalCred + $provcomadep;

	//esta vari�vel � levada p/ o script views/exibilanc.php que chamado ao final deste loop numa linha abaixo
	$exibicred .= sprintf("<tr class='total'><td colspan='2'>Total Creditado</td><td id='moeda'>R$ %s</td><td></td></tr>",number_format($totalCred,2,',','.'));

	//Lan�a o hist�rico do lan�amento
	$InsertHist = sprintf("'','%s','%s','%s'",$ultimolanc,$referente,$roligreja);
	$lanchist = new incluir($InsertHist, 'lanchist');
	$lanchist->inserir();

	//echo "Miss�es: $provmissoes, Comadep: $provcomadep";
	//inserir o hist�rico do lan�amento das provis�es na tabela lanchist

	//Lan�a o hist�rico do lan�amento das provis�es
	$HistProv = sprintf("'','%s','%s','%s'",$ultimolanc,'Valor provisionado da SEMAD e COMADEP sobre a receita nesta data',$roligreja);
	$lanchist = new incluir($HistProv, 'lanchist');
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

	echo '<script>alert("'.$mensagem.'");location.href="./?escolha=tesouraria/receita.php&rec=2";</script>';
	echo $mensagem;

}
