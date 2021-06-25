
<?php 
	require_once "../conf/funcoes.php";
	//JS in atender_chamados.php



	$area_func_id = get_area_funcional_id_usuario($_SESSION["usuario_id"],$pdo);
	
	$resultado = '{"id":"","nome":"Usuário sem permissão","atualiza":false}';
	
	//if (not isnull(session("login")) and session("login")<> "visitante") then
	if (!is_null($_SESSION["login"]) and $_SESSION["login"] != "visitante") {
		$nome = utf8_decode(safeget("nome"));

		$resultado="";
		$sql = "SELECT u.usuario_id, isnull(af.sigla, '') +'/'+ isnull(u.sigla_categoria, '') +' - '+ u.nome as nome, u.area_id, u.area_funcional_id 
					FROM usuario u, area_funcional af  
					WHERE u.area_funcional_id = af.area_funcional_id 
						AND u.excluido = '0'  
						AND u.usuario_id not in('0169','0487','0860') 
						AND af.area_funcional +' - '+ u.nome LIKE '%" . $nome . "%' 
					ORDER BY u.nome";

		$rs = $pdo->query($sql)->fetchAll();

		foreach($rs as $item){
									//code previous while not rs.eof
			$atualiza = "false";
			if ($area_func_id == "28" and $item["area_id"] == "0005" and $item["area_funcional_id"] == 27) {
				$atualiza = "true";
			}
			
			if ($resultado != "") {
				$resultado = $resultado . ',{"id":"' . $item["usuario_id"] . '","nome":"' . $item["nome"] . '","atualiza":' . $atualiza . '}';
			}else{
				$resultado = '{"id":"' . $item["usuario_id"] . '", "nome":"' . $item["nome"] . '", "atualiza":' . $atualiza . '}';
			}
		}
		

	}// ending if principal
	
	//response.Write("["& resultado &"]")
	print ('[' . $resultado . ']');
?>


