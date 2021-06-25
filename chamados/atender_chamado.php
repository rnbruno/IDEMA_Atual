<!--#include file="../conf/conecta_banco.asp"-->

<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22','Visualizar Chamados', 'in pagina visualizar_chamados.php'
?>

<?php

$chamado_id = safeget("chamado_id");
if ($chamado_id != "") { //if termina no fim da pag

//code previous 'resgatando as informacoes do chamado
	$sqlChamado = "select * from chamado where chamado_id = ". $chamado_id;
	
	$rs = $pdo->query($sqlChamado)->fetchAll()[0];
	
	$area_chamado_id = $rs["area_chamado_id"];
	$problema_chamado = $rs["problema_chamado"];
	$usuario_id_problema = $rs["usuario_id_problema"];
	$data_hora = $rs["data_hora"];
	$prioridade_chamado_id = $rs["prioridade_chamado_id"];
	$nao_primeira_vez_chamado = $rs["nao_primeira_vez_chamado"];
	
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
		<title>Cerberus - Atender Chamados</title>
		<meta charset="UTF-8" />
		<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
        <link type="text/css" rel="stylesheet" href="../js/jquery/css/custom-theme/jquery-ui-1.8.7.custom.css" />	
        <script type="text/javascript" src="../js/jquery/js/jquery-1.4.4.min.js"></script>
		<script language="JavaScript1.2" src="../js/cerberus.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/jquery/js/jquery-ui-1.8.7.custom.min.js"></script>
        <script>
			$(function(){
									/*comment previous
																						src4= function(request, response) {
																							$.ajax({
																								url: 'listaUsuario_json.asp', 
																								dataType: 'json',
																								data: {nome: escape(request.term)},
																								success: function(data){
																									//callback(data);
																									response( $.map( data, function( item ) {
																											return {
																												label: item.nome,// + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
																												value: '',
																												id: item.id,
																												atualiza: item.atualiza
																											}
																										}
																									));
																								}
																							});
																						};	*/   
			
			src4 = function(request, response) {
				$.ajax({
					url: 'listaUsuario_json.php', 
					dataType: 'json',
					data: {nome: escape(request.term)},
					success: function(data){
						//callback(data);
						response( $.map( data, function( item ) {
								return {
									label: item.nome,
									id: item.id,
									value: '',
									atualiza: item.atualiza
								}
							}
						));
					}
				});
			}
			selev5 = function(event, ui) {
				$('#usr_atendente').val(ui.item.label);
				$('#usr_atendente_id').val(ui.item.id);
				return false;
			};
			focusev=function(){
				return false;	
			}
			$("#usr_atendente").autocomplete({source:src4, minLength: 3, select:selev5, focus:focusev});	   
			
			});
		
		/*
		
		src = function(request, response) {
			$.ajax({
				url: 'ajax/json_get_auto.php', 
				type: 'POST',
				dataType: 'json',
				data: {term: request.term },
				success: function(data){
					//callback(data);
					response( $.map( data, function( item ) {
							return {
								label: item.label,
								value: item.value
							}
						}
					));
				}
			});
		}
		selev = function(event,ui){
			num_auto = ui.item.label;
			$("#numero_auto").val(num_auto);
			documento_id = ui.item.value;
			$("#documento_id").val(documento_id);
			get_dados_auto(documento_id);
			return false;
		}
		focusev = function(){
			return false;	
		}
		
		*/
		// $("#empreendedor").autocomplete({source:src2, minLength: 3, delay: 150, select:selev5, focus:focusev});
		
        function showQtdHoras(){ // funcao para verificar se o status é encerrado para mostrar o campo de quantidade de horas
			var status_chamado_id = document.getElementById('status_chamado_id').value;
			if(status_chamado_id==3){ //status encerrado = 3 no banco
				document.getElementById('qtd_horas').style.display = 'block';
				document.getElementById('desc_ch').style.display = 'block';
				document.getElementById('sel_ch').style.display = 'block';
				document.getElementById("descricao_servico").disabled = false;
				$("#div_usr_atendente").css("display","none");
			}else if(status_chamado_id==2){
				$("#div_usr_atendente").css("display","block");
			}else{
				$("#div_usr_atendente").css("display","none");
				document.getElementById('qtd_horas').style.display = 'none'; 
				document.getElementById('desc_ch').style.display = 'none';
				document.getElementById('sel_ch').style.display = 'none';
				document.getElementById("descricao_servico").disabled = true;
			}
		}
		function validar_chamado(){
			var status_chamado_id = document.getElementById('status_chamado_id').value;
			if(status_chamado_id==3){ //status encerrado = 3 no banco
				if (document.getElementById("descricao_servico").value == "") {
					alert("Escolha um tipo de serviço para o chamado em aberto!");
					document.getElementById("descricao_servico").focus();
					return false	
				}
			}
			
		}
        
        </script>
	</head>
	<style>
	#qtd_horas {
		display:none;
	}
	</style>
	<body>
<?php
titulopagina("Atender/Visualizar Chamado","P&aacute;gina para atendimento de chamados de suporte referentes ao CERBERUS");

$area_id = $_SESSION["area_id"];
		
if ($area_id = "0095"){
	$servico_padrao = 5;
	$status_padrao = 3;
}

?>		
			<form action="receivers/recebe_atender_chamado.php" method="post"  name="frm" id="frm" height="auto" style="margin-top: 20px; margin-bottom: 100px; margin-right: 80px;
				margin-left: 80px; " onSubmit=" return validar_chamado();">
				<br/>
				<table border="0" cellspacing="0" cellpadding="1" align="center" style= "border-style: solid; border-color:rgb(194, 212, 212,0.4); ">
					<tr class="lista">
						<td class="titulo_lista" colspan="4" style="font-size:14px;">Atender/Visualizar Chamado</td>
					</tr>
					<tr>
						<td><strong>Tipo de Problema: </strong></td>
						<td>
			<?
						//comment previous'resgatando os valores da tabela area chamado
						$sql = "select * from area_chamado";
						$rs = $pdo->query($sql)->fetchAll();
			?>
							<select name="area_chamado_id">                    						
			<?
								foreach($rs as $item){ //code previous not rs.eof
			?>
									<option value="<?= $item["area_chamado_id"]; ?>" <? 
										if ($item["area_chamado_id"]==$area_chamado_id) {
											print("selected='selected'"); } ?>><?= $item["nome_area_chamado"]; ?>
									</option>
			<?
						}
			?>
							</select>                    
						</td>
							<td><strong>Nível de Prioridade: </strong></td>
						<td>
			<?
//comment previous 'resgatando os valores da tabela prioridade
						$sql = "select * from prioridade_chamado";
						$rs = $pdo->query($sql)->fetchAll();
			?>
							<select name="prioridade_chamado_id">
			<?
								foreach($rs as $item_prioridade){
			?>
										<option value="<?= $item_prioridade["prioridade_chamado_id"]; ?>"  <? 
											if ($item_prioridade["prioridade_chamado_id"]== $prioridade_chamado_id) {
												print("selected='selected'"); } ?> > <?= $item_prioridade["nome_prioridade_chamado"]; ?>
										</option>
			<?
								}
			?>
							</select>                    
						</td>                    
					</tr>
					<tr>
						<td colspan="4">
							<strong>O problema:</strong>
						</td>
					</tr>
					<tr>
						<td colspan="4">
                        	<p style="font-size:16px; font-weight:bold; color:rgb(77, 102, 179); margin-top:8px;" height="auto";>
                            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $problema_chamado ;?>
                            </p>
                        </td>
					</tr>
                    
					<tr>
						<td colspan="4"><br/>
							<strong>Isto j&aacute; aconteceu antes?: </strong><? if($nao_primeira_vez_chamado){ print("SIM"); }else{ print("N&Atilde;O"); } ?>
						</td>
					</tr>                    
					<tr>
						<td colspan="4">
						
						<?
                            
							
							$sqlChamados = "select sc.descricao_solucao_chamado, sc.data_hora, usc.nome, sc.local_arquivo, stc.nome_status_chamado, qtd_horas
							from status_chamado as stc,  solucao_chamado as sc left outer join usuario as usc
							on sc.usuario_id = usc.usuario_id , usuario as uc, chamado as c, area_chamado as ac
							where c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema
							and  c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.chamado_id =". $chamado_id ." order by sc.data_hora desc";
							
							//echo $sqlChamados; //debug
							
//comment previous 'sqlChamados = "select sc.descricao_solucao_chamado, sc.data_hora, usc.nome, sc.local_arquivo, stc.nome_status_chamado, qtd_horas from status_chamado as stc, usuario as usc, usuario as uc, chamado as c, area_chamado as ac, solucao_chamado as sc where c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema and usc.usuario_id =* sc.usuario_id and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.chamado_id ="&chamado_id&" order by sc.data_hora desc"
                            $rs = $pdo->query($sqlChamados)->fetchAll(); 
                            ?>
                        
                            <table class="lista" border="1" width="100%" cellpadding="1" cellspacing="0" align="center">
                                
								
						<?php for($count=0;$count<count($rs);$count++){ 		
                                   if($count==0){?> 
								<tr class="lista">
									<td class="titulo_lista" width="60%">Solu&ccedil;&atilde;o atual</td>
									<td class="titulo_lista" align="center" width="10%">Data</td>
                                    <td class="titulo_lista" align="center" width="10%">Usu&aacute;rio</td>
                                    <td class="titulo_lista" align="center" width="10%">Arquivo</td>
                                    <td class="titulo_lista" align="center" width="10%">Status</td>
                                    <td class="titulo_lista" align="center" width="10%">Tempo</td>
								</tr>
								<tr class="lista">
                                        <td class="lista" style="font-size:12px" height="auto"><?= $rs[$count]["descricao_solucao_chamado"]; ?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$count]["data_hora"]; ?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?=$rs[$count]["nome"]; ?></td>                                        
										<td class="lista" style="font-size:12px" align="center"><? if ($rs[$count]["local_arquivo"] != "") { print("<a href='" . $rs[$count]("local_arquivo") . "'>Clique Aqui</a>"); }?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?=$rs[$count]["nome_status_chamado"]; ?></td>
            <?
										if ($rs[$count]["qtd_horas"]!=""){
			?>
                                        	<td class="lista" style="font-size:12px" align="center"><?= $rs[$count]["qtd_horas"] . "h"?></td>
            <?
										}else{
											?>
                                        	<td class="lista" style="font-size:12px" align="center"></td>
                                    		<?
                                        }
                                        ?>
								</tr>
								<tr class="lista">
                                    <td class="titulo_lista" width="60%">Solu&ccedil;&otilde;oes Anteriores</td>
                                    <td class="titulo_lista" align="center" width="10%">Data</td>
                                    <td class="titulo_lista" align="center" width="10%">Usu&aacute;rio</td>
                                    <td class="titulo_lista" align="center" width="10%">Arquivo</td>
                                    <td class="titulo_lista" align="center" width="10%">Status</td>
                                    <td class="titulo_lista" align="center" width="10%">Tempo</td>
                                </tr>
						
						<?php;}else{?>
							
								<tr class="lista">
                                        <td class="lista" style="font-size:12px" height="auto"><?= $rs[$count]["descricao_solucao_chamado"]; ?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $rs[$count]["data_hora"]; ?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?=$rs[$count]["nome"]; ?></td>                                        
										<td class="lista" style="font-size:12px" align="center"><? if ($rs[$count]["local_arquivo"] != "") { print("<a href='" . $rs[$count]("local_arquivo") . "'>Clique Aqui</a>"); }?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?=$rs[$count]["nome_status_chamado"]; ?></td>
            <?
										if ($rs[$count]["qtd_horas"]!=""){
			?>
                                        	<td class="lista" style="font-size:12px" align="center"><?= $rs[$count]["qtd_horas"] . "h"?></td>
            <?
										}else{
											?>
                                        	<td class="lista" style="font-size:12px" align="center"></td>
                                    		<?
                                        }
                                        ?>
								</tr>
						<?php };
									}?>
								
            <!--<?
                            
                            
                            if (count($rs)==0){ //code previous eof then
            ?>
                                <tr class="lista"><td class="lista" style="font-size:12px" colspan="6" align="center">Nenhuma solu&ccedil;&atilde;o para o chamado.</td></tr>
 
			<?			 }else{
                                foreach($rs as $item_chamado){ //code previoous not rs.eof 
			?>
                                    <tr class="lista">
                                        <td class="lista" style="font-size:12px" height="auto"><?= $item_chamado["descricao_solucao_chamado"]; ?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?= $item_chamado["data_hora"]; ?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?=$item_chamado["nome"]; ?></td>                                        
										<td class="lista" style="font-size:12px" align="center"><? if ($item_chamado["local_arquivo"] != "") { print("<a href='" . $item_chamado("local_arquivo") . "'>Clique Aqui</a>"); }?></td>
                                        <td class="lista" style="font-size:12px" align="center"><?=$item_chamado["nome_status_chamado"]; ?></td>
            <?
										if ($item_chamado["qtd_horas"]!=""){
			?>
                                        	<td class="lista" style="font-size:12px" align="center"><?= $item_chamado["qtd_horas"] . "h"?></td>
            <?
										}else{
											?>
                                        	<td class="lista" style="font-size:12px" align="center"></td>
                                    		<?
                                        }
                                        ?>
                                    </tr>
            <?
								}
						}
			?>-->
                            </table>                        
						</td>
					</tr>
					<tr>
                    	<td colspan="4">
							<br><hr width="100%" size="1" class="azul"><br>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="4">
							<strong>Defina o status do chamado para o usu&aacute;rio:</strong>
			<?
//comment previous 'resgatando os valores da tabela status
						$sql = "select * from status_chamado";
						$rs =$pdo->query($sql)->fetchAll();
			?>
							<select name="status_chamado_id" id="status_chamado_id" onChange="showQtdHoras();">
			<?
						foreach($rs as $item_status_chamado){ //code previous not rs.eof
						?>
								<option value="<?= $item_status_chamado["status_chamado_id"]?>"<?
									if ($status_padrao == $item_status_chamado["status_chamado_id"]) {
										?> selected="selected"<?
									}
								
									?>><?= $item_status_chamado["nome_status_chamado"];?>
								</option>
						<?
						}
						?>
							</select>
                        </td>
                    </tr>  
                    <tr>
                    	<td colspan="4">
							<div id="div_usr_atendente" style="display:none"><strong>Usuario atendimento</strong> 
								<input type="text" name="usr_atendente" id="usr_atendente" size="45" /> 
									<input type="hidden" name="usr_atendente_id" id="usr_atendente_id" value="" />
							</div>
                        </td>
                    </tr>                                     
					<tr>
						<td colspan="4">
							<div id="desc_ch" style="display:none"><strong>Tipo de Servi&ccedil;o do Chamado:</strong></div>
						</td>
					</tr>
                    <tr>
						<td colspan="4">
                        <div id="sel_ch" style="display:none">
<?
						$sql_servico = "select servico_chamado_id, descricao_servico from servico_chamado order by descricao_servico";
						$rs_servico =$pdo->query($sql_servico)->fetchAll();
?>	
						<select name="descricao_servico" id="descricao_servico">
                        <option value="">Selecione um tipo servi&ccedil;o</option>
<?						
						if(count($rs_servico)!=0){  //code previous not rs_servico.eof
						
								foreach($rs_servico as $item_servico){ //code previous do while not rs_servico.eof
									$servico_chamado_id = $item_servico["servico_chamado_id"];
									$descricao_servico = $item_servico["descricao_servico"];
?>
									<option value="<?= $servico_chamado_id; ?>" <?
										if ($servico_chamado_id == $servico_padrao) {
											?> selected="selected"<?
										}
									?>><?= $descricao_servico?>
									</option>
						
<?								}			
						}else{
?>
  							<option value="">Nenhum servi&ccedil;o est&aacute; cadastrado!</option>                         
<?						
						}
?>                        
						</select>
                        </div>
						</td>
					</tr>
                    <tr>
                    	<td colspan="4">
							<strong>Descreva sua soluçao abaixo:</strong>
                        </td>
                    </tr>                                       
                    <tr>
                    	<td colspan="4" style="text-align: center;">
							<textarea name="descricao_solucao_chamado" rows="10" cols="100"></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="4">
							<div id="qtd_horas"><strong>Tempo de Conclus&atilde;o</strong> <input type="text" name="qtd_horas" maxlength="3" size="3" value="0.5"/>&nbsp;&nbsp;&nbsp;Horas</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <strong>Inserir arquivo? </strong> <input type="radio" name="inserir_arqivo" value="1"><label>Sim</label>&nbsp;&nbsp;&nbsp;
								<input name="inserir_arqivo" type="radio" value="0" checked="checked"><label>N&atilde;o</label>
                        </td>                   
                    </tr>
					<tr>
						<td >
                            <label>
							<input type="checkbox" name="enviarcomorecado" value="sim" />
							Enviar mensagem como recado.
							</label>
                        </td> 
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="submit" class="btAzul" name="enviar" value="Enviar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" name="voltar" class="btAzul" onClick="javascript: history.back();" value="Voltar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="reset" class="btAzul" name="limpar" value="Limpar">
						</td>
					</tr>
					<td colspan="4">
							<br><hr width="100%" size="1" class="azul"><br>
                    </td>
			</table>
			<br/>
            <input name="chamado_id" type="hidden" value="<?= $chamado_id ?>">
			</form>
			
	</body>
</html>

<script>
<?
	if ($area_id = "0095") {
?>
	showQtdHoras();
<?
	}
?>
</script>

<? } // if termina?>
<!--#include file="../conf/desconecta_banco.asp"-->
