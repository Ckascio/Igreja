<?PHP
define ('NOMESYS','NomeDaAplica��o');
define ('NOMEIGR','Denomina��oDaIgreja');
define ('CIDADEIG','CidadeDaIgreja');
define ('UFIG','UfDaCidadeDaIgreja');
define ('DBPATH','localhost');
define ('DBUSER','igreja');
define ('DBPASS','suaSenha');
define ('DBNAME','assembleia');
define ('SECTOR_QUANT','5');#Quantidade de setores da Igreja na cidade
define ('MSGCARTAO','&quot;Este cart&atilde;o s&oacute; ter&aacute; validade com apresenta&ccedil;&atilde;o da carta&quot');#Mensagem do cart�o de membro
$dns = 'mysql://'.DBUSER.':'.DBPASS.'@'.DBPATH.'/'.DBNAME;
/*
 * Usu�rio e senha de Backup:
 * usu�rio: igrejaBKP
 * senha: abPh!jUEyjs@8EK#xX4
 */
?>
