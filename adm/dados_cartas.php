<?PHP
if ($_SESSION['nivel']>4){

$igreja = new DBRecord ("igreja","1","rol");
$bsc_rol = $_GET['bsc_rol'];

$tab="adm/atualizar_dados.php";//link q informa o form quem chamar p atualizar os dados
$tab_edit='adm/dados_cartas.php&tabela=carta&bsc_rol='.$bsc_rol.'&campo=';//Link de chamada da mesma p�gina para abrir o form de edi��o do item
$query = "SELECT *,DATE_FORMAT(data,'%d/%m/%Y')AS data FROM carta WHERE rol='".$bsc_rol."' ORDER BY id DESC";
$nmpp="5"; //N�mero de mensagens por p�rginas
$paginacao = Array();
$paginacao['link'] = "?"; //Pagina��o na mesma p�gina

//Faz os calculos na pagina��o
$sql2 = mysql_query ("$query") or die (mysql_error());
$total = mysql_num_rows($sql2) ; //Retorna o total de linha na tabela
$paginas = ceil ($total/$nmpp); //Retorna o total de p�ginas
$pagina = $HTTP_GET_VARS["pagina1"];

if (!isset($pagina)) {$pagina=0;} //Especifica um valor p vari�vel p�gina caso ela esteja setada
$inicio=$pagina * $nmpp; //Retorna qual ser� a primeira linha a ser mostrada no MySQL
$sql3 = mysql_query ("$query"." LIMIT $inicio,$nmpp") or die (mysql_error());
		//Executa a query no MySQL com limite de linhas para ser usado pelo while e montar a array
$arr_dad = mysql_fetch_array ($sql3);

list($diav,$mesv,$anov) = explode("/", $arr_dad["data"]);
//echo '<br />  - Data atual - ultimo Vencimento: '.$rec_alterar->data().' ---- '. ceil( (mktime() - mktime(0,0,0,$mesv,$diav,$anov))/(3600*24));
$diasemissao = ceil( (mktime() - mktime(0,0,0,$mesv,$diav,$anov))/(3600*24)); //quantidade de dias ap�s a emiss�o do recibo
?>
<div id="lst_cad">
	<?PHP
	if (!empty($_GET["bsc_rol"]))
	{
	?>
	<table>
      <tr>
        <td>Tipo:
          <?PHP
			$nome = new editar_form("tipo",$arr_dad["tipo"],$tab,$tab_edit);
			echo "Carta de ".carta($arr_dad["tipo"]);
			//$nome->getMostrar();$nome->getEditar();
			?></td>
        <td>Data:
          <?PHP
          if ($diasemissao==1) {
          	echo ' (Criada hoje)';
          }elseif ($diasemissao<3){
          echo ' (Criada ontem!)';
          }else {
          	echo ' (Criada �: '.$diasemissao. ' dias)';
          }

       $nome = new editar_form("data",$arr_dad["data"],$tab,$tab_edit);
	   $nome->getMostrar();

		if ($diasemissao<='3') {
			$nome->getEditar();
		}elseif ($_GET['campo']=='data') {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">�</span><span class="sr-only">Close</span></button>
			Prazo <strong>EXPIRADO</strong>! Voc� tem at� <strong>3 dias</strong> para alterar esta data!
				    </div>
			<?php
		}
		?></td>
      </tr>
      <tr>
        <td colspan="2">Igreja/Institui&ccedil;&atilde;o:
          <?PHP
		$nome = new editar_form("igreja",$arr_dad["igreja"],$tab,$tab_edit);
		$nome->getMostrar();
		if ($diasemissao<='20') {
			$nome->getEditar();
		}elseif ($_GET['campo']=='igreja')  {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">�</span><span class="sr-only">Close</span></button>
			Prazo <strong>EXPIRADO</strong>! Voc� tem at� <strong>20 dias</strong> para alterar a Igreja de destino!
			</div>
			<?php
		}
		?></td>
      </tr>
      <tr>
        <td>Destino:
        <?PHP

        $det_inteiro = (int)$arr_dad["destino"];

        if ($det_inteiro!=0)
        {
        	$rec = new DBRecord ("cidade",$arr_dad["destino"],"id");// Aqui ser� selecionado a informa��o do campo autor com id=2
			$cidade=$rec->nome()." - ".$rec->coduf();

		}else {
		 	$cidade = $arr_dad["destino"];
		}
		if (isset($cidade)){
				//print $cidade;
				$cid = new editar_form("destino",$cidade,$tab,$tab_edit);
				$cid->getMostrar();
				if ($diasemissao<='20') {
					$cid->getEditar();
				}elseif ($_GET['campo']=='destino')  {
					?>
					<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">�</span><span class="sr-only">Close</span></button>
					Prazo <strong>EXPIRADO</strong>! Voc� tem at� <strong>20 dias</strong> para alterar o destino!
					</div>
					<?php
				}
			}else{
                echo '<div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">�</span><span class="sr-only">Close</span></button>
                    <h3>Sem registro</h3>
                     Nenhuma carta encontrada para este membro!
                    </div>';
			}
		?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3">Observa&ccedil;&otilde;es
		<?PHP
		$nome = new editar_form("obs",$arr_dad["obs"],$tab,$tab_edit);
		$nome->getMostrar();
				if ($diasemissao<='20') {
					$nome->getEditar();
				}elseif ($_GET['campo']=='obs')  {
					?>
					<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">�</span><span class="sr-only">Close</span></button>
					Prazo <strong>EXPIRADO</strong>! Voc� tem at� <strong>20 dias</strong> para alterar a observa��o!
					</div>
					<?php
				}

		?></td>
      </tr>
    </table>
	<?PHP
	}}
	if ($ecles->situacao_espiritual()=='1') {
	?>&Uacute;ltima ocorr&ecirc;ncia para este Membro
	<form id="form1" name="form1" method="get" action="">
	  <label>
	    <input type="submit" class='btn btn-primary' name="Submit" value="Nova Carta" />
      </label>
      <input name="escolha" type="hidden" id="escolha" value="adm/cria_carta.php" />
      <input name="bsc_rol" type="hidden" id="bsc_rol" value="<?php echo $_GET['bsc_rol'];?>" />
      <input name="uf" type="hidden" id="uf" value="PB" />
	</form>
	<?php
	}else {
		echo '<div class="bs-callout bs-callout-warning">';
		echo '<h4>Esta pessoa n�o est� com situa��o regular em nosso rol de membro! </h4>';
		echo 'Para emiss�o de outra carta � necess�rio que esteja em comunh�o com a igreja! ';
		echo 'Se deseja emitir nova transfer�ncia, caso a anterior tenha perdido a validade, ';
		echo 'ou qualquer outro tipo de carta, reintegre-o a comunh�o da igreja e emita nova carta! ';
		echo 'Tendo, ainda, 3 dias para alterar a data e 20 para os demais dados! <br>';
		echo '<strong>Voc� ainda poder� re-imprimir a �ltima!</strong>';
		echo '</div>';
	}
    $cargoIgreja = new tes_cargo();
    $dadosCargo = $cargoIgreja->dadosArray();
    //print_r($dadosCargo);
    //echo $dadosCargo['7']['1']['1']['nome'];
	?>
</div>
<form id="form2" name="form2" method="post" action="relatorio/carta_print.php">
    <input type="hidden" name="id_carta" value="<?PHP echo $arr_dad["id"];?>" />
    <input name="bsc_rol" type="hidden" id="bsc_rol" value="<?php echo $_GET['bsc_rol'];?>" />
  <div class="row">
  <div class="col-xs-5">
  <label>Secret�rio que ir&aacute; assinar a carta:</label>
  <select name="secretario" id="secretario" class='form-control'>
    <option value="1"><?PHP echo $dadosCargo['7']['1']['1']['nome'];?></option>
    <option value="2"><?PHP echo $dadosCargo['7']['1']['2']['nome'];?></option>
  </select></div>
  <!-- Envia o id para a impress�o da carta escolhida -->
  <input type="image" src="img/Preview-48x48.png" name="Submit2" value="Imprimir esta Carta" align="absmiddle" alt="Visualizar Impress&atilde;o" title="Visualizar Impress&atilde;o"/>
  </div>
</form>
