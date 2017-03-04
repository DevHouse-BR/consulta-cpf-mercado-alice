<?
##################################################################################################

function inicia_quadro_branco_com_sombra($titulo){
	?>
	<table cellpadding="0" cellspacing="0" border="0" width="80%" class="conteudo2">
		<tr>
			<td width="30" height="28" background="imagens/canto_sup_esq_quadro_branco.gif"></td>
			<td background="imagens/lateral_sup_quadro_branco.gif" valign="top">
				<span style="font-size:9px; font-family:">&nbsp;</span><br>
				 <?=$titulo?>
			</td>
			<td width="29" background="imagens/canto_sup_dir_quadro_branco.gif"></td>
		</tr>
		<tr>
			<td width="30" background="imagens/lateral_esq_quadro_branco.gif"></td>
			<td bgcolor="#FFFFFF">
	<?
}

##################################################################################################

function termina_quadro_branco_com_sombra(){
	?>
			</td>
			<td width="29" background="imagens/lateral_dir_quadro_branco.gif"></td>
		</tr>
		<tr>
			<td width="30" height="27" background="imagens/canto_inf_esq_quadro_branco.gif"></td>
			<td background="imagens/lateral_inf_quadro_branco.gif"></td>
			<td width="29" height="27" background="imagens/canto_inf_dir_quadro_branco.gif"></td>
		</tr>
	</table>
	<?
}
?>