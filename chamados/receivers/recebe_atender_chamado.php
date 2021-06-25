<!--#include file="../../conf/conecta_banco.asp"-->
<?php
	require_once "../../conf/funcoes.php";
	//menu_id '6.22' in process in page default 'atender_chamados.php'
?>

<?php
$chamado_id = safepost("chamado_id");
$area_chamado_id = safepost("area_chamado_id");
$prioridade_chamado_id = safepost("prioridade_chamado_id");
$status_chamado_id = safepost("status_chamado_id");
$descricao_solucao_chamado = safepost("descricao_solucao_chamado");
$inserir_arqivo = safepost("inserir_arqivo");
$usr_atendente_id = safepost("usr_atendente_id");
$qtd_horas = str_replace(",", ".",safepost("qtd_horas")); 											//replace(safepost("qtd_horas"), ",", ".");  //code previous replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")
$enviarcomorecado = safepost("enviarcomorecado");


if ($status_chamado_id == 3) {    						//comment previous ' chamado encerrado = 3
	$servico_chamado_id = safepost("descricao_servico");
	$update_servico_chamado_id = ", servico_chamado_id =" . $servico_chamado_id;
}else{
	$update_servico_chamado_id = "";
}

if ($enviarcomorecado == "sim") {
	$quemvaimandar = "select top 1 usuario_id_problema from chamado where chamado_id='". $chamado_id ."'";
	$rs = $pdo->query($quemvaimandar)->fetchAll()[0];
	$quemvaimandar = $rs["usuario_id_problema"];
																						//code previous server.htmlencode
	$pdo->query("insert into aviso (texto, nexibicoes, data_hora, area_funcional_id, usuario_id, criador_id) 
		values ('" . htmlentities($descricao_solucao_chamado) ."',
		1, getdate(), 0, '". $quemvaimandar ."' ,
		'" . $_SESSION["usuario_id"] . "' )");
}

															//comment previous 'zerando o fim da fila das outras solucoes

$sql="update solucao_chamado set fim_da_fila=0 where chamado_id='" . $chamado_id . "'";
$rs = $pdo->query($sql);


															//comment previous 'inserindo uma nova solucao chamado com o fim da fila 1
if ($usr_atendente_id != ""){
	$usuario_atendimento = $usr_atendente_id;
}else{
	$usuario_atendimento = $_SESSION["usuario_id"];
}
$sqlInsert = "insert into solucao_chamado(data_hora,status_chamado_id,chamado_id,fim_da_fila,descricao_solucao_chamado,usuario_id) 
	values(getDate()," . $status_chamado_id . "," . $chamado_id . ",1,'" . $descricao_solucao_chamado . "','" . $usuario_atendimento ."')";

															//comment previous 'atualizado os dados do chamado
$sqlUpdate = "update chamado set area_chamado_id=" . $area_chamado_id . ", prioridade_chamado_id = " . $prioridade_chamado_id . $update_servico_chamado_id . " where chamado_id=". $chamado_id;
$rs = $pdo->query($sqlUpdate);

															//comment previous 'Response.Write(sqlUpdate)
															//comment previous 'Response.End()
															//comment previous 'verificando o ultimo id antes de inserir
$sqlSolucao= "select max(solucao_chamado_id) as valor from solucao_chamado where chamado_id ='" . $chamado_id . "'"; //". $chamado_id;
$rs = $pdo->query($sqlSolucao)->fetchAll()[0];
$ultimo_id = $rs["valor"];

$rs = $pdo->query($sqlInsert);

															//comment previous 'pegando o id do chamado inserido
$sqlnewSolucao="select max(solucao_chamado_id) as valor from solucao_chamado where chamado_id = '" . $chamado_id . "'";
$rs = $pdo->query($sqlnewSolucao)->fetchAll()[0];
$novo_id = $rs["valor"];

$sql_problema_id="select usuario_id_problema from chamado where chamado_id = '" . $chamado_id ."'";

$rs = $pdo->query($sql_problema_id)->fetchAll()[0];
$usuario_id_problema = $rs["usuario_id_problema"];
$usuario_id = $_SESSION["usuario_id"];
															//comment previous 'colocando a quantidade de horas para a execução do chamado
if ($status_chamado_id == 3) {
	$rs = $pdo->query("update chamado set qtd_horas = " . $qtd_horas ." where chamado_id = " . $chamado_id);
	$rs = $pdo->query("insert into aviso (texto, nexibicoes, data_hora, area_funcional_id,  usuario_id, criador_id) values ('Um chamado seu foi encerrado.', 1, getdate(), 0, '" . $usuario_id_problema . "','" . $usuario_id . "')");
} 


															//comment previous 'testando a insercao do novo chamado
if ($novo_id > $ultimo_id) {
?>
 	<script language="javascript">   
		alert("Solução inserida com sucesso.")
<?php                                                                  //possível criação futura da inserção do arquivo
			if(!is_null($inserir_arqivo) && $inserir_arqivo!="") {
			print "verificar";
				$pagine_redirection="../visualizar_chamados.php";
					header('Location: '. $pagine_redirection);
	?>
				//location.href = "http://google.com.br";
			
			<?php 
			}else{ 
			?>
			location.href = "../chamados/visualizar_chamados.php";
			<?php //
			
				$pagine_redirection="../visualizar_chamados.php";
					header('Location: '. $pagine_redirection);
			}
			?>
	</script>
    <?php
}else{
	?>
 	<script language="javascript">   
		alert("Erro ao tentar inserir uma solução para chamado.")
		history.back();
	</script>
<?php
}

?>