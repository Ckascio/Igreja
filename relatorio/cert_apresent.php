<br/>
<div class="mainpanel" >
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<?PHP
	$ind = 1;
	if ($_SESSION['nivel']>7){
?>
<fieldset>
	<legend> Certid&atilde;o de Apresenta&ccedil;&atilde;o</legend>
    <form id="form1" name="form1" method="post" action="relatorio/carta_apres.php">
	Estado que nasceu:
	   <label>
	<select name="uf" id="uf" onchange="MM_jumpMenu('parent',this,0)" tabindex="<?PHP echo $ind++;?>" onselect="1">
            <option value="<?PHP echo $_GET['uf'];?>"><?PHP echo $_GET['uf'];?></option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=AC&menu=top_formulario'>Acre</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=AL&menu=top_formulario'>Alagoas</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=AP&menu=top_formulario'>Amap�</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=AM&menu=top_formulario'>Amazonas</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=BA&menu=top_formulario'>Bahia</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=CE&menu=top_formulario'>Cear�</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=DF&menu=top_formulario'>Distrito Federal</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=GO&menu=top_formulario'>Goi�s</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=MA&menu=top_formulario'>Maranh�o</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=MT&menu=top_formulario'>Mato Grosso</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=MS&menu=top_formulario'>Mato Grosso do Sul</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=MG&menu=top_formulario'>Minas Gerais</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=PA&menu=top_formulario'>Par�</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=PB&menu=top_formulario'>Para�ba</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=PR&menu=top_formulario'>Paran�</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=PE&menu=top_formulario'>Pernambuco</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=PI&menu=top_formulario'>Piau�</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=RJ&menu=top_formulario'>Rio de Janeiro</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=RN&menu=top_formulario'>Rio Grande do Norte</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=RS&menu=top_formulario'>Rio Grande do Sul</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=RO&menu=top_formulario'>Rond�nia</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=RR&menu=top_formulario'>Roraima</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=SC&menu=top_formulario'>Santa Catarina</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=SP&menu=top_formulario'>S�o Paulo</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=SE&menu=top_formulario'>Sergipe</option>
            <option value='./?escolha=relatorio/cert_apresent.php&uf=TO&menu=top_formulario'>Tocantins</option>
    </select></label>
	<?PHP 
		if (!empty($_GET["uf"])){
			$vl_uf=$_GET["uf"];
			$lst_cid = new sele_cidade("cidade","$vl_uf","coduf","nome","cidade");
			echo "Na Cidade de:<label>";		
			$vlr_linha=$lst_cid->ListDados ($ind++);//"2" � o indice de tabula��o do formul�rio
			echo "</label>";
	?>
	<br>
	Nome da Crian&ccedil;a:<label>
    <input name="nome" type="text" id="nome" size="50" maxlength="40" tabindex="<?PHP echo $ind++;?>">
	</label>
	<label>Pai:</label>
	<input name="pai" type="text" id="pai" size="50" maxlength="40" tabindex="<?PHP echo $ind++;?>">
	Rol:
	<input name="rol_pai" type="text" id="rol_pai" size="5" tabindex="<?PHP echo $ind++;?>"/>
    <a href="javascript:lancarSubmenu('campo=pai&rol=rol_pai&form=0')" tabindex="<?PHP echo $ind++;?>"><img border="0" src="img/lupa_32x32.png" width="18" height="18" align="absbottom" title="Click aqui para pesquisar membros!" /></a></p>
	<p><label>M&atilde;e:</label>
	<input name="mae" type="text" id="mae" size="50" maxlength="40" tabindex="<?PHP echo $ind++;?>">
	Rol:
	<input name="rol_mae" type="text" id="rol_mae" size="5" maxlength="5" tabindex="<?PHP echo $ind++;?>" />
    <a href="javascript:lancarSubmenu('campo=mae&rol=rol_mae&form=0')" tabindex="<?PHP echo $ind++;?>"><img border="0" src="img/lupa_32x32.png" width="18" height="18" align="absbottom" title="Click aqui para pesquisar membros!" /></a>
	</p>

    <table width="419" border="0">
      <tr>
        <td>Congrega��o dos Pais:<label>
			<?PHP
		 	$congr = new List_sele ("igreja","razao","id_cong");
		 	$congr->List_Selec ($ind++);
		 	?>
        </label></td>
        <td>Hospital de nascimento:<label>
            <input name="maternidade" type="text" id="maternidade" tabindex="<?PHP echo $ind++;?>">
        </label></td>
      </tr>
      <tr>
        <td>Sexo:<label>
			<select name="sexo" id="sexo" tabindex="<?PHP echo $ind++;?>">
				<option value=""  selected>- Selecionar um(a) -</option>
				<option value="M" >Masculino</option>
				<option value="F" >Feminino</option>
			</select></label>		</td>
        <td>Data de Nascimento:<label>
            <input name="dt_nasc" type="text" id="dt_nasc" tabindex="<?PHP echo $ind++;?>" OnKeyPress="formatar('##/##/####', this);" maxlength="10" />
</label></td>
      </tr>
      <tr>
        <td>Folha:<label>
            <input name="fl" type="text" id="fl" tabindex="<?PHP echo $ind++;?>" />
        </label></td>
        <td>Livro:<label>
            <input name="livro" type="text" id="livro" tabindex="<?PHP echo $ind++;?>" />
        </label></td>
      </tr>
      <tr>
        <td>Data da Apresenta&ccedil;&atilde;o:
          <label>
          <input name="dt_apresent" type="text" id="dt_apresent" tabindex="<?PHP echo $ind++;?>" OnKeyPress="formatar('##/##/####', this);" maxlength="10" />
          </label></td>
        <td>N�mero da Certid�o:<label>
            <input name="num_cert" type="text" id="num_cert" tabindex="<?PHP echo $ind++;?>" />
        </label></td>
      </tr>
      <tr>
        <td colspan="2">Observa&ccedil;&otilde;es:
          <label>
          <textarea name="obs" cols="60" id="obs" tabindex="<?PHP echo $ind++;?>"></textarea>
        </label></td>
      </tr>
      <tr>
        <td><label></label></td>
        <td>&nbsp;</td>
      </tr>
    </table>	  
  Secret�rio que ir&aacute; assinar a carta:
  <?PHP $igreja = new DBRecord ("igreja","1","rol");?>
  <select name="secretario" id="secretario" tabindex="<?PHP echo $ind++;?>">
    <option value="<?PHP echo fun_igreja ($igreja->secretario1());?>"><?PHP echo fun_igreja ($igreja->secretario1());?></option>
    <option value="<?PHP echo fun_igreja ($igreja->secretario2());?>"><?PHP echo fun_igreja ($igreja->secretario2());?></option>
  </select>
  <!-- Envia o id para a impress�o da carta escolhida -->
  <input type="image" src="img/Preview-48x48.png" name="Submit2" value="Imprimir esta Carta" align="absmiddle" alt="Visualizar Impress&atilde;o" title="Visualizar Impress&atilde;o" tabindex="<?PHP echo $ind++;?>" />
 <?PHP 
 } //fim do if ap�s selecionar a uf nascimento da crian�a
 ?> 
</form>
</fieldset>
<?PHP
} //Fim do if session>4
controle ("consulta");
?> <fieldset>
	<legend>Busca certid&atilde;o...</legend>
  <table>
    <tr>
      <td>
<form id="form1" name="form1" method="get" action="">
 <label>Busca por:
  <select name="campo" id="campo" tabindex="<?PHP echo $ind++;?>">
    <option value="nome">Crian&ccedil;a</option>
    <option value="pai">Pai</option>
    <option value="rol_pai">Rol do Pai</option>
    <option value="rol_mae">M&atilde;e</option>
    <option value="dt_nasc">Data de Nascimento</option>
    <option value="sexo">Sexo</option>
    <option value="dt_apresent">Data da apresenta&ccedil;&atilde;o</option>
  </select> </label>
  
  <input name="menu" type="hidden" id="menu" value="top_formulario" />
  <input name="escolha" type="hidden" id="escolha" value="relatorio/busca_apresent.php" />
  <input name="valor" type="text" id="valor" tabindex="<?PHP echo $ind++;?>" />  
  <label>
  <input type="submit" name="Submit" value="Procurar..." tabindex="<?PHP echo $ind++;?>" />
  </label></form></td>
      <td>
		<form id="form1" name="form1" method="get" action="">
		<p>Listar por Congrega&ccedil;&atilde;o:
		<select name="menu1" onchange="MM_jumpMenu('parent',this,0)">
		  <option>--&gt;&gt; Escolha a Congrega&ccedil;&atilde;o&lt;&lt;-- </option>
		  <option value="./?escolha=<?PHP echo $_GET["escolha"];?>&proxima=<?PHP echo $_GET["proxima"];?>&ord=<?PHP echo $_GET["ord"];?>&amp;congregacao=0">Todas as Congregac&otilde;es</option>
		  <?PHP
			$congr = new List_sele ("igreja","razao","congregacao");
			$congr->List_Selec_pop ("campo=id_cong&menu=top_formulario&escolha=relatorio/busca_apresent.php&valor=");
		?>
		</select>
		</form></td>
    </tr>
  </table>

</fieldset>
</div>