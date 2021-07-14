<?php

require_once "C:/Inetpub/wwwroot/SISLIA/classes/repositorio/Conexao.php";
require_once "C:/Inetpub/wwwroot/SISLIA/classes/repositorio/ConexaoPDO.php";
require_once "C:/Inetpub/wwwroot/SISLIA/classes/repositorio/Repositorio.php";

class RepositorioPDO implements Repositorio {
	
	private $conexao;
	
	function __construct($pdo = null) {
		$conex = new ConexaoPDO($pdo);
		$this->conexao = $conex->getConexao();
	}
	
	public function buscar($consulta) {}
	
	public function buscar_stm($consulta) {
		
		try {
			$query = $this->conexao->query($consulta);
			if (!is_null($this->conexao->errorCode()) && $this->conexao->errorCode() != '00000') {
				$err = $this->conexao->errorInfo();
				throw new Exception($err[2]);
			}
			return $query->fetch();
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function buscar_arr($consulta) {
	
		try {
			$conex = $this->conexao;
			$query = $conex->query($consulta);
			if (!is_null($this->conexao->errorCode()) && $this->conexao->errorCode() != '00000') {
				$err = $this->conexao->errorInfo();
				throw new Exception($err[2]);
			}
			return $query->fetchAll();
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function executar($consulta) {
	
		try {
			$exec = $this->conexao->exec($consulta);
			if (!is_null($this->conexao->errorCode()) && $this->conexao->errorCode() != '00000') {
				$err =$this->conexao->errorInfo();
				throw new Exception($err[2]);
			}
			return $exec;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function iniciarTransacao() {
		$this->conexao->beginTransaction();
	}
	
	public function efetuarTransacao() {
		$this->conexao->commit();
	}
	
	public function desfazerTransacao() {
		$this->conexao->rollBack();
	}
	
	
	
	public function montarSQLInsert($dominio, $campos, $valores) {
		return "INSERT INTO $dominio (". implode(", ", $campos) .") VALUES (". implode(", ", $valores) .")";
	}
	
	public function inserir($dominio, $campos, $valores) {
		
		$sql = $this->montarSQLInsert($dominio, $campos, $valores);
		try {
			$this->executar($sql);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
}


?>