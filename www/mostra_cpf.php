<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Refresh" content="5; URL=index.php"> 
<title>CPF</title>
<link href="Styles/alice/Style.css" type="text/css" rel="stylesheet">
<script language="javascript">
window.status=''
</script>
</head>
<body style="background-color:#FFFFFF;">
<? 
error_reporting(E_ERROR);
require("funcoes.php"); 
require("header2.html"); 
require("conectar_mysql.php");
$result = mysql_query("SELECT situacao FROM cpf where cpf = '" . $_POST["cpf"] . "'");
$registro = mysql_fetch_assoc($result);
if (mysql_num_rows($result) == 0){
	$imagem = "proibido.jpg";
	$texto =  "CPF NÃO CADASTRADO!";
}
else{
	if($registro["situacao"] == "1"){
		$imagem = "permitido.jpg";
		$texto =  "CPF OK!";
	}
	elseif($registro["situacao"] == "0"){
		$imagem = "proibido.jpg";
		$texto =  "CPF NÃO AUTORIZADO!";
	}
}

inicia_quadro_branco_com_sombra("BUSCA CPF");
?>
<br />
<img align="absmiddle" src="<?=$imagem?>" /> &nbsp;<span style="font-size:24px;"><?=$texto?></span>
<br />
<? 
require("desconectar_mysql.php");
termina_quadro_branco_com_sombra();
require("footer.html"); ?>
</body>
</html>
