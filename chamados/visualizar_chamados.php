<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process construct menu 'smoothmenu1'
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
	cursor: pointer!important;

}
.t-os-chamados{
	display:none;
}
</style>
<script>
/*	opcao desabilita - a funcao criar chamado automatico é antiga e esta em desuso
	function auto_criar_chamado_mesmo(){
		$("#dialog_chamado_automatico").dialog('close');
		$.ajax({
			url:"receivers/,"//recebe_criar_chamado_automatico_geo.php",
			type: "post",
			data: {
				'executar': 'sim',
				'qtd_horas':$("#dialog_chamado_automatico #auto_chamado_horas").val()
			},
			success: function( data ) {
				alert('Seu chamado foi criado e encerrado para o processo '+data+'.');
			}
	   });
	}
	function auto_criar_chamado(){
		$.ajax({
			   url:"receivers/,"//recebe_criar_chamado_automatico_geo.php",
				type: "post",
				data: {
					'executar': 'nao'
				},
				success: function( data ) {
					$("#dialog_chamado_automatico .texto_confirmacao").html("Deseja mesmo criar um chamado autom&aacute;tico para o processo "+data+"?");
					$("#dialog_chamado_automatico").dialog('open');
				}
			   });
	}

	$(function(){
		$(".botao").button();
		//$("#dialog_chamado_automatico").dialog({autoOpen:false});
		
		//carregar_aviso();
		parent.aviso_quantidade();
	 });
	 
	 
		function runEffect() {
			// get effect type from
			
		};
	
*/
	
	function aviso_quantidade() {
		
		$.ajax({
			url:"../avisos/aviso_quantidade_json.php",
			type: "get",
			dataType: "json",
			success: function( data ) {
				var aviso_qt = $('#aviso_qt');
				$(aviso_qt).text(data.quantidade);
				realizarEfeito($('#aviso_widget'), 'bounce', 200, {}, function () {
					realizarEfeito($('#aviso_qt'), 'bounce', 200, {}, function (){
						realizarEfeito($('#aviso_widget'), 'bounce', 200, {});
					});
				});
			}
		});
	}
	
	function realizarEfeito(elemento, efeito, tempo, opcoes, callback) {
		$( elemento ).effect( efeito, opcoes, tempo, callback);
	}
	 
</script>
</head>

<body>




<?php
titulopagina( "Visualizar Chamados","Visualizador de chamados feitos pelos usuários do CERBERUS");

																																					//comment previous selecionando a area funciona do usuario de suporte a chamados
$sqlArea = "select area_id,area_funcional_id from usuario where usuario_id = '".$_SESSION["usuario_id"]."'";

																																			//comment previous retired set of ASP (Search function set in ASP)
$rs = $pdo->query($sqlArea)->fetchAll()[0];

$area_id_funcional = $rs["area_id"]; //$rs[0]																																				//comment previous get field highlights
$area_id = $rs["area_id"]; //$rs[0]


																																	//comment previous -> mapenado as areas com as areas de chamado

$area_chamado_id = "";
switch ($area_id){
	
	case "0125": //coment previous (ids)
		$area_chamado_id = "1,2,3,4,8";
		break;
	case "0073": //coment previous (geo)
		$area_chamado_id = "1,5";
		break;
	case "0095": //coment previous (informatica)
		$area_chamado_id = "1,3,6,8";
		break;
	case "0073": //coment previous (GED)
		$area_chamado_id = "1,4";
		break;
	case "0099": //coment previous (tele)
		$area_chamado_id = "1,6";
		break;
	default:
		$area_chamado_id = "";
}
																																		//comment previous exceção para o coordenador do sistema de chamados
$id_coordenador = "0049";

if($_SESSION["usuario_id"] == $id_coordenador){
	$area_chamado_id = "1,2,3,4,5";
}
//exceção para usuario -- conversao
if($_SESSION["usuario_id"] == "2160"){
	$area_chamado_id = "1,2,3,4,5,6,8";
}
function nao_permitido_visualizar(){
?>
			<table class="lista" border="0" width="50%" cellpadding="1" cellspacing="0" align="center">
                    <tr class="lista">
                       <td class="titulo_lista" colspan="10" style="font-size:14px;padding:3px; text-align:center;">Voc&ecirc; n&atilde;o possui privil&eacute;gio de acesso.</td>
                   </tr>
				 
			</table>
<?php		}


$visualizar_todas_areas = safeget("visualizar_todas_areas");
?>



&Uacute;ltima Atualiza&ccedil;&atilde;o: 
<script type="text/javascript">
agora = new Date();
hora = agora.getHours()+":"+agora.getMinutes()+":"+agora.getSeconds();
document.write(hora);
</script>  
<br/><br/>
<?php
if ($area_chamado_id == ""){
	echo nao_permitido_visualizar();
}
else{?>
<h3 class="t-os-chamados" style=" font-size:14px; margin-left:40px; ">
	<span style="background-color: rgb(232, 244, 246); color:rgb(116,146,184)">
	Todos os chamados abertos
	</span>
</h3>
	<span onCLick="url1()" class="button_v_c" style="width:100%;text-align:right; padding-right:30px;">Visualizar Chamados Encerrados</span>
	<span onCLick="url2()" class="button_v_c remove-button" style="width:100%;text-align:right; padding-right:30px;">Visualizar <b>todos</b> os chamados abertos</span>
       <!--retirando botao criar chamado automatico -->
	   <!-- <input class="botao" type="button" value="Criar chamado do &uacute;ltimo fluxo" onClick="auto_criar_chamado()"/> -->

<script>
function url1() {
  location.href = "visualizar_chamados_encerrados_atendente.php";//href="visualizar_chamados_encerrados_atendente.php"
}
function url2() {
  location.href = "visualizar_chamados.php?visualizar_todas_areas=sim";
  $(".t-os-chamados").css("display":"block");
}
</script>

<br/><br/><br/><hr width="100%" size="1" class="azul"><br/>

<?php }?>       


<?php
																																					//comment previous gerando a tabela de chamados por area
		if ($area_chamado_id != "") {
		$sqlAreaChamado = "select nome_area_chamado, area_chamado_id from area_chamado where area_chamado_id in ($area_chamado_id)  or ('$visualizar_todas_areas' = 'sim')";

		
		$rs = $pdo->query($sqlAreaChamado)->fetchAll();

		
			foreach($rs as $item){ 
			
?>
                <table class="lista" border="0" width="96%" cellpadding="1" cellspacing="0" align="center">
                    <tr class="lista">
                        <td class="titulo_lista" colspan="10" style="font-size:14px;padding:3px;"><?= $item['nome_area_chamado']?></td>
                    </tr>
                    <tr class="lista">
                        <td class="titulo_lista" width="7%">N&uacute;mero</td>
                        <td class="titulo_lista" width="37%">Problema</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Prioridade</td>                        
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Usu&aacute;rio</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Data</td>                        
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Setor / Ramal</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Email</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Atendente</td>                  
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Status</td>
                        <td class="titulo_lista" align="center" width="7%" style="padding-left:3px;">Atender</td>
                    </tr>
<?php	
							$sqlChamados = "select c.chamado_id,sc.status_chamado_id, c.numero_chamado, c.problema_chamado,p.nome_prioridade_chamado, uc.nome as nome_usuario_problema, ac2.sigla,
							sc.data_hora, uc.ramal, uc.email, usc.nome, stc.nome_status_chamado 
								from status_chamado as stc, solucao_chamado as sc 
							left outer join usuario as usc on sc.usuario_id = usc.usuario_id,
								usuario as uc 
							left outer join chamado as c on uc.usuario_id = c.usuario_id_problema, 
								prioridade_chamado as p,
							area_chamado as ac, area  as ac2 
								where c.chamado_id = sc.chamado_id and c.area_chamado_id = ac.area_chamado_id 
								and stc.status_chamado_id = sc.status_chamado_id and sc.fim_da_fila=1 
								and p.prioridade_chamado_id = c.prioridade_chamado_id 
								and c.area_chamado_id = '{$item['area_chamado_id']}'
								and uc.area_id = ac2.area_id 
								and sc.status_chamado_id not in(3,4) 
							order by sc.status_chamado_id,sc.data_hora";

					//comment previous 'sqlChamados = "select c.chamado_id,sc.status_chamado_id, c.numero_chamado, c.problema_chamado,p.nome_prioridade_chamado, uc.nome as nome_usuario_problema, sc.data_hora, uc.ramal, uc.email,usc.nome, stc.nome_status_chamado from status_chamado as stc, prioridade_chamado as p, usuario as usc, usuario as uc, chamado as c, area_chamado as ac, solucao_chamado as sc where c.chamado_id = sc.chamado_id and uc.usuario_id =* c.usuario_id_problema and usc.usuario_id =* sc.usuario_id and c.area_chamado_id = ac.area_chamado_id and stc.status_chamado_id = sc.status_chamado_id and sc.fim_da_fila=1 and p.prioridade_chamado_id = c.prioridade_chamado_id and c.area_chamado_id = "&rs("area_chamado_id")&" and sc.status_chamado_id not in(3,4) order by sc.status_chamado_id,sc.data_hora"
					//echo $sqlChamados; //debug
					$rs1= $pdo->query($sqlChamados)->fetchAll();

							if(count($rs1)==0){
?>
								<tr class="lista"><td class="lista" style="font-size:12px" colspan="10" align="center">Nenhum chamado em aberto.</td></tr>
<?php
							}else{
								foreach($rs1 as $new_item){
																																	//comment previous here lies Reponse.flush();
								flush();
								?>
									<tr class="lista">
                                    	<td class="lista" 
											style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { echo("color:#C00; font-weight:bold;padding-left:3px;"); }
											?>" height="30">
												<a href="atender_chamado.php?chamado_id=<?= $new_item['chamado_id']?>">
													<?= $new_item['numero_chamado']. "\n"?>
												</a></td>
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { echo("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" height="30"><a href="atender_chamado.php?chamado_id=<?= $new_item['chamado_id']?>"><?= $new_item['problema_chamado']. "\n"?></a></td>
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold;padding-left:3px;") ;} ?>" align="center"><?= $new_item['nome_prioridade_chamado']?></td>
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" align="center"><?= $new_item['nome_usuario_problema']?></td>
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" align="center"><?= $new_item['data_hora']?></td>                                  
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" align="center"><?= $new_item['sigla']?> <br/> <?= $new_item['ramal']?></td> 
										<td class="lista" 
											style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { echo("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" 
												align="center"><?= $new_item['email']?>
										</td> 
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" align="center"><?= $new_item['nome']?>&nbsp;</td> 
										<td class="lista" style="font-size:12px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold;padding-left:3px;"); } ?>" align="center"><?= $new_item['nome_status_chamado']?></td>
										<td class="lista" style="font-size:12px;padding-left:3px;<?php if ($new_item['status_chamado_id']==1) { print("color:#C00; font-weight:bold") ;}?>" align="center"><?= "<a href='atender_chamado.php?chamado_id=" . $new_item['chamado_id']."'>Atender</a>"?></td>                                    
									</tr>
								
					<?php
								}
								
								
																																					//ending second else
							}
?>
                </table>
                            <br/><br/>
<?php				
		 } 																																		// ending foreach_1 
		}																																		// ending if (area_chamado_id)
			?>                    

																																						<!--trecho abaixo excluído-->
<!--<div id="dialog_chamado_automatico" title="Criar chamado autom&aacute;tico">
	<div class="ui-state-alert texto_confirmacao">
    
    </div>
    <br/>
    <label for="auto_chamado_horas">Horas trabalhadas</label>
    <input type="text" id="auto_chamado_horas" /><br/>
    <input class="botao" type="button" onClick="auto_criar_chamado_mesmo()" value="Enviar"/>
	-->
</div> 
</body>
</html>
<!--#include file="../conf/desconecta_banco.asp"-->
