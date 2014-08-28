<?php
$titTabela = 'Balancete - Saldo em: '.date('d/m/Y');

if (!empty($dataMov) && checadata($dataMov)) {
	$mesRelatorio = '"'.$a.$m.'"';
}elseif ($m>'0' && $m<'13') {
	$a = date('Y');
	$d=date("t",mktime(0,0,0,$m,1,$a));//recupera o ultimo dia do m�s
	$mesRelatorio = '"'.$a.$m.'"';
}else {
	list($d,$m,$a) = explode('/',date('d/m/Y'));
	$mesRelatorio = '"'.date('Ym').'"';
}

if ($_GET['rec']>'12' && $_GET['rec']<'20') {
	session_start();
	if ($_SESSION["setor"]=="2" || $_SESSION["setor"]>"50"){
	require "../help/impressao.php";//Include de func�es, classes e conex�es com o BD
	
	$igreja = new DBRecord ("igreja","1","rol");
	
	if ($igreja->cidade()>0) {
		$cidSede = new DBRecord('cidade', $igreja->cidade(), 'id');
		$origem = $cidSede->nome();
	}else {
		$origem = $igreja->cidade();
	}
	
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
			$igrejaSelecionada = new DBRecord('igreja', '1', 'rol');
			$titTabela = 'Relat�rio de Lan�amentos';
			$linkImpressao ='tesouraria/receita.php/?rec=15';
			$mes = empty($_GET['mes']) ? '':$_GET['mes'] ;
			$ano = empty($_GET['ano']) ? '':$_GET['ano'];
			$roligreja = '1';
			$tituloColuna5 = 'Valor(R$)';
			 $nomeArquivo='../views/tesouraria/tabRelatLanc.php';
			require_once '../views/modeloPrint.php';
			break;
			case '16':
				$mesRelatorio .=$rolIgreja;
				$dtRelatorio = data_extenso ($d.'/'.$m.'/'.$a);
				$titTabela = 'Fluxo das Contas - '.$dtRelatorio.'<h3>'.$congRelatorio.'<h3>';
				require_once '../models/tes/relatorioComadep.php';
				$nomeArquivo='../views/saldosComadep.php';
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
		
	}
}else {
$ind=1;
$tabRelatorio = 'views/tesouraria/tabDizimosOfertas.php';
if ($_SESSION["setor"]=="2" || $_SESSION["setor"]>"50"){
$_SESSION['lancar']=true;
$linkLancamento  = './?escolha=tesouraria/receita.php&menu=top_tesouraria';
$linkLancamento .= '&igreja='.$_GET['igreja'];

require_once 'views/tesouraria/menu.php';//Sub-Menu de links 

$dizmista = new dizresp($_SESSION['valid_user']);
$idIgreja = (empty($_GET['igreja'])) ? 1:$_GET['igreja'];
if ((int)$_POST['rolIgreja']>0) {
	$idIgreja=$_POST['rolIgreja'];
}
$igrejaSelecionada = new DBRecord('igreja', $idIgreja, 'rol');

	// verifica se h� valor a ser lan�ado e libera os forms
	//printf('<h1> teste %s</h1>',$teste);
	$tituloColuna5 = ($idIgreja>'1') ? 'Congrega��o':'Igreja';
	if ($_POST['concluir']=='1') {
			$tituloColuna5 = 'Status';
			require_once 'forms/lancdizimo.php';
		} elseif ($_POST['lancar']=='1') {
			require_once 'models/feccaixaculto.php';
		} else {
			
			$linkAcesso  = 'escolha=tesouraria/receita.php&menu=top_tesouraria';
			$linkAcesso .= '&rec='.$_GET['rec'].'&idDizOf='.$idDizOf.'&igreja=';
			
			$fin = ($_GET['fin']<'1') ? '2':$_GET['fin'];
					$rec = (empty($_GET['rec'])) ? 0:$_GET['rec'];
			
			switch ($_GET['rec']) {
				case '0':
					require_once ('forms/tes/busca.php');
					//require_once 'forms/tes/histResumo.php';
					break;
				case '1':
					require_once 'forms/concluirdiz.php';
					require_once ('forms/autodizimo.php');
					break;
				case '2':
				    $form = 'forms/tes/autoCompletaContas.php';
					require_once ('forms/lancar.php');
					break;
				case '3':
					require_once ('forms/ofertaEBD.php');
					require_once 'forms/concluirdiz.php';
					break;
				case '4'://Relat�rio COMADEP					
					$mesRelatorio .=$rolIgreja;
					$dtRelatorio = data_extenso ($d.'/'.$m.'/'.$a);
					$titTabela = 'Fluxo das Contas - COMADEP - '.$dtRelatorio.$congRelatorio;
					$recLink = '16&dia='.$d.'&mes='.$m.'&ano='.$a;
					$linkImpressao ='tesouraria/receita.php/?rec='.$recLink.'&igreja='.$_GET['igreja'];
					require_once 'models/tes/relatorioComadep.php';
					require_once ('views/saldosComadep.php');
					require_once 'forms/tes/mesComadep.php';
					break;
				case '5':
				    $form = 'forms/tes/autoLancarDespesas.php';
					require_once ('forms/lancar.php');
					break;
				case '7':
					require_once 'forms/tes/histFinanceiro.php';
					require_once 'models/saldos.php';
					$mes = date('m'); // M�s desejado, pode ser por ser obtido por POST, GET, etc.
					$ano = date('Y'); // Ano atual
					$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 
					$recLink = '14&dtBalac='.$ultimo_dia.'/'.$mes.'/'.$ano;
					$linkImpressao ='tesouraria/receita.php/?rec='.$recLink;
					require_once ('views/saldos.php');
					break;
				case '8':
					require_once 'forms/tes/histFinanceiro.php';
					$titTabela = 'Plano de Contas em: '.date('d/m/Y');
					require_once 'models/saldos.php';
					$recLink = '15&tipo=1';
					$linkImpressao ='tesouraria/receita.php/?rec='.$recLink;
					require_once ('views/saldos.php');
					break;
				case '9':
					$idDizOf = $_GET['idDizOf'];
					$rec = (empty($_GET['rec'])) ? 9:$_GET['rec'];
					require_once 'forms/tes/histResumo.php';
					break;
				case '10':
					$id = (int)$_GET["idDizOf"];
					$tabela = 'dizimooferta';
					$campo 	= 'id';
					require_once 'models/tes/excluir.php';
					break;
				case '11':
					require_once 'forms/tes/histFinanceiro.php';
					require_once 'views/tesouraria/saldoMembros.php';
					break;
				case '12':
					require_once 'forms/tes/histFinanceiro.php';
					require_once 'help/tes/saldoIgrejas.php';
					require_once 'views/tesouraria/saldoIgrejas.php';
					break;
				case '21':
					require_once ('forms/tes/relatorioLanc.php');
					$mes = empty($_GET['mes']) ? '':$_GET['mes'] ;
					$ano = empty($_GET['ano']) ? '':$_GET['ano'];
					$roligreja = (empty($_GET['igreja'])) ? '0':$_GET['igreja'];
					
					$tituloColuna5 = 'Valor(R$)';
					$tabRelatorio = 'views/tesouraria/tabRelatLanc.php';
					break;
				default:
					require_once 'forms/receita.php';
				break;
			}
	}


} else {
	echo "<script> alert('Sem permiss�o de acesso! Entre em contato com o Tesoureiro!');location.href='../?escolha=adm/cadastro_membro.php&uf=PB';</script>";
	$_SESSION = array();
	session_destroy();
	header("Location: ./");
}
	unset($_SESSION['lancar']);
	
	require_once $tabRelatorio;
}
?>
