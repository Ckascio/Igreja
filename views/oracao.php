<h1><img src="img/loading2.gif" width="30" height="30"></h1>
<?PHP
/**
 * Joseilton Costa Bruce
 *
 * LICEN�A
 *
 * Please send an email
 * to hiltonbruce@gmail.com so we can send you a copy immediately.
 *
 * @category   Pessoal
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2008-2009 Joseilton Costa Bruce (http://)
 * @license    http://
 * Insere dados no banco do forms/autodizimo.php na tabela:usuario
 */
controle ("tes");

	$vlr = false;

	//Aqui chamar� models/oracao.php para realizar o pre lan�amento
	
$dta = explode("/",$_POST["data"]);
		$d=$dta[0];
		$m=$dta[1];
		$y=$dta[2];
		$res = checkdate($m,$d,$y);
		
	$datalanc = sprintf("%s-%s-%s",$y,$m,$d);

echo '<H1>Data do registo: '.$vlrregistro[0].'</h1>';
echo '<H1>Data do lan�amento: '.$datalanc.'</h1>';

if (($vlr && $vlrregistro[0] == $datalanc) || ($vlr && $vlrregistro[0] =='') ) {
	//Verifica se o caixa do ultima presta��o para a igreja especifica foi encerrado e se h� algum valor em dizimo, oferta ou oferta extra
		
	$sem = semana($_POST["data"]);
	$hist = $_SESSION['valid_user'].": ".$_SESSION['nome'];

	
	echo "<script>location.href='./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec={$_POST["tipo"]}&igreja={$_POST["igreja"]}'; </script>";
	echo "<a href='./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec={$_POST["tipo"]}&igreja={$_POST["igreja"]}'>Continuar0...<a>";
}elseif ($vlrregistro[0] <> $datalanc) {
	echo "<script>alert('Voc� n�o encerrou o caixa do �ltimo culto! Fa�a agora para continuar...');</script>";
	echo "<script>location.href='./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec={$_POST["tipo"]}&igreja={$_POST["igreja"]}'; </script>";
	echo "<a href='./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec={$_POST["tipo"]}&igreja={$_POST["igreja"]}'>Continuar1...<a>";
} else {
	echo "<script>alert('Valor n�o Informado!');</script>";
	echo "<script>location.href='./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec={$_POST["tipo"]}&igreja={$_POST["igreja"]}'; </script>";
	echo "<a href='./?escolha=tesouraria/receita.php&menu=top_tesouraria&rec={$_POST["tipo"]}&igreja={$_POST["igreja"]}'>Continuar2...<a>";
}	
	
	/*
	$value="'{$_SESSION["rol"]}','','','','','','','','','','','','','','','','','','','','','','','',''";
	$eclesiastico = new insert ("$value","eclesiastico");
	$eclesiastico->inserir();
	*/

?>