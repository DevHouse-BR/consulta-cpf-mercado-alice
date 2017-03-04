<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CPF</title>
<link href="Styles/alice/Style.css" type="text/css" rel="stylesheet">
</head>
<body style="background-color:#FFFFFF;" onload="document.forms[0].elements[0].focus();">
<? 

require("funcoes.php"); 
require("header2.html"); 
inicia_quadro_branco_com_sombra("BUSCA CPF");
?>
<form action="mostra_cpf.php" method="post">
<table width="80%">
	<tr>
		<td width="20" style="font-size: 40px; font-weight:bold;">CPF</td>
		<td><input name="cpf" type="text" style="width:98%;font-size: 40px;font-weight:bold;" onKeyDown="if(event.keyCode == 13) document.forms[0].submit();" /></td>
	</tr>
</table>
</form>
<? 
termina_quadro_branco_com_sombra();
require("footer.html"); ?>
</body>
</html>
