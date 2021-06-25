<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in pagina visualizar_chamados.php
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Cerberus - Visualizar Chamados</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="refresh" content="60"> 
	<link rel="STYLESHEET" type="text/css" href="../css/cerberus.css">
	<script language="JavaScript1.2" src="../js/cerberus.js" type="text/javascript"></script>
    <link type="text/css" href="../js/jquery/css/custom-theme/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="../js/jquery/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="../js/jquery/js/jquery-ui-1.8.7.custom.min.js"></script>

<style>
#aviso_qt {
	position: relative;
	top: 10px;
	background-color: #ff0000;
	border-radius: 5px;
	font-size: 11px;
	color: #ffffff;
	float: right;
	font-weight: bold;
	padding: 0px 3px 0px 3px;
	cursor: pointer;
}

#aviso_widget {
	float: right;
}
.button_v_c{
	border: 1px solid #7094ff22;
	border-radius: 25px !important;
	background-color: #f2f2ff!important;
	padding: 10px!important; 
	position: relative;
	left:40%;

}
.button_v_c:hover{
	background-color: #0066ff!important;
	color: #ffffff!important;
	cursor: pointer !important;

}
</style>

<body>
<?php
titulopagina("Visualizar Chamados","Visualizador de chamados feitos pelos usuários do CERBERUS");

	//comment previous 'selecionando a area funciona do usuario de suporte a chamados
	$sqlAreaFuncional = "select area_funcional_id from usuario where usuario_id = '". $_SESSION["usuario_id"]."'";


	$rs = $pdo->query($sqlAreaFuncional)->fetchAll()[0];

	
	$area_funcional_id = $rs["area_funcional_id"];
//comment previous 'mapenado as areas com as areas de chamado
	$area_chamado_id = "";
	switch ($area_funcional_id){
		case 2: //comment previous'geo
			$area_chamado_id = "1,2,5";
			break;
		case 28: //comment previous ' informatica
			$area_chamado_id = "1,3";
			break;
		case 16: //comment previous ' GED
			$area_chamado_id = "1,4";
			break;
		case 38: //comment previous ' Telecomunicação
			$area_chamado_id = "1,6";
			break;
		default:
			$area_chamado_id = "0";
			
	}
//comment previous 'exceção para o coordenador do sistema de chamados
	$id_coordenador = "0049";

		if($_SESSION["usuario_id"]==$id_coordenador){
			$area_chamado_id = "1,2,3,4,5";
		}
		
 //teste
		if($_SESSION["usuario_id"]=="2160"){
			$area_chamado_id = "1,2,3,4,5";
		}
		
		
 //ending teste
		//echo "teste" . $area_funcional_id;
		function nao_permitido_visualizar(){
?>
			<table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
                    <tr class="lista">
                       <td class="titulo_lista" colspan="10" style="font-size:14px;padding:3px;">Você n&atilde;o possui privilégio de acesso.</td>
                   </tr>
				   <tr class="lista">
				       <td colspan="10" class="lista" style="text-align:center;"><input type="button" name="voltar" class="btAzul" onClick="javascript: history.back();" value="Voltar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					   </td>
                   </tr>
			</table>
<?php		}


?>
&Uacute;ltima Atualiza&ccedil;&atilde;o: <script type="text/javascript">
agora = new Date();
hora = agora.getHours()+":"+agora.getMinutes()+":"+agora.getSeconds()
document.write(hora);</script>  
		<br/><br/>
<span onCLick="url2()" class="button_v_c" style="width:100%;text-align:right; padding-right:30px;">Visualizar <b>todos</b> os chamados abertos</span>
       <!--retirando botao criar chamado automatico -->
	   <!-- <input class="botao" type="button" value="Criar chamado do &uacute;ltimo fluxo" onClick="auto_criar_chamado()"/> -->

<script>

function url2() {
  location.href = "visualizar_chamados.php?visualizar_todas_areas=sim";
}		

</script>	

		<br/><br/><br/>
		<hr width="96%" size="1" class="azul"><br/>
<?php
		if ($area_chamado_id==0){
			nao_permitido_visualizar();
		}
		
		//comment previous 'gerando a tabela de chamados por area
		$sqlAreaChamado = "select nome_area_chamado, area_chamado_id from area_chamado where area_chamado_id in ($area_chamado_id)";

		$rs = $pdo->query($sqlAreaChamado)->fetchAll(); 
		
		
?>
			
<?php	

		foreach($rs as $item){// comment previous eof 
			
?>
                <table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
                    <tr class="lista">
                        <td class="titulo_lista" colspan="10" style="font-size:14px;padding:3px;"><?= $item["nome_area_chamado"];?></td>
                    </tr>
                    <tr class="lista">
                    	<td class="titulo_lista" width="44%">N&uacute;mero</td>
                        <td class="titulo_lista" width="44%">Problema</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Prioridade</td>                        
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Usu&aacute;rio</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Data</td>                        
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Ramal</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Email</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Atendente</td>                  
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Status</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Atender</td>
                    </tr>
<?php
                         //$sqlChamados = "select top 300 c.chamado_id,sc.status_chamado_id, c.numero_chamado, c.problema_chamado,p.nome_prioridade_chamado, 
						 //uc.nome as nome_usuario_problema, sc.data_hora, uc.ramal, uc.email,usc.nome, stc.nome_status_chamado from status_chamado as stc,
						 //solucao_chamado as sc left outer join usuario as usc on sc.usuario_id = usc.usuario_id,  usuario as uc left outer join chamado as c
						// on uc.usuario_id = c.usuario_id_problema, prioridade_chamado as p, area_chamado as ac 
						 //where c.chamado_id = sc.chamado_id and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id 
						// and sc.fim_da_fila=1 and p.prioridade_chamado_id = c.prioridade_chamado_id and c.area_chamado_id = '". $rs['area_chamado_id'] ."' 
						// and sc.status_chamado_id in(3,4) order by sc.data_hora desc";
						 //echo $sqlChamados;
						 
						  $sqlChamados = "select top 300 c.chamado_id,sc.status_chamado_id, c.numero_chamado, c.problema_chamado,p.nome_prioridade_chamado, 
						 uc.nome as nome_usuario_problema, sc.data_hora, uc.ramal, uc.email,usc.nome, stc.nome_status_chamado from status_chamado as stc,
						 solucao_chamado as sc left outer join usuario as usc on sc.usuario_id = usc.usuario_id,  usuario as uc left outer join chamado as c
						 on uc.usuario_id = c.usuario_id_problema, prioridade_chamado as p, area_chamado as ac 
						 where c.chamado_id = sc.chamado_id and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id 
						 and sc.fim_da_fila=1 and p.prioridade_chamado_id = c.prioridade_chamado_id and c.area_chamado_id in (". $area_chamado_id .") 
						 and sc.status_chamado_id in(3,4) order by sc.data_hora desc";
						 

						 //comment previous	'sqlChamados = "select c.chamado_id,sc.status_chamado_id,
						//c.numero_chamado, c.problema_chamado,p.nome_prioridade_chamado, uc.nome as 
						//nome_usuario_problema, sc.data_hora, uc.ramal, uc.email,usc.nome, stc.nome_status_chamado 
						//from status_chamado as stc, prioridade_chamado as p, usuario as usc, usuario as uc, chamado as c,
						//area_chamado as ac, solucao_chamado as sc where c.chamado_id = sc.chamado_id and uc.usuario_id =* 
						//c.usuario_id_problema and usc.usuario_id =* sc.usuario_id and c.area_chamado_id = ac.area_chamado_id 
						//and stc.status_chamado_id = sc.status_chamado_id and sc.fim_da_fila=1 and p.prioridade_chamado_id = c.prioridade_chamado_id 
						//and c.area_chamado_id = "&rs("area_chamado_id")&" and sc.status_chamado_id in(3,4) order by sc.data_hora desc"

						$rs1= $pdo->query($sqlChamados)->fetchAll();

						if(count($rs1)==0){
			 
					 //comment previous eof
?>
							<tr class="lista"><td class="lista" style="font-size:12px" colspan="10" align="center">Nenhum chamado em aberto.</td></tr>
<?php					}//ending if
							
						else{
							$qtd = 0;
							foreach($rs1 as $item1){
							//comment previous (not rs1.eof) 
							$qtd = $qtd+1;
?>
							
							<tr class="lista">
								<td <?php if ($item1["status_chamado_id"]==1)
									{ print("style=color:#C00; font-weight:bold padding-left:3px;"); }?>
										class="lista" style="font-size:12px" height="30">
											<a href="atender_chamado.php?chamado_id= <?= $item1["chamado_id"]?>"> <?= $item1["numero_chamado"]."\n" ?>
											</a>
								</td>
								<td <?php if ($item1["status_chamado_id"]==1)
									{ print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?>
										class="lista" style="font-size:12px" height="30">
											<a href='atender_chamado.php?chamado_id=<?= $item1["chamado_id"]?>'>
												<?= $item1["problema_chamado"]. "\n"?>
											</a></td>
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["nome_prioridade_chamado"]; ?></td>
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["nome_usuario_problema"]; ?></td>
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["data_hora"]; ?></td>                                  
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["ramal"]; ?></td> 
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["email"]; ?></td> 
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["nome"]; ?></td> 
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'padding-left:3px;"); }?> class="lista" style="font-size:12px" align="center"><?= $item1["nome_status_chamado"]; ?></td>
								<td <?php if ($item1["status_chamado_id"]==1) { print("style='color:#C00; font-weight:bold'"); } ?>
									class="lista" style="font-size:12px;padding-left:3px;" align="center">
										<?= "<a href='atender_chamado.php?chamado_id=" . $item1["chamado_id"]."'>Visualizar/Atender</a>"; ?></td>                                    
							
								
							</tr>
<?php
							} //ending foreach else
						}//ending else
?>
							<tr class="lista">
				            	<td colspan="10" class="titulo_lista" style="text-align:right;">Total de chamados do setor <?= $item["nome_area_chamado"] . " é \t " . $qtd;?>
                				</td>
                             </tr>                            
                             <tr class="lista">
				            	<td colspan="10" class="lista" style="text-align:center;"><input type="button" name="voltar" class="btAzul" onClick="javascript: history.back();" value="Voltar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                				</td>
                             </tr>
                </table>
                            <br/><br/>
                  
		<? }?>
</body>
</html>
<!--#include file="../conf/desconecta_banco.asp"-->
