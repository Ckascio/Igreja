<?PHP
    $dia = (empty($_GET['dia'])) ? '' : sprintf("%'02u",$_GET['dia']);
    $ano = (empty($_GET['ano'])) ? date('Y'):$_GET['ano'];
    $refer = (empty($_GET['refer'])) ? '' : $_GET['refer'] ;
    $cta = (empty($_GET['conta'])) ? '' : $_GET['conta'] ;
    $mes = (empty($_GET['mes'])) ? '':sprintf("%'02u",$_GET['mes']) ;
    $roligreja = (empty($_GET['igreja'])) ? '0':intval($_GET['igreja']);
    $tituloColuna5 = 'Valor(R$)';

    if (!empty($_GET['deb'])) {
        $deb =  'checked="checked"' ;
        $debValor =  $_GET['deb'] ;
    }else {
        $deb =  '' ;
        $debValor =  '' ;
    }

    if (!empty($_GET['cred'])) {
        $cred =  'checked="checked"' ;
        $credValor =  '1' ;
    }else {
        $cred =  '' ;
        $credValor =  $_GET['cred'];
    }
?>