<!--#include file="../../conf/conecta_banco.asp"-->
<?php
	require_once "../../conf/funcoes.php";
	//menu_id '6.22' in process constructed in directory receivers in chamados
?>

<?php
$area_chamado_id = safepost("area_chamado_id");
$usr_problema= safepost("usr_log");

if ($usr_problema != "") {
	$usuario_id_problema = $usr_problema;
}else{
	$usuario_id_problema = $_SESSION["usuario_id"];
}

$nao_primeira_vez_chamado = safepost("nao_primeira_vez_chamado");

$problema_chamado = safepost("problema_chamado");
$problema_chamado = str_replace("'", "&#39;",$problema_chamado);   //code previous replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")

$problema_chamado = str_replace('"', "&#34;",$problema_chamado);

$prioridade_chamado_id = safepost("prioridade_chamado_id");

$rs = $pdo->query("select valor from parametro where parametro='CONTADOR_CHAMADO'")->fetchAll()[0];
$numero = $rs["valor"]+1;
$numero = substr("0000". $numero,-4);								//code previous right()

$numero_chamado = date("Y") . "-" . $numero;          				 //Year(now()) & "-" & numero

$sqlInsert = "insert into chamado (area_chamado_id, usuario_id_problema, data_hora, nao_primeira_vez_chamado, problema_chamado, prioridade_chamado_id, numero_chamado) 
				values('". $area_chamado_id ."', '". $usuario_id_problema ."',getDate(), '". $nao_primeira_vez_chamado . "', '". $problema_chamado ."', '" .$prioridade_chamado_id ."', '" . $numero_chamado ."')";

																		//comment previous 'verificando o ultimo id antes de inserir
$rs = $pdo->query("select max(chamado_id) as valor from chamado")->fetchAll()[0];
$ultimo_id = $rs["valor"];
																	//comment previous 'inserindo novo registro
																	//'response.write(sqlInsert)
																	//'response.End()
$rs = $pdo->query($sqlInsert);


																	//comment previous 'pegando o id do chamado inserido
$rs = $pdo->query("select max(chamado_id) as valor from chamado")->fetchAll()[0];
$novo_id = $rs["valor"];

																	//comment previous 'testando a insercao do novo chamado
if ($novo_id > $ultimo_id) {

																	//comment previous 'criando uma solucao_chado com o status aguardando atendimento
	$rs = $pdo->query("insert into solucao_chamado (data_hora,status_chamado_id,chamado_id,fim_da_fila) values(getDate(),1,'". $novo_id ."',1)");
	$rs = $pdo->query("UPDATE PARAMETRO SET VALOR = " . $numero . ", data=getDate() where parametro='CONTADOR_CHAMADO'");

?>
 	<script language="javascript">   
		alert("Chamado inserido com sucesso.")
		location.href = "../cadastro_chamado.php";
	</script>
<?php
}else{
?>
 	<script language="javascript">   
		alert("Erro ao tentar criar um novo chamado.")
		history.back();
	</script>
<?php
}

?>