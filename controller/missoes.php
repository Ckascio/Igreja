<?php 
	if ($_SESSION["setor"]==3 || $_SESSION["setor"]==2 || $_SESSION["setor"]>50){
	
		$bt1 = '';
		$bt2 = '';

			$op = (empty($_GET['rec'])) ? '0' : intval($_GET['rec']) ;
			$bt = 'bt'.$op;
			$$bt = 'active' ;
		
	   switch ($op) {
	   	case '1':
	   		require_once 'forms/missoes/entradas.php';
	   		break;
	   	
	   	default:
	   		# code...
	   		break;
	   }

	}
?>