<?PHP
session_start();
if ($_FILES ["userfile"]["error"])
{
	echo "Problema: ";
	switch ($_FILES ["userfile"]["error"])
	{
		case 1 : echo "O Arquirvo execede o tamanho m�ximo permitido pelo sistema!"; break;
		case 2 : echo "O Arquirvo execede o tamanho m�ximo permitido pelo formul�rio!"; break;
		case 3 : echo "O recebimento do arquivo goi incompleto!"; break;
		case 4 : echo "Nenhum arquivo foi recebido!"; break;
	}
	exit;
}

//O arquivo possui o tipo MIME correto?
if ($_FILES ["userfile"]["type"] != "image/jpeg")
{
	echo "Este arquivo n�o parece ser uma imagem!";
	exit;
}
	
//Insere o arquivo onde gostariamos ../img_membros/
$upfile = "../img_membros/".$_SESSION["rol"].".jpg";
	
if (is_uploaded_file ($_FILES ["userfile"]["tmp_name"]))
{
	if (!move_uploaded_file ($_FILES ["userfile"]["tmp_name"], $upfile))
	{
		echo "N�o pode gravar o arquivo para o local destinado";
		exit;
	}
}
else
{
	echo "� poss�vel que este seja um tipo de arquivo indesej�vel. Arquivo:";
	echo $_FILES ["userfile"]["name"];
	exit;
}
	
print "<SCRIPT> alert('A foto foi enviada com sucesso...'); window.history.go(-1); </SCRIPT>\n";
echo "O arquivo foi carregado com sucesso!<a href=''>Voltar...<a><br><br>";

//Reformata o conte�do do arquivo

copy($upfile,"img/");


//Mostra o que foi carregado
echo "Veja o arquivo que foi carregado: <br><hr>";

echo "<br><hr>";
?>
