<?php

//require_once "C:/Inetpub/wwwroot/cerberus/conf/funcoes.php";
require_once "C:/Inetpub/wwwroot/SISLIA/classes/repositorio/Conexao.php";

class ConexaoPDO implements Conexao {

	private $conexao;
	
	function ConexaoPDO($conexao) {
		$this->conexao = $conexao;
	}
	
	function _construct($pdo = null) {
		if (is_null($pdo)) {
			try {
				//pgsql:host=localhost dbname=nome_do_banco user=jvideos10 password=password
				//$this->conexao = new PDO("cerberus","user_cerberus_2002","c3rb3rus_2002");
					
				if (!is_null($this->conexao->errorCode())) {
					$err = $this->conexao->errorInfo();
					throw new Exception($err[2]);
				}
			} catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		} else {
			$this->conexao = $pdo;
		}
	}
	
	public function getConexao() {
		return $this->conexao;
	}
}

?>