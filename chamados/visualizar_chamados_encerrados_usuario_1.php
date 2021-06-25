<!--#include file="../conf/conecta_banco.asp"-->
<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process construct menu 'smoothmenu1'
?>

<?php
	$local = $_REQUEST["local"];
	if ($local=="") { $local = "login";}
	if ($local="login") {
	
		$sql="select count(c.chamado_id) as qtd from chamado as c,solucao_chamado as sc 
		where c.chamado_id=sc.chamado_id and sc.status_chamado_id = 3 and sc.fim_da_fila=1 
		and c.nivel_satisfacao_id is null and c.usuario_id_problema=". $_SESSION["usuario_id"];
		
		$rs3 =$pdo->query($sql)->fetchAll();
		$qtd_chamados = $rs3["qtd"];

		if ($rs3["qtd"] <= 0) {
			// comment previous 'response.Redirect("cerberus/chamados/visualizar_chamados_encerrados_usuario.asp?local=login")
			//code previous response.Redirect("../../roteador_paginas.asp?pagina=chamados")
			
			$url="../../roteador_paginas.asp?pagina=chamados";
			header("Location: ",$url);
		}
	}elseif ($local=="avaliar") {
			//code previous response.Redirect("../default.asp")
			$url="../default.asp";
			header("Location: ",$url);
	} //ending if

	/* comment previous 'sql = "select * from nivel_satisfacao_chamado"
	'satisfacao_string = "<select name='satisfacao' >"
	'set rs = conn.execute(sql)
	'do until rs.eof
	
	'	rs.movenext
	'loop
	'satisfacao_string = satisfacao_string&"</select>"
	'satisfacao_string = replace(satisfacao_string,"'","""")
	 ending comment previous*/
	 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
	<title>Cerberus - Cadastro de Chamados</title>
	<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="refresh" content="60"> 
	<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
	<script language="JavaScript1.2" src="../js/cerberus.js" type="text/javascript"></script>
    <link type="text/css" href="../js/jquery/css/custom-theme/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="../js/jquery/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="../js/jquery/js/jquery-ui-1.8.7.custom.min.js"></script>
	<style>
		h1{
			text-align:center;
		}
		.titulo_lista{
			width:6%;
			text-align:center;
		}
		.problema{
			width:58%;
		}
		td.lista{
			height:30px;
			font-size:12px;
		}
	</style>
</head>

<body>
	
	<h1>Por favor avalie seus chamados finalizados</h1>
	<br>
	<form type="post">
	
		<table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
				<tr class="lista">
					<td class="titulo_lista">N&uacute;mero</td>				
					<td class="titulo_lista problema">Problema</td>
					<td class="titulo_lista">Tipo de Problema</td>
					<td class="titulo_lista">Data</td>
					<td class="titulo_lista">Usu&aacute;rio</td>
					<td class="titulo_lista">Arquivo</td>
					<td class="titulo_lista">Status</td>
					<td class="titulo_lista">Avaliar chamado</td>    
				</tr>
<?php	
			
				$sqlChamados	= "select c.nivel_satisfacao_id, c.numero_chamado, c.problema_chamado, c.chamado_id, ac.nome_area_chamado,
				sc.data_hora, usc.nome , sc.local_arquivo, stc.nome_status_chamado from status_chamado as stc, 
				solucao_chamado as sc left outer join usuario as usc on usc.usuario_id = sc.usuario_id, usuario as uc, chamado as c, area_chamado as ac 
				where c.nivel_satisfacao_id is null and c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema 
				and uc.usuario_id = '". $_SESSION["usuario_id"] ."' and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id 
				and sc.fim_da_fila=1 and sc.status_chamado_id in(3) order by sc.data_hora desc";
				
				$rs =$pdo->query($sqlChamados)->fetchAll();
					
					if (count($rs)==0){ //code previous eof
?>
                <tr class="lista"><td class="lista" style="font-size:12px" colspan="8" align="center">Nenhum chamado em aberto.</td></tr>
<?php
					}else{
				//code previous not rs.eof 
?>
				<tr class="lista">
					<td class="lista"><a href="visualizar_solucoes_chamado.asp?chamado_id=<?= $rs["chamado_id"]; ?>"><?= $rs["numero_chamado"] ."\n"; ?></a></td>
					<td class="lista"><a href="visualizar_solucoes_chamado.asp?chamado_id=<?= $rs["chamado_id"]; ?>"><?= $rs["problema_chamado"] . "\n"; ?></a></td>
					<td class="lista" ><?= $rs["nome_area_chamado"]; ?></td>
					<td class="lista" ><?= $rs["data_hora"]; ?></td>
					<td class="lista" ><?= $rs["nome"]; ?></td>                    
					<td class="lista" >
						<?php if ($rs["local_arquivo"]!= "") { print("<a href='". $rs["local_arquivo"] 
								. "'>Clique Aqui</a>")
							;}?>
					</td>
					<td class="lista" ><?= $rs["nome_status_chamado"] ;?></td>
					<td class="lista" >
						<?php if ($rs["nivel_satisfacao_id"]!="") { ?>&nbsp;
							<?php }else{ ?>
								<a href="avaliar_chamado.asp?chamado_id=<?= $rs["chamado_id"];?>">Avaliar chamado
								</a><?php }?>
					</td>
				</tr>
<?php
				}
?>
				<tr class="lista">
					<td colspan="8" class="lista" style="text-align:center;"><input type="button" name="voltar" class="btAzul" onClick="javascript: history.back();" value="Voltar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if ($local == "login") { ?>
							<input type="button" name="pgPrincipal" class="btAzul" onClick="javascript: location.href='../../roteador_paginas.asp?pagina=chamados';" value="P&aacute;gina Inicial Cerberus">
						<?php ;}?>
					</td>
				</tr>
		</table><br/><br/>
			
	</form>
</body>
</html>
<!--#include file="../conf/desconecta_banco.asp"-->