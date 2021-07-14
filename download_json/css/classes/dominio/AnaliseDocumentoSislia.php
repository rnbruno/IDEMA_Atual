<?php

	require_once "C:/Inetpub/wwwroot/cerberus/classe/dominio/Dominio.php";

	class AnaliseDocumentoSislia extends Dominio {
		
		public $id;
		public $processoSislia;
		public $documento;
		public $anexo;
		public $usuario;
		public $area;
		public $analiseTipo;
		public $analise;
		public $data;
		public $ultimo;
		
		
		public function __construct($pdo = null) {
			parent::__construct($pdo);
		}
		
		public function listar() {
			try {
				$consulta = "SELECT top 10 * FROM analise_documento_sislia ORDER BY data DESC";
				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		public function buscarPorId($documento_id) {
			try {
				$consulta = "
					SELECT * 
					FROM analise_documento_sislia 
					WHERE documento_id = $documento_id
					ORDER BY documento_id DESC
				";
				$resposta = $this->repositorio->buscar_arr($consulta);
				return $resposta[0];
			} catch (Exception $e) {
				throw $e; 
			}
		}
		

		
		public function listarAnalise($documento_id, $anexo_id = null) {
			$sql_anexo = "";
			if (is_null($anexo_id)) {
				$sql_anexo = "AND anexo_id = $anexo_id ";
			}
			
			try {
				$consulta = "
					SELECT * 
					FROM analise_documento_sislia
					WHERE documento_id = $documento_id
						$sql_anexo
					ORDER BY a.documento_id DESC
				";

				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		
		public function listarDocumentosReprovadosPorProcesso($processo_id) {

			try {
				$consulta = "
					SELECT d.documento_id, 
						convert(text, d.nome_documento) as nome_documento, 
						isnull(d.documento_check_list_classificacao_id, 1) AS classificacao_id, 
						convert(text, link) as link,
						a.anexo_id, 
						a.nome_arquivo, 
						tjs.tipo_justificativa_sislia_id, 
						tjs.tipo_justificativa_sislia, 
						convert(text, jds.justificativa) as justificativa,
						ad.analise
					FROM analise_documento_sislia ad  
						INNER JOIN processo_sislia ps ON (ps.processo_sislia_id = ad.processo_sislia_id) 
						INNER JOIN processo p ON (ps.processo_sislia_id = p.processo_sislia_id) 
						INNER JOIN documento_check_list d ON (d.documento_id = ad.documento_id) 
						INNER JOIN anexo a ON (a.anexo_id = ad.anexo_id)
						LEFT JOIN justificativa_documento_sislia jds ON (jds.documento_id = d.documento_id AND jds.processo_sislia_id = p.processo_sislia_id)
						LEFT JOIN tipo_justificativa_sislia tjs on (jds.tipo_justificativa_sislia_id = tjs.tipo_justificativa_sislia_id) 
					WHERE p.processo_id = '$processo_id'
						AND ad.analise_tipo_id = 0
						AND a.anexo_situacao_id = 1
						AND ad.ultimo = 1
				";

				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		
		public function validar() {
			
			$retorno = array();
			$retorno["mensagens"] = array();
			$retorno["sucesso"] = true;

			
			if ($this->processoSislia == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Processo sislia não informada;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			if ($this->documento == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Documento não informado;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			if ($this->anexo == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Anexo não informado;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			if ($this->usuario == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Usuario não informado;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			if ($this->area == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Área não informado;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			if ($this->analiseTipo == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Tipo da análise não informada;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			if ($this->analise == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Análise não informada;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			return $retorno; 

		}
		
		
		public function inserir() {
		
			$ultimo = 1;
			//se a análise for uma anotação não a colocar como ultima analise feita
			if ($this->analiseTipo == 2) {
				$ultimo = 0;
			}

			$sql = "
			SET NOCOUNT ON
				DECLARE @erro int; 
			
				BEGIN TRANSACTION
			";
			
			if ($ultimo == 1) {
				$sql .= "
					UPDATE analise_documento_sislia 
					SET ultimo = 0 
					WHERE processo_sislia_id = $this->processoSislia  AND documento_id = $this->documento  AND  ultimo = 1 
				
					IF @@ERROR <> 0	BEGIN		 
						SET @erro = 1	 
					END 
				";
			}
			
			$sql .= "	
				INSERT INTO analise_documento_sislia 
				(processo_sislia_id, usuario_id, area_usuario_id, 
					documento_id, anexo_id, 
					analise_tipo_id, analise, ultimo, data) 
				VALUES ($this->processoSislia, '$this->usuario', '$this->area', 
					$this->documento, $this->anexo, 
					$this->analiseTipo, '$this->analise', $ultimo, getdate() ) 

				IF @@ERROR <> 0	BEGIN		 
					SET @erro = 1	 
				END 

				IF @erro <> 0 ROLLBACK 
				ELSE COMMIT 
				
				SELECT top 1 isnull(@erro, 0) as erro, analise_documento_sislia_id  
				FROM analise_documento_sislia 
				WHERE processo_sislia_id = $this->processoSislia
					AND usuario_id = '$this->usuario'
					AND documento_id = $this->documento 
					AND anexo_id = $this->anexo 
					AND analise_tipo_id =  $this->analiseTipo
				ORDER BY data
			";

		
			//echo $sql;
			try {
				//caso ocorra algum problema, uma exceção estoura e essa parte não é executada
				$erroArr = $this->repositorio->buscar_arr($sql);
	
				if (count($erroArr) > 0) {
					$erro = $erroArr[0]['erro'];
					$this->id = $erroArr[0]['analise_documento_sislia_id'];
					
					if ($erro == 1) {
						throw new Exception("Ocorreu erro durante o processo de cadastro da análise.");
					}
					return true;
				} else {
					throw new Exception("A análise não foi cadastrada.");
				}
			} catch (Exception $e) {
				throw $e;
			}
		}

	}

?>