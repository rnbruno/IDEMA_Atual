<?php

	require_once "C:/Inetpub/wwwroot/cerberus/classe/dominio/Dominio.php";

	class ProcessoErroSislia extends Dominio {
		
		public $processoSislia;
		public $erroSilsia;
		
		public function __construct($pdo = null) {
			parent::__construct($pdo);
		}
		
		public function listar() {
			try {
				$consulta = "SELECT top 10 * FROM processo_erro_sislia ORDER BY data DESC";
				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		public function buscarPorProcessoSislia($processo_sislia_id) {
			try {
				$consulta = "
					SELECT * 
					FROM processo_erro_sislia pes
						INNER JOIN erro_sislia es ON (es.erro_sislia_id = pes.erro_sislia_id)
					WHERE processo_sislia_id = $processo_sislia_id
					ORDER BY fase_sislia_id DESC
				";
				$resposta = $this->repositorio->buscar_arr($consulta);
				return $resposta[0];
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		public function possuiPendenciaImpeditiva($processo_sislia_id) {
			try {
				$consulta = "
					SELECT es.erro_sislia_id 
					FROM processo_erro_sislia pes
						INNER JOIN erro_sislia es ON (es.erro_sislia_id = pes.erro_sislia_id)
					WHERE es.tipo_erro_sislia_id = 1
						AND processo_sislia_id = $processo_sislia_id
				";
				$resposta = $this->repositorio->buscar_arr($consulta);
				if (count($resposta) > 0) {
					return true;
				}
				return false;
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
			
			if ($this->erroSilsia == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Erro não informado;", "sucesso" => false));
				$retorno["sucesso"] = false;
			}
			
			return $retorno; 

		}
		
		
		public function removerPendencia($processo_sislia, $erro) {
			if (is_array($erro)) {
				$separador = "";
				$temp = "";
				foreach ($erro as $err) {
					$temp .= $separador . $err;
					$separador = ", ";
				}
				$where_erro = "erro_sislia_id IN ($temp)";
			} else {
				$where_erro = "erro_sislia_id = $erro";
			}
			
			$sql = "
				DELETE FROM processo_erro_sislia 
				WHERE processo_sislia_id = $processo_sislia
					AND $where_erro";
			
			try {
				//caso ocorra algum problema, uma exceção estoura e essa parte não é executada
				return $this->repositorio->executar($sql);
			} catch (Exception $e) {
				throw $e;
			}
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