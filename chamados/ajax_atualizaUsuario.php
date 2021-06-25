	
<?php
	require_once "../conf/funcoes.php";
	//menu_id '6.22' in process construct in page cadastro_chamados.php

	$area_func_id = get_area_funcional_id_usuario($_SESSION["usuario_id"]);
	$nome = utf8_decode(safepost("nome"));
	$id = safepost("id");

	
	$resposta = "Usuário não tem permissão para alterar.";
	
	if ($area_func_id == "28") {
														//comment previous	if (area_func_id = "2") then	
		$id = "0000" . $id;
														//code previous $id = right($id, 4);
		$id = substr($id,-4);
		
		$sql = "SELECT usuario_id 
		FROM usuario WHERE area_id = '0005' AND  area_funcional_id = 27  AND excluido = 0 
		AND login =" .  $usuario_id ;
		
		$rs = $pdo->query($sql)->fecthAll();
		
		if(count($rs)!=0){		//code previous if (not rs.EOF) then
			$sql = "UPDATE usuario SET nome = '". $nome ."' 
				     WHERE usuario_id = " . $id ;
			$rs = $pdo->query($sql)->fetchAll();
			
			$resposta = "Usuário alterado com sucesso.";
		}else{
			$resposta = "Este usuário não pode ser alterado. Apenas os que foram inseridos pela equipe de informática.";
		}
	}//if principal

?>

{"resposta":"<?= $resposta; ?>"}
