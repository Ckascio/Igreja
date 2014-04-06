<?PHP
$imprimir = false;
if ($_GET['imp']>'0') {
	
		
	date_default_timezone_set('America/Recife');
	error_reporting(E_ALL);
	ini_set('display_errors', 'off');
	require_once ("../func_class/classes.php");
	require_once ("../func_class/funcoes.php");
	if ($_GET['imp']==2) {
		require 'imprSantaCeiaTodas.php';
	}else {
		require("funcs_impress.php");
	}
	
	
	function __autoload ($classe) { 
		require_once ("../models/$classe.class.php");
	}

	$roligreja	= (empty($_GET['id'])) ? '1':$_GET['id'];

	$igreja 	= new DBRecord ("igreja",$roligreja,"rol");
	$sede 		= new DBRecord ("igreja",'1',"rol");
	$colunas 	= 6;
	
	if ($roligreja=='1') {
		//Sede
		$dircon		= 'Pastor: '.$sede->pastor();
		$templo		= '<b>Templo Sede </b> ';
	}else {
		//Congrega��es
		$dadocong 	= new DBRecord ("igreja",$roligreja,"rol");
		$dirigente 	= new DBRecord ("membro",$dadocong->pastor(),"rol");
		$dircon		= '<b>Dirig. </b>'.cargo($dadocong->pastor()).': '.$dirigente->nome();
		$templo		= '<b>Congreg.: </b>'.$igreja->razao();
	}
	$ano = (empty($_GET['ano'])) ? date ('Y'):$_GET['ano'];
		$templo = '<div style="background:#CCC;width:265%; text-align:left;">'.$templo.' - Santa Ceia - Calend&aacute;rio '.$ano.'<br /></div> ';

	$imprimir = true; //permite o impress�o do rodap�
	?>
	
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Santa Ceia</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/gif" href="../img/br_igreja.gif">
</head>
<body>
   <table id="playlistTable" summary="">
	<thead>
		<tr>
			<th colspan="3"><span style='font-size: 120%;'><?php echo $templo.$dircon;?></span></th>
		</tr>
	</thead>
   </table>

	<?PHP
	
}else {
	require("calendario/funcs.php");
	$colunas = 2;
}
	/*
	Fun��o geradora de calend�rio.
	Par�metros:

	string
	gerarCalendario([M�S],[ANO],[N�MERO_DE_M�SES],[N�MERO_DE_TABELAS_POR_LINHA],
																	[CONJUTO DE DATAS1]...[CONJUTO DE DATASn],
																	[RODAP�S],
																	[DESCRI��ES DA LEGENDA])

	Os tr�s �ltimos par�metros s�o arrays.
	A marca��o dos dias � feita da seguinte forma:
	dd/mm, para um dia espec�fico ou dd-dd/mm para um intervalo de dias.

	Podem ser criadas marca��es de datas indefinidamente, basta adicion�-las no arquivo
	'calendario.css', usando o nome de classe td_marcadoX, onde X � o n�mero da marca��o.

	*/
	  $marc0=array();//Santa ceia
	  $marc1=array();//Circulo de Ora��o
	  $marc2=array();//dias de culto
	  $marc3=array();//Escola B�blica
	  $marc4=array();
	  $marc5=array();
	  $marc6=array();

	  //Define a Igreja do rodap� dos calendarios
	  $id_igreja = (int) $_GET ["id"];
	  if (empty($id_igreja)){$id_igreja =1;}

	  $igreja_rodape = new DBRecord ("igreja",$id_igreja,"rol");
	  $ceia = $igreja_rodape->ceia();
	  $nome_igreja = $igreja_rodape->razao();

	  if ($id_igreja<>1) {
	  $nome_igreja="Congrega&ccedil;&atilde;o: ".$nome_igreja;
	  }else {
	  $nome_igreja="Templo ".$nome_igreja;
	  }

	  $dia_ceia = substr ($ceia,-1);
	  $semana_ceia = substr ($ceia,-2,1);

	  $todos = "1";
	 if($_GET["mes"]=="") //Caso a classe ainda n�o esteja definida ap�s o for acima
	 $mes= "1"; else $mes=(int)$_GET["mes"];


	 //$mes= "1";
	 $todos ="12";

	if ($mes=="")
	{$mes = date("n");}

	if (empty($_GET["ano"]))
	  $y = date("Y");
	  else
	  $y = (int)$_GET["ano"];
	  
	  if ($_GET['imp']!='1') {
	  	//Gera cabelho de altera��o de ano
	  	prox_ant_ano ();
	  }

	echo gerarCalendario($mes,$y,$todos,$colunas,
	                     array(array()),
                      $nome_igreja,/*Define o texto do Rodap� do calend�rio*/
                      "Santa Ceia"/*Define o texto da legenda*/,$dia_ceia,$semana_ceia);
     if ($_GET["id"]<2)
	  echo "<div >Cultos: Todas as Segunda, Quartas e Domingos. Das 19h �s 21h</div>";
     else
	  echo "<div >Cultos: Todas as Ter&ccedil;as, Quintas e Domingos. Das 19h �s 21h</div>";

     
     if (isset($_GET['imp']) && $_GET['imp']='1') {
     	echo '</body></html>';
	?>

 <div id="footer">
	<?PHP 
	echo "Templo: {$sede->razao()}: {$sede->rua()}, N&ordm; {$sede->numero()} - {$sede->cidade()} - {$sede->uf()}";
	
	?><br />
	  Copyright &copy; <a onclick='target="_blank"' href="http://<?PHP echo "{$sede->site()}";?>/" title="Copyright information"></a>
      Email: <a rel="nofollow" href="mailto: <?PHP echo "{$sede->email()}";?>" onclick='target="_blank"'><?PHP echo "{$sede->email()}";?></a> <br />
	   <?PHP echo "CNPJ: {$sede->cnpj()}";?><br />
   		<?PHP echo "CEP: {$sede->cep()} - Fone: {$sede->fone()} - Fax: {$sede->fax()}";?><br />
	  <p>Designed by <a rel="nofollow"  onclick='target="_blank"' href="mailto: hiltonbruce@gmail.com">Joseilton Costa Bruce.</a></p>
    </div>

<?PHP

     }
     ?>
