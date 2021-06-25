<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process in page default 'index'
?>

<?php

	//Response.Charset="ISO-8859-1"
	//response.ContentType = "text/html" 
	
	$area_func_id = get_area_funcional_id_usuario($_SESSION["usuario_id"]);
	$nome = utf8_decode(safepost("nome"));
	$resposta = "Usuário não tem permissão para executar essa ação.";

																					//comment previous   '	sql = "SELECT TOP 1 cast(usuario_id as int) as id  "&_
																					//'		"FROM usuario "&_
																					//'		"ORDER BY usuario_id DESC"
		
	$sql = "SELECT 	(SELECT TOP 1 cast(usuario_id as int) as id 
			FROM usuario  
			ORDER BY usuario_id DESC) as id,
				(SELECT TOP 1 cast(usuario_id as int) as id
				FROM usuario_pendente 
					ORDER BY usuario_id DESC) as pendente_id";
	
		
	$rs = $pdo->query($sql)->fetchAll()[0];
	$maior = $rs["id"];

	if ($maior < $rs["pendente_id"]) {
		$maior = $rs["pendente_id"];
	}

	$id = "0000" . ($maior + 1);
	$id = substr($id,-4);																//code previous right(id, 4)
	
	if ($area_func_id == "28") {
		if (trim(nome) = "") {
			$resposta = "Nome inválido.";
		}else{
			$sql = "insert into Usuario (usuario_id, area_id, login, nome, excluido, contador_parecer, area_funcional_id)
				values ('" . $id  ."', '0005', '" . $id . "', '". $nome . "', 0, 0, 27)";
			$rs = $pdo->query($sql);
			$resposta = "Usuário cadastrado com sucesso.";
		}
	}

?>
{"resposta":"<?= $resposta; ?>"}
