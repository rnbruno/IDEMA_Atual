<?php

/*
Salva arquivo enviados via formulário em disco, retornando um array com o resultado do salvamento.
Cada arquivo tem uma linha no array de retorno, com os seguites chaves:
	arquivo = representa o nome do arquivo do cliente
	arquivo_servidor = representa o nome do arquivo no servidor
	mensagem = representa mensagem relativo a tentativa de salmento do arquivo no servidor
	sucesso = representa o resutado da tentativa do salvamento do arquivo no servidor
*/
class Upload {

	public function __construct() {
	}
	
	private function extensaoPermitida($extensao, $extensoes) {
		$retorno = array();
		
		if (is_null($extensoes) || array_search($extensao, $extensoes) != false) {
			$retorno["mensagem"] = "Arquivo recebido.";
			$retorno["sucesso"] = true;
		} else {
			$retorno["mensagem"] = "Arquivo com extensão não permitida. As extensões permitidas: ". implode(", ", $extensoes);
			$retorno["sucesso"] = false;
		}
		
		return $retorno;
	}

	private function mensagem_upload($erro) {
		$retorno = array();
		if ($erro == UPLOAD_ERR_OK) {
			$retorno["mensagem"] = "Arquivo recebido.";
			$retorno["sucesso"] = true;
		} else if ($erro == UPLOAD_ERR_INI_SIZE) {
			$mensagem = "Arquivo maior do que o predefinido no servidor.";
			$retorno["sucesso"] = false;
		} else if ($erro == UPLOAD_ERR_FORM_SIZE) {
			$mensagem = "Arquivo maior do que o predefinido no formulário do cliente.";
			$retorno["sucesso"] = false;
		} else if ($erro == UPLOAD_ERR_PARTIAL) {
			$mensagem = "Upload do arquivo incompleto.";
			$retorno["sucesso"] = false;
		} else if ($erro == UPLOAD_ERR_NO_FILE) {
			$mensagem = "Upload do arquivo não recebido.";
			$retorno["sucesso"] = false;
		}
		return $retorno;
	}

	function salvar($files, $nome_formulario, $caminho, $padrao_nome_arquivo, $extensoesPermitidas) {

		$f_nome = $files[$nome_formulario]['name'];
		$f_tipo = $files[$nome_formulario]['type'];
		$f_tamanho = $files[$nome_formulario]['size'];
		$f_servidor = $files[$nome_formulario]['tmp_name'];
		$f_erro = $files[$nome_formulario]['error'];
		
		if (!is_array($f_nome)) {
			$f_nome = array($files[$nome_formulario]['name']);
			$f_tipo = array($files[$nome_formulario]['type']);
			$f_tamanho = array($files[$nome_formulario]['size']);
			$f_servidor = array($files[$nome_formulario]['tmp_name']);
			$f_erro = array($files[$nome_formulario]['error']);
			echo '[unidimencional]';
		}

		$qt = count($f_nome);
		$upload = array();
		//		$nome_miolo = $usuario_id ."_". date("d_m_Y_h_i_s");

		for ($i = 0; $i < $qt; $i++ ) {
			$m_upload = $this->mensagem_upload($f_erro[$i]);
			
			if ($m_upload["sucesso"]) {
				$pathinfo = pathinfo($f_nome[$i]);
				$extensao = $pathinfo['extension'];
	
				//verifica se a extensão do arquivo é permitida
				$m_upload = $this->extensaoPermitida($extensao, $extensoesPermitidas);
				if ($m_upload["sucesso"]) {
					
					$nome_arquivo = $i ."_". $padrao_nome_arquivo .".". $extensao;
					$m_upload["arquivo_servidor"] = $nome_arquivo;
	
					if (move_uploaded_file($f_servidor[$i] , $caminho . $nome_arquivo)) {
						$m_upload["mensagem"] = "Arquivo salvo";
						$m_upload["sucesso"] = true;
					} else {
						$m_upload["mensagem"] = "Arquivo não pode ser salvo. Entre em contato com a equipe mantedora do sistema.";
						$m_upload["sucesso"] = false;
					}
				}
			}
			$upload[] = array("arquivo" => $f_nome[$i], "arquivo_servidor" => $m_upload["arquivo_servidor"], "mensagem" => $m_upload["mensagem"], "sucesso" => $m_upload["sucesso"]);
		}
		return $upload;
	}

	function padraoNomeArquivoAnexo($usuario_id, $tipo_usuario) {
		return $usuario_id ."_". $tipo_usuario ."_". date("d_m_Y_h_i_s");
	}
}
?>