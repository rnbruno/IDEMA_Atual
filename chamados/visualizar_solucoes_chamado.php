<!--#include file="../conf/conecta_banco.asp"-->
<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process constructed in directory receivers in chamados
?>
<?php
$chamado_id = safeget("chamado_id");
if ($chamado_id != "") {

																			//comment previous 'resgatando as informacoes do chamado
	$sqlChamado = "select * from chamado where chamado_id = '" . $chamado_id . "'";
	$rs = $pdo->query($sqlChamado)->fetchAll()[0];
	
	$area_chamado_id = $rs["area_chamado_id"];
	$numero_chamado = $rs["numero_chamado"];
	$problema_chamado = $rs["problema_chamado"];
	$usuario_id_problema = $rs["usuario_id_problema"];
	$data_hora = $rs["data_hora"];
	$prioridade_chamado_id = $rs["prioridade_chamado_id"];
	$nao_primeira_vez_chamado = $rs["nao_primeira_vez_chamado"];
	
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
		<title>Cerberus - Visualizar Solu&ccedil;&otilde;es Chamados</title>
		<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
		<script language="JavaScript1.2" src="../js/cerberus.js" type="text/javascript"></script>
	</head>
	
	<body>
<?php
titulopagina("Visualizar Solu&ccedil;&otilde;es Chamados","P&aacute;gina de visualiza&ccedil;&atilde;o das solu&ccedil;&otilde;es dadas ao chamado.");
?>
			<form action="receivers/recebe_atender_chamado.php" method="post"  name="frm" id="frm" onSubmit="">
				<br/>
				<table border="0" cellspacing="0" cellpadding="1" align="center">
					<tr class="lista">
						<td class="titulo_lista" colspan="4" style="font-size:14px;">Visualizar Solu&ccedil;&otilde;es Chamados</td>
					</tr>
					
					<tr>
						<td><br/><strong>Tipo de Problema: </strong></td>
						<td><br/>
<?php
																									//comment previouos 'resgatando os valore da tabela area chamado
						$sql = "select nome_area_chamado from area_chamado  where area_chamado_id = '". $area_chamado_id . "'";
						$rs = $pdo->query($sql)->fetchAll()[0];
						print($rs["nome_area_chamado"]);
?>
        
						</td>
							<td><br/><strong>Nível de Prioridade: </strong></td>
						<td><br/>
<?php
																									//comment previous 'resgatando os valore da tabela prioridade
						$sql = "select nome_prioridade_chamado from prioridade_chamado where prioridade_chamado_id= '" . $prioridade_chamado_id . "'";
						$rs = $pdo->query($sql)->fetchAll()[0];
						print($rs["nome_prioridade_chamado"]);
?>
						</td>                    
					</tr>
					<tr>
                    	<td colspan="4">
							<br><hr width="100%" size="1" class="azul"><br>
                        </td>

                    </tr>
					<tr>
						<td colspan="4">
							<strong>O problema:</strong>
						</td>
					</tr>
					<tr>
						<td colspan="4">
                        	<p style="font-size:16px; font-weight:bold; color:rgb(77, 102, 179)">
                            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $problema_chamado; ?>
                            </p>
                        </td>
					</tr>
                    
					<tr>
					<tr>
                    	<td colspan="4">
							<br><hr width="100%" size="1" class="azul"><br>
                        </td>

                    </tr>
						
						<td colspan="4">
							<strong>Isto j&aacute; aconteceu antes?: </strong><?php 
								if($nao_primeira_vez_chamado==1) {
									print("SIM");
								}else{ 
								print("N&Atilde;O");
								} ?>
						</td>
					</tr>                    
					<tr>
						<td colspan="4"><br/>
                        
                            <table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
                                <tr class="lista">
                                    <td class="titulo_lista" colspan="6">Solu&ccedil;&atilde;o atual</td>
                                </tr>
                                <tr class="lista">
                                	<td class="titulo_lista" width="60%">N&uacute;mero</td>
                                    <td class="titulo_lista" width="60%">Solução</td>
                                    <td class="titulo_lista" align="center" width="10%">Data</td>
                                    <td class="titulo_lista" align="center" width="10%">Usu&aacute;rio</td>
                                    <td class="titulo_lista" align="center" width="10%">Arquivo</td>
                                    <td class="titulo_lista" align="center" width="10%">Status</td>
                                </tr>
<?php	
                            
							$sqlChamados = "select sc.descricao_solucao_chamado, c.numero_chamado, sc.data_hora, usc.nome, sc.local_arquivo, stc.nome_status_chamado 
								from status_chamado as stc, solucao_chamado as sc 
									left outer join usuario as usc on usc.usuario_id = sc.usuario_id, usuario as uc, chamado as c, area_chamado as ac 
								where c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema and  c.area_chamado_id = ac.area_chamado_id 
								and stc.status_chamado_id = sc.status_chamado_id and sc.chamado_id = '" . $chamado_id . "' order by sc.data_hora desc";
																			
							//echo $sqlChamados; //debug													//comment previous 'sqlChamados = "select sc.descricao_solucao_chamado, c.numero_chamado, sc.data_hora, usc.nome, sc.local_arquivo, stc.nome_status_chamado from status_chamado as stc, usuario as usc, usuario as uc, chamado as c, area_chamado as ac, solucao_chamado as sc where c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema and usc.usuario_id =* sc.usuario_id and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.chamado_id ="&chamado_id&" order by sc.data_hora desc"
                            $rs = $pdo->query($sqlChamados)->fetchAll(); 
                            
						 for($cont=0;$cont<count($rs);$cont++){
							
							if (count($rs)==''){									//code previous .eof then
?>
                                <tr class="lista"><td class="lista" style="font-size:12px" colspan="6" align="center">Nenhuma solução para o chamado.</td></tr>
<?php
							}
							elseif ($cont==0){									//code previous .eof then
?>
                                <tr class="lista">
                                        <td class="lista" style="font-size:12px" height="30"><?= $rs[$cont]["numero_chamado"];?></td>
                                        <td class="lista" style="font-size:12px" height="30"><?= $rs[$cont]["descricao_solucao_chamado"];?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$cont]["data_hora"];?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$cont]["nome"];?></td>                                        
										<td class="lista" style="font-size:12px" align="center"><?php if ($rs[$cont]["local_arquivo"] != ""){
											print("<a href='" . $rs[$cont]["local_arquivo"] . "'>Clique Aqui</a>"); }?>
										</td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$cont]["nome_status_chamado"]?></td>
                                </tr>
								<tr class="lista">
                                    <td class="titulo_lista" colspan="6">Solu&ccedil;&otilde;es anteriores</td>
                                </tr>
                                <tr class="lista">
                                	<td class="titulo_lista" width="60%">N&uacute;mero</td>
                                    <td class="titulo_lista" width="60%">Solução</td>
                                    <td class="titulo_lista" align="center" width="10%">Data</td>
                                    <td class="titulo_lista" align="center" width="10%">Usu&aacute;rio</td>
                                    <td class="titulo_lista" align="center" width="10%">Arquivo</td>
                                    <td class="titulo_lista" align="center" width="10%">Status</td>
                                </tr>
<?php
							}else{
                                //while not rs.eof 
								
?>
                                    <tr class="lista">
                                        <td class="lista" style="font-size:12px" height="30"><?= $rs[$cont]["numero_chamado"];?></td>
                                        <td class="lista" style="font-size:12px" height="30"><?= $rs[$cont]["descricao_solucao_chamado"];?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$cont]["data_hora"];?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$cont]["nome"];?></td>                                        
										<td class="lista" style="font-size:12px" align="center"><?php if ($rs[$cont]["local_arquivo"] != ""){
											print("<a href='" . $rs[$cont]["local_arquivo"] . "'>Clique Aqui</a>"); }?>
										</td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$cont]["nome_status_chamado"]?></td>
                                    </tr>
<?php

						 }}
?>
                            </table>                        
						</td>
					</tr>
					<tr>
                    	<td colspan="4">
							<br><hr width="100%" size="1" class="azul"><br>
                        </td>

                    </tr>
                    
					<tr>
						<td colspan="4" align="center">
							<input type="button" name="voltar" class="btAzul" onClick="javascript: history.back();" value="Voltar">
						</td>
					</tr>
			</table>
			<br/>
            <input name="chamado_id" type="hidden" value="<?= $chamado_id; ?>">
			</form>
			
	</body>
</html>
<?php } ?>
<!--#include file="../conf/desconecta_banco.asp"-->
