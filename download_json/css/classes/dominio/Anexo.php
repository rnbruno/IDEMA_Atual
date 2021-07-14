<?php

require_once "C:/Inetpub/wwwroot/SISLIA/classes/dominio/Dominio.php";

class Anexo extends Dominio {

	public $id;
	public $tipoAnexo;
	public $processo;
	public $usuario;
	public $data;
	public $nome;
	public $descricao;
	public $anexoSituacao;
	public $siglaUsuario;
	public $relatorio;
	public $anualidade;
	public $documento;
	public $processoSislia;
	public $boletoSislia;
	
	
	public function __construct($pdo = null) {
		parent::__construct($pdo);
	}
	
	
	public function buscarPorDocumento($documento, $processoSislia) {
		try {
			$consulta = "SELECT * FROM anexo WHERE documento_id = $documento AND processo_sislia_id = $processoSislia";
			return $this->repositorio->buscar_arr($consulta);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	public function descobrirId() {
		try {
			$consulta = "
				SELECT anexo_id 
				FROM anexo 
				WHERE documento_id = $this->documento 
					AND processo_sislia_id = $this->processoSislia
					AND anexo_situacao_id = '$this->anexoSituacao'
					AND nome_arquivo = '$this->nome'
			";
			$anexo = $this->repositorio->buscar_arr($consulta);
			return $anexo[0]["anexo_id"];
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	public function inserirAnexoSislia() {
		$campos = array (
				"tipo_anexo_id",
				"usuario_id",
				"nome_arquivo",
				"descricao",
				"anexo_situacao_id",
				"sigla_usuario",
				"documento_id",
				"processo_sislia_id",
		);
		$valores = array (
				"'$this->tipoAnexo'",
				"'$this->usuario'",
				"'$this->nome'",
				"'$this->descricao'",
				"$this->anexoSituacao",
				"'$this->siglaUsuario'",
				"$this->documento",
				"$this->processoSislia"
		);
		
		try {
			$inseriu = $this->repositorio->inserir ( "anexo", $campos, $valores );
			// caso ocorra algum problema, uma exceção estoura e essa parte não é executada
			$this->id = $this->descobrirId();
			return $inseriu;
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	
	public function removerAnexoDocumento() {
		
		try {
			$atualizacao = "
				UPDATE Anexo
				SET anexo_situacao_id = 2
				WHERE anexo_id = $this->id 
					AND processo_sislia_id = $this->processoSislia
					AND usuario_id = '$this->usuario'
					AND sigla_usuario = '$this->siglaUsuario'
					AND tipo_anexo_id = $this->tipoAnexo
			";

			return $this->repositorio->executar($atualizacao);
		} catch (Exception $e) {
			throw $e;
		}
		
	}
}

?>