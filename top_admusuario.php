<?php 
if ($_SESSION["nivel"]>9){
?>
<div id="tabs">
	<ul>
	  <li><a <?PHP id_corrente ("cad_usuario");?> href="./?escolha=tab_auxiliar/cad_usuario.php&menu=top_admusuario"><span>Cadastrar Usu�rio</span></a></li>
	  <li><a <?PHP id_corrente ("inic_usuario");?> href="./?escolha=tab_auxiliar/inic_usuario.php&menu=top_admusuario"><span>Apagar / Inicializar Usu�rio</span></a></li>
	</ul>
</div>
<?php
}
?>