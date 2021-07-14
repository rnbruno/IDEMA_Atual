<?php

	require_once "C:/Inetpub/wwwroot/cerberus/classe/dominio/Dominio.php";

	class Cliente extends Dominio {
		
		public $id = "";
		public $cliente = "";
		public $telefone = "";
		public $celular = "";
		public $email = "";
		public $rg = "";
		public $cpf = "";
		public $cnpj = "";
		public $inscricaoEstadual = "";
		public $cep = "";
		public $complemento = "";
		
		public function __construct($pdo = null) {
			parent::__construct($pdo);
		}
		
		public function listar() {
			try {
				$consulta = "SELECT top 10 * FROM Cliente ORDER BY cliente_id DESC";
				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		public function buscarPorNome($cliente) {
			try {
				$consulta = "SELECT * FROM cliente WHERE cliente like '$cliente'";
				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		private function descobrirId() {
			try {
				$consulta = "SELECT * FROM cliente WHERE cnpj = '$this->cnpj' OR cpf='$this->cpf'";
				$clientes = $this->repositorio->buscar_arr($consulta);
				return $clientes[0]["cliente_id"];
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		public function existeCliente() {
			try {
				$consulta = "SELECT * 
				FROM cliente 
				WHERE rg = '$this->rg' 
					OR cpf='$this->cpf' 
					OR cnpj='$this->cnpj'";

				$cliente = $this->repositorio->buscar_arr($consulta);
				
				if (count($cliente) > 0) {
					return true;
				} else {
					return false;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
		public function validar() {
			
			$retorno = array();
			$retorno["mensagens"] = array();
			$retorno["sucesso"] = false;

			if ($this->cliente == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Pessoa física/Razão social não informada;", "sucesso" => false));
	//			$retorno["mensagem"] .= "Pessoa física/Razão social não informada;\n";
				$retorno["sucesso"] = false;
			}
			
			if ($this->rg == "" && $this->cpf == "" && $this->cnpj == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Pelo menos um desses campos deve ser informado: rg, cpf, cnpj;", "sucesso" => false));
//				$retorno["mensagem"] .= "Pelo menos um desses campos deve ser informado: rg, cpf, cnpj;\n";
				$retorno["sucesso"] = false;
			}
			
			if ($this->cep == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Cep não informado;", "sucesso" => false));
//				$retorno["mensagem"] .= "Cep não informado;\n";
				$retorno["sucesso"] = false;
			}
			
			if ($this->telefone == "" && $this->celular == "" && $this->email == "") {
				array_push($retorno["mensagens"], array("elemento" => "", "mensagem"=>"Pelo menos um desses campos deve ser informado: celular, telefone, e-mail;", "sucesso" => false));
				//$retorno["mensagem"] .= "Pelo menos um desses campos deve ser informado: celular, telefone, e-mail;\n";
				$retorno["sucesso"] = false;
			}
			 
			return $retorno;
		}
		
		
		public function inserir() {
			
			$campos = array("cliente", "rg", "cpf", "cnpj", "inscricao_estadual", 
					"complemento", "cep_id", 
					"telefone_cliente", "celular_cliente", "email_cliente");
			$valores = array("'$this->cliente'", "'$this->rg'", "'$this->cpf'", "'$this->cnpj'", "'$this->inscricaoEstadual'", 
					"'$this->complemento'", "'$this->cep'", "'$this->telefone'", "'$this->celular'", "'$this->email'");
			
			try {
				if (!existeCliente()) {
					$inseriu = $this->repositorio->inserir("cliente", $campos, $valores);
					//caso ocorra algum problema, uma exceção estoura e essa parte não é executada
					$this->id = $this->descobrirId();
					return $inseriu;
				} else {
					throw new Exception("Sistema possui Cliente com o mesmo RG, CPF ou CNPJ.");
				}
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	}

?>