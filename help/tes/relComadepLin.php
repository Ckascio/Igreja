<?PHP

//print_r($planoCta);


$ctaAtualN4 = '';
$ctaAtualN3 = '';
////echo $planoCta['5']['4'];
//$saldo = array_merge($saldoAnte,$saldo);
#print_r($saldoAnteGrp);

ksort($saldo); #Ordena o array pela chave
#echo "<br /><br />";
#print_r($saldoGrp);
//print_r($saldo);

/* print_r($dataLancDeb);
echo "<br/><br/><br/>";
print_r($dataLancCred);
*/
#print_r($saldo);
$grpFim = FALSE;

foreach ($saldo AS $chave => $valor){
		//$acesso = sprintf("[%04s]\n", $planoCta[$chave]['1']);
		$acesso = '';
		$vlrSaldo = abs($saldo[$chave]);

		$vlrSaldo = number_format($vlrSaldo,2,',','.');
		if ($saldo[$chave]<0) {
				$vlrSaldo .= $cred;
			}elseif ($saldo[$chave]>0) {
				$vlrSaldo .= $dev;
			} else {
				$vlrSaldo = '--o--';
			}

		$vlrSaldoAnte = number_format(abs($saldoAnte[$chave]),2,',','.');
		if ($saldoAnte[$chave]<0) {
				 $vlrSaldoAnte .= $cred;
			}elseif ($saldoAnte[$chave]>0) {
				$vlrSaldoAnte .= $dev;
			} else {
				$vlrSaldoAnte = '--o--';
			}

		$vlrSaldoAtual = number_format(abs($saldo[$chave]+$saldoAnte[$chave]),2,',','.');
		if (($saldo[$chave]+$saldoAnte[$chave])<0) {
				$vlrSaldoAtual  .= $cred;
			}elseif (($saldo[$chave]+$saldoAnte[$chave])>0) {
				$vlrSaldoAtual .= $dev;
			} else {
				$vlrSaldoAtual = '--o--';
			}

		//echo $planoCta[$chave]['4'].' -- ';
		if ($ctaAtualN4==$planoCod[$chave]['nivel4'] || $ctaAtualN4==''){
			//Contas simples
			$codAcesso = sprintf ("%'04u",$planoCod[$chave]['acesso']);
			$nivel1 .='<tr><td>'.$chave.'</td><td title="'.$title.'">'.'['.$codAcesso.'] - '.$planoCod[$chave]['titulo'].
				'</td><td id="moeda">'.$vlrSaldo.'</td><td id="moeda">'.$vlrSaldoAtual.'</td><td id="moeda">'.$vlrSaldoAnte.'</td></tr>';
		}else {

			//Contas Nivel 4, tipo: 1.1.1.001
			$sldGrupoCta = $saldoGrp[$planoCod[$ctaAtualN4]['nivel4']];//Sld do movimento grupo nível 3
			$movSld = number_format(abs($sldGrupoCta),2,',','.');
			if ($sldGrupoCta > 0) {
				$movSld .=  $dev;
			} elseif ($sldGrupoCta < 0) {
				$movSld .= $cred;
			} else {
				$movSld = '--o--';
			}

			$sldGrupoCtaAnte = $saldoAnteGrp[$planoCod[$ctaAtualN4]['nivel4']];//Sld anterior grupo nível 3
			$saldoAntr = number_format(abs($sldGrupoCtaAnte),2,',','.');
			if ($sldGrupoCtaAnte > 0) {
				$saldoAntr .=  $dev;
			} elseif ($sldGrupoCtaAnte < 0) {
				$saldoAntr .= $cred;
			} else {
				$saldoAntr = '--o--';
			}

			$sldGrupoAtual = $sldGrupoCta+$sldGrupoCtaAnte;//Sld atual grupo nível 3
			$saldoAtl = number_format(abs($sldGrupoAtual),2,',','.');
			if ($sldGrupoCtaAnte > 0) {
				$saldoAtl .=  $dev;
			} elseif ($sldGrupoCtaAnte < 0) {
				$saldoAtl .= $cred;
			} else {
				$saldoAtl = '--o--';
			}

			$nivelGrupo ='<tr class="primary"><td>'.$planoCod[$ctaAtualN4]['nivel4'].'</td><td title="'.$title.'">'
				.$planoCod[$planoCod[$ctaAtualN4]['nivel4']]['titulo'].'</td><td id="moeda">'.$movSld
				.'</td><td id="moeda">'.$saldoAtl.'</td>
				<td id="moeda">'.$saldoAntr.'</td></tr>';
			
			$codAcesso = sprintf ("%'04u",$planoCod[$chave]['acesso']);
			$nivel2 .= $nivelGrupo.$nivel1;

			//Contas Nivel codigo, tipo: 1.1.1.001.001
			$nivel1 = '<tr><td>'.$chave.'</td><td title="'.$title.'">'.'['.$codAcesso.'] - '.$planoCod[$chave]['titulo'].
				'</td><td id="moeda">'.$vlrSaldo.'</td><td id="moeda">'.$vlrSaldoAtual.'</td><td id="moeda">'.$vlrSaldoAnte.'</td></tr>';

		}

		if ($ctaAtualN3!=$planoCod[$chave]['nivel3'] && $nivel2!=''){

			//Contas Nivel 3
			$sldN3 = number_format(abs($saldoGrp[$planoCta[$ctaCred]['nivel3']]),2,',','.');
			$sldGrupoCta = $saldoGrp[$planoCod[$ctaAtualN3]['nivel3']];//Sld do movimento grupo nível 3
			$movSld = number_format(abs($sldGrupoCta),2,',','.');
			if ($sldGrupoCta > 0) {
				$movSld .=  $dev;
			} elseif ($sldGrupoCta < 0) {
				$movSld .= $cred;
			} else {
				$movSld = '--o--';
			}

			$sldGrupoCtaAnte = $saldoAnteGrp[$planoCod[$ctaAtualN3]['nivel3']];//Sld anterior grupo nível 3
			$saldoAntr = number_format(abs($sldGrupoCtaAnte),2,',','.');
			if ($sldGrupoCtaAnte > 0) {
				$saldoAntr .=  $dev;
			} elseif ($sldGrupoCtaAnte < 0) {
				$saldoAntr .= $cred;
			} else {
				$saldoAntr = '--o--';
			}

			$sldGrupoAtual = $sldGrupoCta+$sldGrupoCtaAnte;//Sld atual grupo nível 3
			$saldoAtual = number_format(abs($sldGrupoAtual),2,',','.');
			if ($sldGrupoCtaAnte > 0) {
				$saldoAtual .=  $dev;
			} elseif ($sldGrupoCtaAnte < 0) {
				$saldoAtual .= $cred;
			} else {
				$saldoAtual = '--o--';
			}

			#$movSld = ($sldGrupoCta > '0') ? $dev : $cred ;
			#$saldoAtual = ($sldGrupoAtual > '0') ? $dev : $cred ;
 			#$saldoAntr = ($sldGrupoCtaAnte > '0') ? $dev: $cred;

			$nivelN3  = '<tr class="primary"><td>'.$planoCod[$ctaAtualN4]['nivel3'].'</td>';
			$nivelN3 .=	'<td title="'.$title.'">'.$planoCod[$planoCod[$ctaAtualN3]['nivel3']]['titulo'];
			$nivelN3 .=	'</td><td class="text-right">'.$movSld;
			$nivelN3 .=	'</td><td class="text-right">';
			$nivelN3 .=	$saldoAtual.'</td>';
			$nivelN3 .=	'<td class="text-right">';
			$nivelN3 .=	$saldoAntr.'</td></tr>';
			
			$nivel3 .= $nivelN3.$nivel2;
			$nivel2 = '';
		}

		$ctaAtualN4 = $planoCod[$chave]['nivel4'];
		$ctaAtualN3 = $planoCod[$chave]['nivel3'];

}

//$nivel3 .= $nivel1;

$nivel2 = $nivel3;
?>