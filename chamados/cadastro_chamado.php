<!--#include file="../conf/conecta_banco.asp"-->
<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process in page default 'index'
?>
<?
$area_chamado_id = safepost("area_chamado_id");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Cerberus - Cadastro de Chamados</title>
	<link type="text/css" rel="STYLESHEET" href="../css/cerberus.css">
	<link type="text/css" rel="stylesheet" href="../js/jquery/css/custom-theme/jquery-ui-1.8.7.custom.css" />	

	<script type="text/javascript" src="../js/cerberus.js" ></script>
	<script type="text/javascript" src="../js/jquery/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="../js/jquery/js/jquery-ui-1.8.7.custom.min.js"></script>
	
	<style type="text/css">
		.botoes {
			margin-top: 10px;
			text-align: right;
		}
		
		.texto {
			width: 60%
		}
		
		label {
			font-weight: bold;
		}
		
		form {
			text-align: center;
			margin-top: 10px;
		}
		
		.flutuar {
			float: left;
			clear: none;
		}
		
		.linha {
			margin-top: 10px;
		}
		
		#divAlterar {
			display: none;
		}
		<!--CSS para nome escolhido
		.class-nomeEscolhido{
			border-radius: 25px !important;
			background-color: #f2f2ff!important;
			padding: 5px!important; 
			
		}
		.class-nomeEscolhido:hover{
			background-color: #0066ff!important;
			color: #ffffff!important;
			cursor: pointer!important;

		}-->
		#nomeEscolhido{
			display:none;
			border-radius: 25px !important;
			background-color: #0066ff55!important;
			padding: 5px!important; 
		}
		#nomeEscolhido:hover{
			background-color: #0066ff!important;
			color: #ffffff!important;
			cursor: pointer!important;
		}
		.button_v_c{
			border-radius: 25px !important;
			background-color: #f2f2ff!important;
			padding: 10px!important; 
			position: relative;

		}
		.button_v_c:hover{
			background-color: #0066ff!important;
			color: #ffffff!important;
			cursor: pointer!important;

		}
		
	</style>
	
	<script language="javascript" type="text/javascript">
	
		function cadastrarUsuario() {
			if (confirm('Você tem certeza que deseja cadastrar esse usuário?')) {
				$.ajax({
					url: 'cadastraUsuario_ajax.php', 
					dataType: 'json',
					type: 'POST',
					data: {nome: escape($('#buscaNome').val())},
					success: function(data){
						alert(data.resposta);
					},
					error: function() {
						alert('erro');
					}
				});
			}
		}

		$(function () {
			
			$('#alterarUsuario').dialog({
				title: 'Atualização de usuário',
				modal: true,
				width: '40%',
				buttons: {
					Atualizar: function() {
					
						if (confirm('Você tem certeza que deseja alterar esse usuário?')) {
							$.ajax({
								url: 'ajax_atualizaUsuario.php', 
								dataType: 'json',
								type: 'POST',
								data: {nome: escape($('#nome').val()), id: $('#usr_log').val()},
								success: function(data){
									alert(data.resposta);
									$('#nomeEscolhido').text($('#nome').val())
								},
								error: function() {
									alert('erro');
								}
							});
						}
						$( this ).dialog('close');
					},
					Cancelar: function() {
						$( this ).dialog('close');
					}
				}
			});
			
			$('#alterarUsuario').dialog('close');
			
			$('#buscaNome').autocomplete({
				source: function(request, response) {
					$.ajax({
						url: 'listaUsuario_json.php', 
						dataType: 'json',
						data: {nome: escape(request.term)},
						success: function(data){
							//callback(data);
							response( $.map( data, function( item ) {
									return {
										label: item.nome,					//comment previous + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
										value: '',
										id: item.id,
										atualiza: item.atualiza
									}
								}
							));
						}
					});
				},
				minLength: 3,
				delay: 250,
				select: function(event, ui) {
					$('#nomeEscolhido').html(''+ ui.item.label);
					$('#usr_log').val(ui.item.id);
					if (ui.item.atualiza) {
						$("#divAlterar").css('display', 'block');
					} else {
						$("#divAlterar").css('display', 'none');
					}
					$('#nomeEscolhido').css('display', 'block');
				}
			});
			
			$('#btnCadastrar').button();
			$('#btnAlterar').button();
		});
		
		
		function janelaAlterar() {
			var pos = $('#nomeEscolhido').text().indexOf('-') +2;
			var nome = $('#nomeEscolhido').text().substring(pos);

			$('#nomeSelecionado').text(nome);
			$('#nome').val(nome);

			$('#alterarUsuario').dialog('open');
		}
		
	</script>
	
</head>

<body>
<?

	function get_pagina_inical($usuario_id,$pdo){
		//global $pdo;
		//$sql_inicial = "select m.script from perfil p, menu m where m.menu_id=p.menu_id and p.usuario_id='" . $usuario_id . "' and p.pagina_inicial='1'";
		$sql_inicial = "select m.script from perfil p, menu m where m.menu_id=p.menu_id and p.usuario_id='" . $usuario_id . "' and p.pagina_inicial='1'";

		//echo $sql_inicial; //debug 
			//erro $pag_inicial = "../default.asp" chamados/visualizar_chamados.php dois menus;
		
		$rs4 = $pdo->query($sql_inicial)->fetchAll();

			 
		if(count($rs4)!=0){ // code previous not eof
			$pag_inicial = "../" . $rs4["script"];
												//$pag_inicial = "../princ.asp";
			$pag_inicial = "../chamados/visualizar_chamados.php";
		}else{
			$pag_inicial = "../princ.asp";
		}
		return $pag_inicial;
	}

	$area_func_id= get_area_funcional_id_usuario($_SESSION["usuario_id"],$pdo);
	
	//test area function
	if ($_SESSION["usuario_id"]=="2160"){
	$area_func_id=2;
	}
	titulopagina("Abertura de Chamado","P&aacute;gina para abertura de chamados de suporte referentes ao CERBERUS");
	
	//function create for Rafael, listed in conf/funcoes.php
	
	registrar_uso_funcionalidade("vendo tela de abertura de chamado", ""); 
	echo registrar_uso_funcionalidade("vendo tela de abertura de chamado", ""); 
	$pagina_inicial = get_pagina_inical($_SESSION["usuario_id"],$pdo);


?>

		<form action="receivers/recebe_cadastro_chamado.php" method="post" name="frm" id="frm" onSubmit="">
			<br/>
			<table width="" border="0" cellspacing="3" cellpadding="1" align="center">
				<tr class="lista">
                	<td class="titulo_lista" colspan="4" style="font-size:14px;border-left:solid 1px;border-top:solid 1px;">Abertura de Chamado</td>
                </tr>
                <tr>
					<td><strong>Tipo de Problema: </strong></td>
                    <td>
			<?
//comment previous 'resgatando os valore da tabela area chamado

					$sql = "select * from area_chamado";
					$rs = $pdo->query($sql)->fetchAll();
			?>
        	            <select name="area_chamado_id">                    						
			<?
							foreach($rs as $item_area){ //code previous while not rs.eof
			?>
								<option value="<?= $item_area["area_chamado_id"]; ?>" <? if (($item_area["area_chamado_id"]==$area_chamado_id) 
									or ($item_area["area_chamado_id"]=="3" and $area_func_id=="28")) { print("selected='selected'"); }?>><?= $item_area["nome_area_chamado"]; ?>
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
							foreach($rs as $item_prioridade_chamado){ //code previouos while not rs.eof
			?>
								<option value="<?= $item_prioridade_chamado["prioridade_chamado_id"]; ?>"><?= $item_prioridade_chamado["nome_prioridade_chamado"]; ?></option>
			<?
							}
			?>
                        </select>                    
                    </td>                    
				</tr>
<?
		if ($area_func_id == "2" or $area_func_id == "28" or $area_func_id == "16" or $area_func_id == "73") {
?>                
                <tr>
                  <td colspan="4"><b>Busque o usu&aacute;rio que solicitou o atendimento(caso n&atilde;o escolha, o chamado ser&aacute; aberto para o usu&aacute;rio logado)</b></td>
                </tr>
                <tr>
                  <td colspan="4">
					<div class="flutuar" style="margin-top: 4px;margin-right: 5px;"><strong>Usu&aacute;rio:</strong> <input style="clear: none" id="buscaNome" size="50%"/></div>
<?
			if ($area_func_id == "28") {
?>					<div class="flutuar"><div id="btnCadastrar" class="flutuar" onClick="cadastrarUsuario()">Cadastrar usu&aacute;rio</div></div>
<?			}

?>
<!--					<select name="usr_log" id="usr_log">
                  	<option value="">Selecione o usu&aacute;rio</option>
<?
//					sql_usr = "select u.nome, u.usuario_id, u.sigla_categoria, af.sigla from usuario u, area_funcional af where af.area_funcional_id=u.area_funcional_id and u.excluido ='0' and u.usuario_id not in('0169','0487','0860') order by af.sigla,u.nome"
//					set rs_usr = conn.execute(sql_usr)
//					if not rs_usr.eof then
//						while not rs_usr.eof
//							nome_usr = rs_usr("nome")
//							id_usr = rs_usr("usuario_id")
//							sigla_categoria = rs_usr("sigla_categoria")
//							sigla_usr = rs_usr("sigla")
?>
							<option value="<?=id_usr?>"><?=sigla_usr&"/"&sigla_categoria&" - "&nome_usr?></option>
<?						
//						rs_usr.movenext
//						wend

//					end if
?>
                  </select>
-->                  </td>
                </tr>
                <tr>
                  <td colspan="4"> <!--<input type="text" id="nomeEscolhido" style="background-color: rgb(128, 128, 128,0.3)" disabled="disabled"/>-->
					<div class="flutuar" id="" style="margin-top: 4px;margin-right: 5px;"><strong>Usu&aacute;rio escolhido:</strong> <span id="nomeEscolhido" ></span>&nbsp;&nbsp;&nbsp;&nbsp;</div>
					<input type="hidden" name="usr_log" id="usr_log"/>
					<div class="flutuar" id="divAlterar"><div id="btnAlterar" class="flutuar" onClick="janelaAlterar()">Alterar usu&aacute;rio</div><div style="width: 250px; position: absolute;margin-left: 125px;">Apenas os usuários cadastrados por aqui poderam ser alterados.</div></div>
                  </td>
                </tr>
<?
		}
?>              
                <tr>
                	<td colspan="4">
						<strong><br/>Descreva o problema(5000 caracteres):</strong>
                    </td>
                </tr>
                <tr>
                	<td colspan="4">
	                    <textarea name="problema_chamado" rows="10" cols="100"></textarea>
                    </td>
                </tr>
                <tr>
                	<td colspan="4">
						<strong>Isto j&aacute; aconteceu antes?: </strong> <input type="radio" name="nao_primeira_vez_chamado" value="1"><label>Sim</label>&nbsp;&nbsp;&nbsp;<input name="nao_primeira_vez_chamado" type="radio" value="0" checked="checked"><label>N&atilde;o</label>
                    </td>
                </tr>
                <tr>
                	<td colspan="4" align="center">
                    	<input type="submit" name="enviar" class="btAzul" value="Cadastrar Chamado">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="voltar" class="btAzul" onClick="javascript: location.href='<?= $pagina_inicial?>';" value="Voltar">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="limpar" class="btAzul" value="Limpar">
                    </td>
                </tr>
		</table>
		<br/>
		</form>
		
			<br><hr width="100%" size="1" class="azul"><br/>
			
			<h3 style="margin-left:40px; font-size:14px;"><span style="background-color: rgb(232, 244, 246); color:rgb(116,146,184)">Chamados em Aberto</span></h3><br/>
           
			<table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
				<tr class="lista">
					
					<td class="titulo_lista" width="60%">Problema</td>
					<td class="titulo_lista" align="center" width="8%">Tipo de Problema</td>
					<td class="titulo_lista" align="center" width="8%">Data</td>
					<td class="titulo_lista" align="center" width="8%">Usu&aacute;rio</td>
					<td class="titulo_lista" align="center" width="8%">Arquivo</td>
					<td class="titulo_lista" align="center" width="8%">Status</td>
				</tr>
		<?	
			$sqlChamados = "select c.chamado_id,c.problema_chamado, ac.nome_area_chamado, sc.data_hora, usc.nome , sc.local_arquivo, stc.nome_status_chamado 
			from status_chamado as stc, solucao_chamado as sc left outer join usuario as usc on sc.usuario_id = usc.usuario_id , usuario as uc, chamado as c, area_chamado as ac 
			where c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema and uc.usuario_id = '" . $_SESSION["usuario_id"] . "' 
			and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.fim_da_fila=1 and sc.status_chamado_id not in(3,4)";
			//echo $sqlChamados;
			
			//code previous'sqlChamados = "select c.chamado_id,c.problema_chamado, ac.nome_area_chamado, sc.data_hora, usc.nome , sc.local_arquivo, stc.nome_status_chamado from status_chamado as stc, usuario as usc, usuario as uc, chamado as c, area_chamado as ac, solucao_chamado as sc where c.chamado_id = sc.chamado_id and uc.usuario_id = c.usuario_id_problema and usc.usuario_id =* sc.usuario_id and uc.usuario_id = '"&session("usuario_id")&"' and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.fim_da_fila=1 and sc.status_chamado_id not in(3,4)"
			$rs1 = $pdo->query($sqlChamados)->fetchAll(); 
			
			if (count($rs1)==0){ //code previous .eof then
		?>
                <tr class="lista"><td class="lista" style="font-size:12px" colspan="6" align="center">Nenhum chamado em aberto.</td></tr>
		<?
			}else{
				foreach($rs1 as $item_chamados){//while not rs.eof 
		?>
                    <tr class="lista">
                        <td class="lista" style="font-size:12px" height="30"><a href="visualizar_solucoes_chamado.php?chamado_id=<?= $item_chamados["chamado_id"]; ?>"><?= $item_chamados["problema_chamado"] . "\n"?></a></td>
                        <td class="lista" style="font-size:12px" align="center"><?= $item_chamados["nome_area_chamado"]; ?></td>
                        <td class="lista" style="font-size:12px" align="center"><?= $item_chamados["data_hora"]; ?></td>
                        <td class="lista" style="font-size:12px" align="center"><?=$item_chamados["nome"]; ?></td>                    
                        <td class="lista" style="font-size:12px" align="center">
							<? if ($item_chamados["local_arquivo"] != "") { print("<a href='". $item_chamados["local_arquivo"] . "'>Clique Aqui</a>"); } ?>
						</td>
                        <td class="lista" style="font-size:12px" align="center"><?= $item_chamados["nome_status_chamado"]; ?></td>
                    </tr>
		<?
               }  
			}
		?>
			</table><br/><br/>
			
		<!--<div id="alterarUsuario">
			<div class="linha"><strong>Nome Atual: </strong><span id="nomeSelecionado">Teste</span></div>
			<div class="linha"><label for="nome">Alterar para: </label><input type="text" name="nome" id="nome" style="width: 80%;" /></div>
		</div>
		<div onClick="javascript:$('#alterarUsuario').dialog('open')">.</div>
		-->
		
		<br><hr width="100%" size="1" class="azul">
			<h3 style=" font-size:14px; margin-left:40px; "><span style="background-color: rgb(232, 244, 246); color:rgb(116,146,184)">Avalie os chamados encerrados</h3>
			<p><span><input type="button" style="margin-left:40px; text-align:right; padding-right:5px;" name="visualizar_encerrados" onClick="javascript: location.href='visualizar_chamados_encerrados_usuario.php?local=ch';" class="button_v_c" value="Visualizar Chamados Encerrados">&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
			

	</body>
</html>
<!--#include file="../conf/desconecta_banco.asp"-->
