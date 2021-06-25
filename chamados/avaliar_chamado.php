<!--#include file="../conf/conecta_banco.asp"-->
<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process in page default 'index'

$chamado_id = safepost("chamado_id");

if ($chamado_id != "") {

																					// comment previous  'resgatando as informacoes do chamado
	$sqlChamado = "select * from chamado where chamado_id = ". $chamado_id;
	$rs = $pdo->query($sqlChamado);
	
	foreach($rs as $item){
		
		$area_chamado_id = $item["area_chamado_id"];
		$problema_chamado = $item["problema_chamado"];
		$usuario_id_problema = $item["usuario_id_problema"];
		$data_hora = $item["data_hora"];
		$prioridade_chamado_id = $item["prioridade_chamado_id"];
		$nao_primeira_vez_chamado = $item["nao_primeira_vez_chamado"];
		$nivel_satisfacao_id = $item["nivel_satisfacao_id"];
		$conclusao_chamado_id = $item["conclusao_chamado_id"];
		$observacao_chamado = $item["observacao_chamado"];
		$qtd_horas = $item["qtd_horas"];
		
	}
	
	$sql_sol = "select descricao_solucao_chamado as desc_solucao from solucao_chamado where chamado_id=" . $chamado_id . " and status_chamado_id= 3 ";
	$rs_sol = $pdo->query($sql_sol)->fetchAll();
	
	if (count($rs_sol)==0) {										//code previous .eof then 
		$desc_solucao = $rs_sol["desc_solucao"];
	}else{
		$desc_solucao = "&nbsp;";
	}
	
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
		<title>Cerberus - Atender Chamados</title>
		<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
		<script language="JavaScript1.2" src="../js/cerberus.js" type="text/javascript"></script>
	</head>
	
	<body>
<?php
titulopagina("Avalia&ccedil;&atilde;o de Chamado","P&aacute;gina para avalia&ccedil;&atilde;o de chamados de suporte referentes ao CERBERUS");
?>
			<form action="receivers/recebe_avaliar_chamado.php" method="post"  name="frm" id="frm" >
				<br/>
				<table border="0" cellspacing="0" cellpadding="1" align="center" width="70%">
					<tr class="lista">
						<td class="titulo_lista" colspan="4" style="font-size:14px;">Avalia&ccedil;&atilde;o de Chamado</td>
					</tr>
					<tr>
						<td><strong>Tipo de Problema: </strong></td>
						<td>
<?php
																										//comment previous 'resgatando os valore da tabela area chamado
							$sql = "select * from area_chamado where area_chamado_id=". $area_chamado_id;
							
							$rs = $pdo->query($sql)->fetchAll();
							foreach($rs as $item_area){ 												//code previous while not rs.eof
?>
									<?php $item_area["nome_area_chamado"]; ?>
					
<?php

							}
?>
						</td>
							<td><strong>Nível de Prioridade: </strong></td>
						<td>

<?php					
																										//comment previous 'resgatando os valore da tabela prioridade
						$sql = "select * from prioridade_chamado where prioridade_chamado_id=". $prioridade_chamado_id;
						$rs = $pdo->query($sql)->fetchAll();
						foreach($rs as $item_nome_prio){ 																		//code previous while not rs.eof
?>
							<?= $item_nome_prio["nome_prioridade_chamado"]; ?>
<?php
						}
?>
						</td>                    
					</tr>
					

					<tr>
						<td colspan="4">
							<strong>O problema:</strong>
						</td>
					</tr>
					<tr>
						<td colspan="4">
                        	<p style="font-size:16px; font-weight:bold; color:#333">
                            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $problema_chamado; ?>
                            </p>
                        </td>
					</tr>
                    <tr>
						<td colspan="4">
							<strong>A solu&ccedil;&atilde;o:</strong>
						</td>
					</tr>
					<tr>
						<td colspan="4">
                        	<p style="font-size:16px; font-weight:bold; color:#333">
                            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $desc_solucao; ?>
                            </p>
                        </td>
					</tr>

					<tr>
						<td colspan="2">
							<strong>Isto j&aacute; aconteceu antes?: </strong><?php if($nao_primeira_vez_chamado==1){ print("SIM"); }else{ print("N&Atilde;O"); } ?>
						</td>
                        <td colspan="2"><b>Horas gastas na solu&ccedil;&atilde;o do problema:&nbsp;<?= $qtd_horas . " horas" ?></b></td>
					</tr>    
                    <tr>
                    <td colspan="4"><br><hr width="96%" size="1" class="azul"><br></td>
                    </tr>                                    
					<tr>
						<td colspan="2"><strong>Qual seu n&iacute;vel de satisfa&ccedil;&atilde;o com o atendimento: </strong>

<?php
																			//comment previous 'resgatando os valore da tabela area chamado
						$sql = "select * from nivel_satisfacao_chamado";
						$rs = $pdo->query($sql)->fetchAll();
?>
							&nbsp;&nbsp;<select name="nivel_satisfacao_id">
<?php
						foreach($rs as $item){                 //while not rs.eof
?>
								<option value="<?= $rs["nivel_satisfacao_id"] ?>"  <?php if ($rs["nivel_satisfacao_id"]==$nivel_satisfacao_id){ print("selected='selected'"); } ?>><?= $rs["nome_nivel_satisfacao"]; ?>
                                </option>
<?php
						}
?>
							</select>
	                   </td>
						<td colspan="2"><strong>Seu problema foi ? </strong>
<?php
																	// comment previous 'resgatando os valore da tabela prioridade
						$sql = "select * from conclusao_chamado";
						$rs = $pdo->query($sql)->fetchAll();
?>
							&nbsp;&nbsp;<select name="conclusao_chamado_id">
<?php
						foreach($rs as $item){						//while not rs.eof
?>
								<option value="<?= $rs["conclusao_chamado_id"]; ?>" <?php if ($rs["conclusao_chamado_id"]==$conclusao_chamado_id) { print("selected='selected'"); } ?>><?= $rs["nome_conclusao_chamado"]; ?>
                                </option>
<?php
						}
?>
							</select>                    
						</td>                    
					</tr>
					<tr>
						<td colspan="4" style="text-align:center">
							<br/>
							<strong>Utilize o campo abaixo para expressar-se: (Opcional)</strong>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="text-align:center">
							<textarea name="observacao_chamado" rows="10" cols="100" <?= $observacao_chamado; ?>></textarea>
						</td>
					</tr>                    
					<tr>
						<td colspan="4" align="center">
							<input type="submit"  class="btAzul" name="enviar" value="Enviar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="voltar" class="btAzul" onClick="javascript: history.back();" value="Voltar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btAzul" type="reset" name="limpar" value="Limpar">
						</td>
					</tr>
			</table>
			<br/>
            <input name="chamado_id" type="hidden" value="<?= $chamado_id; ?>">
			</form>
			
	</body>
</html>
<?php
	}											//code previous end if
 ?>
<!--#include file="../conf/desconecta_banco.asp"-->
