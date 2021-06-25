<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process construct menu 'smoothmenu1'
?>

<?php
	$local = safeget("local");

	if ($local=="") 
		{ $local = "login"; }
	if ($local=="login") {
		$sql_visualizar_chamados_encerrados = "select count(c.chamado_id) as qtd from chamado as c,solucao_chamado as sc 
		where c.chamado_id=sc.chamado_id and sc.status_chamado_id = 3 and sc.fim_da_fila=1 and c.nivel_satisfacao_id is null 
		and c.usuario_id_problema= '". $_SESSION["usuario_id"] ."'";
		
		
		$rs3 = $pdo->query($sql_visualizar_chamados_encerrados)->fetchAll();
		$qtd_chamados = $rs3['qtd'];
		

			if($qtd_chamados <= 0) {
				//comment previous 'response.Redirect("cerberus/chamados/visualizar_chamados_encerrados_usuario.asp?local=login")
				$pagine_redirection="../../roteador_paginas.php?pagina=visualizar_chamados_encerrados_usuario";
				header('Location: '. $pagine_redirection);
			}elseif ($local=="avaliar"){
				$pagine_redirection="../default.php";
				header('Location: '. $pagine_redirection);
			}
	}

	//construct options for $satisfacao_string
	$sql = "select * from nivel_satisfacao_chamado";
	$satisfacao_string = "<select name=\"satisfacao_[id]\" >";
	
	$rs =$pdo->query($sql)->fetchAll();
	
	  //comment code do and while(until rs.eof)/ substitution
	foreach($rs as $item){
			$satisfacao_string = $satisfacao_string . "<option value=\"". $item['nivel_satisfacao_id'] . "\">". $item['nome_nivel_satisfacao']. "</option>";
		}
	
	$satisfacao_string = $satisfacao_string . "</select>";
	$satisfacao_string = str_replace("'",'""',$satisfacao_string);              //code previous replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")
	
	//construct options for $conclusao_string
	$sql = "select * from conclusao_chamado";
	$conclusao_string = "<select name=\"conclusao_[id]\" >";
	
	
	$rs =$pdo->query($sql)->fetchAll();
	//foreach($rs as $item){
	//		$conclusao_string = $conclusao_string . "<option value='" . $item['conclusao_chamado_id'] . "'>" . $item['nome_conclusao_chamado'] . "</option>";
	//	}
	
	
	//comment code do and while(until rs.eof)/ substitution if
	foreach($rs as $item){
			$conclusao_string = $conclusao_string . "<option value=\"" . $item['conclusao_chamado_id'] . "\">" . $item['nome_conclusao_chamado'] . "</option>";
	}
	
	$conclusao_string = $conclusao_string . "</select>";
	$conclusao_string = str_replace("'",'""',$conclusao_string);                //code previous replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")
	
	
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head>
	<title>Cerberus - Cadastro de Chamados</title>
	<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
	
	<link type="text/css" rel="stylesheet" href="../js/jquery/css/custom-theme/jquery-ui-1.8.7.custom.css" />
	
	<script type="text/javascript" src="../js/jquery/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="../js/jquery/js/jquery-ui-1.8.7.custom.min.js"></script>
	
	<style>
		h1{
			text-align:center;
		}
		.titulo_lista{
			text-align:center;
		}
		.problema{
			width:52%;
		}
		td.lista{
			height:30px;
			font-size:12px;
		}
		label {
			margin-left: 10px;
		}
	</style>
	
	<script>
		function marcarTodosSatisfacao() {
			$('select[name^="satisfacao_"]').val($('select[name^="satisfacao_todos"]').val());
		}
		
		function marcarTodosConclusao() {
			$('select[name^="conclusao_"]').val($('select[name^="conclusao_todos"]').val());
		}
		
		function marcarTodosAvaliar(elemento) {
			if (elemento.checked) {
				$('input[type="checkbox"]').attr('checked', 'checked');
			} else {
				$('input[type="checkbox"]').attr('checked', '');
			}
		}
		
		function atualizarCheckbox(elemento) {
			
			if (!elemento.checked) {
				$('input[name="chamado_todos"]').attr('checked', '');
			}
			
			if ($('input:checkbox').length -1  == $('input:checked').length) {
				$('input[name="chamado_todos"]').attr('checked', 'checked');
			}
		}
		
	</script>
</head>

<body>
	
	<h1>Por favor, avalie seus chamados finalizados</h1>
	<br>
	
	<form method="post" action="receivers/recebe_avaliar_chamado.php">
		<div style="text-align:center">
		
			<label>
			Avaliar todos
			<input type="checkbox" name="chamado_todos" onclick="marcarTodosAvaliar(this)">
			</label>
			<label>
			Conclus&atilde;o para todos
			<?php $marcarTodosConclusao = "todos \" onchange=\"marcarTodosConclusao()"; ?>
<?= str_replace("[id]",$marcarTodosConclusao,$conclusao_string);
						//previous code replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")
?>	
			</label>
			<label>
			Satisfa&ccedil;&atilde;o para todos 
<?= str_replace("[id]", "todos\" onchange=\"marcarTodosSatisfacao()", $satisfacao_string);
?>
			</label>
		</div>
		<br/>
		<table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
			<tr class="lista">
				<td class="titulo_lista">Avaliar</td>
				<td class="titulo_lista">N&uacute;mero</td>
				<td class="titulo_lista">Conclus&atilde;o</td>	
				<td class="titulo_lista">Satisfa&ccedil;&atilde;o</td>
				<td class="titulo_lista problema">Problema</td>
				<td class="titulo_lista">Tipo de Problema</td>
				<td class="titulo_lista">Data</td>
				<td class="titulo_lista">Usu&aacute;rio</td>
				<td class="titulo_lista">Arquivo</td>
				<td class="titulo_lista">Status</td>    
			</tr>
<?php
			
		$sqlChamados = "select c.nivel_satisfacao_id, c.numero_chamado, c.problema_chamado, c.chamado_id, ac.nome_area_chamado,
		sc.data_hora, usc.nome , sc.local_arquivo, stc.nome_status_chamado
		from status_chamado as stc, solucao_chamado as sc left outer join usuario as usc 
		on usc.usuario_id = sc.usuario_id, usuario as uc, chamado as c, area_chamado as ac where c.nivel_satisfacao_id is null 
		and c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema and uc.usuario_id = '". $_SESSION["usuario_id"]."' 
		and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.fim_da_fila=1 
		and sc.status_chamado_id in(3) order by sc.data_hora desc";

		
		$rs= $pdo->query($sqlChamados)->fetchAll(); 
			
		if(count($rs)==0){	//previous code eof 
?>
			<tr class="lista"><td class="lista" style="font-size:12px" colspan="11" align="center">Nenhum chamado em aberto.</td></tr>
<?php
		}else{
				// previous code do,eof 
			foreach($rs as $item){

?>			<tr class="lista">
				<td class="lista"><input type="checkbox" name="chamado_id_selecionado[]" value="<?= $item["chamado_id"]; ?>" onclick="atualizarCheckbox(this)"/></td>
				<td class="lista"><a href="visualizar_solucoes_chamado.php?chamado_id=<?= $item["chamado_id"]?>"><?= $item["numero_chamado"];?></a></td>
				<td class="lista"><?= str_replace("[id]", $item["chamado_id"],$conclusao_string);?></td>
				<td class="lista"><?= str_replace("[id]", $item["chamado_id"],$satisfacao_string); 	?></td>
				<td class="lista"><a href="visualizar_solucoes_chamado.php?chamado_id=<?= $item["chamado_id"] ?>"><?= $item["problema_chamado"]; ?></a></td>
				<td class="lista" ><?= $item["nome_area_chamado"]; ?></td>
				<td class="lista" ><?= $item["data_hora"]; ?></td>
				<td class="lista" ><?= $item["nome"]; ?></td>                    
				<td class="lista" ><?php if ($item["local_arquivo"]!="") { print("<a href='" . $item["local_arquivo"] . "'>Clique Aqui</a>"); } ?> </td>
				<td class="lista" ><?= $item["nome_status_chamado"]; ?>

				</td>
			</tr>
<?php
            }   
		} //ending else

?>            <tr class="lista">
				<td colspan="11" class="lista" style="text-align:center;">
<?php
			if ($local == "login"){
?>
					<input type="button" name="pgPrincipal" class="btAzul" onClick="javascript: location.href='../../roteador_paginas.php?pagina=chamados';" value="Página Inicial Cerberus"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
			}
?>
					<input type="submit" class="btAzul" value="Avaliar selecionados" />
                </td>
            </tr>
			</table><br/><br/>
	</form>
	<script language="JavaScript1.2" src="../js/cerberus.js" type="text/javascript"></script>
</body>
</html>
<!--#include file="../conf/desconecta_banco.asp"-->