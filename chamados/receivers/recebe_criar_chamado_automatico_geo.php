<?php
	require_once "../../conf/funcoes.php";
	//menu_id '6.22' in process constructed in directory receivers in chamados
?>


<?php
$usuario_id_problema = $_SESSION["usuario_id"];
$executar = safepost("executar");
$usuario_id = $usuario_id_problema;
$qtd_horas = safepost("qtd_horas");

$rs = $pdo->query("select area_id from usuario where area_id = '0125' and usuario_id = '" . $usuario_id ."'")->fetchAll();

if (count($rs)==0) {										//.eof then
	print("N&atilde;o &eacute; do GEO.");

}
$area_chamado_id = 5;

$nao_primeira_vez_chamado = 0;

															//comment previous 'Aqui a gente pega os dados do ultimo movimento_processo
															//'=====

$rs = $pdo->query("select top 1 p.processo_id, p.numero, p.descricao, mp.comentario, convert(varchar(30), mp.data_hora, 120) as 
		data_hora,  mp.movimento_processo_id from movimento_processo mp, processo p where mp.processo_id = p.processo_id and mp.usuario_inicio_id!= '" . $usuario_id ."' 
			order by mp.data_hora desc")->fetchAll()[0];

$numero_processo = $rs["numero"];
if ($executar != "sim") {
	print($numero_processo);

}


$problema_chamado = "\nSobre o processo " . $numero_processo . ": " . $rs["descricao"];
echo $problema_chamado;
$problema_chamado = $problema_chamado . "<br/>Software utilizado: ArcGIS";
$problema_chamado = str_replace("'", "&#39;",$problema_chamado);  									//code previous replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")

$problema_chamado = str_replace('"', "&#34;",$problema_chamado);
$resposta_chamado = $rs["comentario"];
$resposta_chamado = str_replace("'", "&#39;",$resposta_chamado);
$resposta_chamado = str_replace('"', "&#34;",$resposta_chamado);
echo $resposta_chamado . "<<<<<<<";
$data_fim = $rs["data_hora"];
echo var_dump($data_fim) . "<<<<<";
$movimento_processo_id_fim = $rs["movimento_processo_id"];
$prioridade_chamado_id = 1;
$processo_id = $rs["processo_id"];

																//comment previous 'Agora vamos pegar o movimento anterior para determinar a data de criacao
																//'====
$rs = $pdo->query("select convert(varchar(30), max(data_hora), 120) as data_hora from movimento_processo 
	where processo_id='" . $processo_id ."' and movimento_processo_id < '" . $movimento_processo_id_fim . "' and usuario_destino_id = '" . $usuario_id . "'")->fetchAll()[0];
echo var_dump($rs);
$data_inicio = $rs["data_hora"];


$rs = $pdo->query("select valor from parametro where parametro='CONTADOR_CHAMADO'")->fetchAll;
$numero = $rs["valor"]+1;
$numero = substr("0000" . $numero,-4);

$numero_chamado = date("Y") . "-" . $numero;

$sqlInsert = "insert into chamado (area_chamado_id, usuario_id_problema, data_hora, nao_primeira_vez_chamado, problema_chamado, prioridade_chamado_id, numero_chamado, 
	conclusao_chamado_id, nivel_satisfacao_id, observacao_chamado, qtd_horas, servico_chamado_id) 
		values('". $area_chamado_id ."', '". $usuario_id_problema ."','" . $data_inicio . "', '" . $nao_primeira_vez_chamado . "', '" . $problema_chamado . "', 
		'" . $prioridade_chamado_id . "', '" . $numero_chamado . "', 1, 1,'Gerado automaticamente.', '" . $qtd_horas . "', 5)";


																	//comment previous 'verificando o ultimo id antes de inserir
$rs = $pdo->query("select max(chamado_id) as valor from chamado where usuario_id_problema='" . $usuario_id_problema . "'")->fetchAll()[0];

$ultimo_id = $rs["valor"];
																	//comment previous 'inserindo novo registro
																	//'response.write(sqlInsert)
																	//'response.End()
$rs = $pdo->query($sqlInsert);


																	//comment previous  'pegando o id do chamado inserido
$rs = $pdo->query("select max(chamado_id) as valor from chamado where usuario_id_problema='" . $usuario_id_problema . "'")->fetchAll()[0];
echo var_dump($rs);
$novo_id = $rs["valor"];

																	//comment previous 'testando a insercao do novo chamado
if ($novo_id > $ultimo_id) {

																	//comment previous 'criando uma solucao_chado com o status aguardando atendimento
	$pdo->query("insert into solucao_chamado (data_hora,status_chamado_id,chamado_id,fim_da_fila) values('" . $data_inicio . "',1,'" . $novo_id . "',0)");
	
	$pdo->query("insert into solucao_chamado (data_hora,status_chamado_id,chamado_id,fim_da_fila, usuario_id, descricao_solucao_chamado) 
		values('" . $data_fim ."',3," . $novo_id .",1, '" . $usuario_id . "', '" . $resposta_chamado . "')");
	
	$pdo->query("UPDATE PARAMETRO SET VALOR = '" . $numero . "', data=getDate() where parametro='CONTADOR_CHAMADO'");

print($numero_processo);
}
?>