<?php
//Op��es de  impress�es para o script /tesouraria/receita.php
switch ($_GET['rec']) {
	case '13':
		//imprimir entradas de todas as congrega��es - mensal
		require_once '../help/tes/saldoIgrejas.php';
		$nomeArquivo='../views/tesouraria/saldoIgrejas.php';
		require_once '../views/modeloPrint.php';
		break;
	case '14':
		//imprimir
		if (!empty($_GET['dtBalac'])) {
			$titTabela = 'Balancete - Saldo em: '.$_GET['dtBalac'];
		}

		require_once '../models/saldos.php';
		$nomeArquivo='../views/saldos.php';
		require_once '../views/modeloPrint.php';
		break;
	case '15':
		require_once '../help/tes/varRelatorio.php';
		$igrejaSelecionada = new DBRecord('igreja', $roligreja, 'rol');
		$titTabela = 'Relat�rio de Lan�amentos';
		$linkImpressao ='tesouraria/receita.php/?rec=15';
		//require_once '../models/saldos.php';
		$nomeArquivo='../views/tesouraria/tabRelatLanc.php';
		require_once '../views/modeloPrint.php';
		break;
	case '16':
		//Relatorio COMADEP
		$idIgreja = intval($_GET['igreja']);
		require_once '../help/tes/relatorioComadep.php';//Cabe�alho e informa��es da consulta
		//$mesRelatorio .=$rolIgreja;
		$dtRelatorio = data_extenso ($d.'/'.$m.'/'.$a);
		require_once '../models/tes/relatorioComadep.php';
		$nomeArquivo='../views/saldosComadep.php';
		$titTabela = $congRelatorio.' &bull; Fluxo das Contas - '.$dtRelatorio;
		require_once '../views/modeloPrint.php';
		break;
	default:
		//imprimir plano de contas
		$titTabela = 'Plano de Contas em: '.date('d/m/Y');
		require_once '../models/saldos.php';
		$nomeArquivo='../views/saldos.php';
		require_once '../views/modeloPrint.php';
		break;
}
?>
