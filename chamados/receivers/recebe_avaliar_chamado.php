<!--#include file="../../conf/conecta_banco.asp"-->

<?php
	require_once "../../conf/funcoes.php";
	//menu_id '6.22' in process constructed in directory receivers in chamados
?>
<?php


	//$chamado_ids = str_replace( " ", "",safepost("chamado_id_selecionado")); 								//code previous replace (texto, palavra que sai, palavra que entra) in ASP. However php str_replace($palavra_sai,"que_entra","texto")


	//$chamdos = explode(",",$chamados_ids);																//code previous split(chamado_ids, ",") ASP. In php explode("simbol_for_break",[]) 
	
	$chamdos =$_POST["chamado_id_selecionado"];
	//var_dump ($chamdos);

	$qt_modificacao = 0;
	$qt_sucesso = 0;

	
	for($k = 0;$k<count($chamdos);$k++){
																							//'atualizando a situação da qualificação do chanmado
		$sql = "UPDATE chamado SET 
			conclusao_chamado_id = " . safepost("conclusao_". $chamdos[$k]) . ", "
			. "nivel_satisfacao_id = " . safepost("satisfacao_" . $chamdos[$k]) . " "
			. "WHERE chamado_id = " . $chamdos[$k];

		$rs = $pdo->query($sql);


																							//comment previous	'verificando se realmete foi atualizado
		$sql = "SELECT conclusao_chamado_id AS valor
			FROM chamado 
			WHERE chamado_id = " . $chamdos[$k];

		$rs = $pdo->query($sql)->fetchAll()[0];
		
		$valor = $rs["valor"];
		
																							//comment previous 'testando a insercao do novo chamado
		if ($valor != "") {
			$qt_sucesso = $qt_sucesso + 1;
		}
		
		$qt_modificacao = $qt_modificacao + 1;
		
	}
	

																						//comment previous 'testando a insercao do novo chamado
	if ($qt_modificacao == $qt_sucesso) {
?>
		<script language="javascript">   
			alert("Avaliação inserida com sucesso.");
<?php
			$sql = "SELECT count(c.chamado_id) as qtd 
				FROM chamado as c, solucao_chamado as sc 
				WHERE c.chamado_id = sc.chamado_id 
				AND sc.status_chamado_id = 3 
				AND sc.fim_da_fila = 1 
				AND c.nivel_satisfacao_id is null
				AND c.usuario_id_problema = " . $_SESSION["usuario_id"]; 
				
			$rs3 = $pdo->query($sql)->fetchAll()[0];
			if($rs3["qtd"]>0) {
				$pagine_redirection = "../visualizar_chamados_encerrados_usuario.php?local=ch";
				header('Location: '. $pagine_redirection);
			}else{                             //verificar pagina principal do usuario
				$sql = "SELECT script 
						FROM PERFIL p
							INNER JOIN menu m ON (p.menu_id = m.menu_id)
						WHERE usuario_id = ". $_SESSION['usuario_id'] . "
							AND pagina_inicial = 1";
				$rs = $pdo->query($sql)->fetchAll()[0];
				$item_script = $rs["script"];
				//$item_script = "../visualizar_chamados_encerrados_usuario.php?local=login";

					header('Location: ../../'. $item_script);
				}

			
?>
		</script>
<?php
	}else{
?>
		<script language="javascript">   
			alert("Erro ao tentar inserir avaliação para chamado.");
			history.back();
		</script>
<?php
	}
?>