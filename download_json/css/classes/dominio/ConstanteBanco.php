<?php

	require_once "C:/Inetpub/wwwroot/SISLIA/classes/dominio/Dominio.php";

	class ConstanteBanco extends Dominio {
		
		public $constante;
		public $valor;
		
		public function __construct($pdo = null) {
			parent::__construct($pdo);
		}
		
		public function listar() {
			try {
				$consulta = "SELECT top 10 * FROM constantes_banco ORDER BY constante DESC";
				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e; 
			}
		}
		
		public function buscarPorConstante($constante) {
			try {
				$consulta = "SELECT * FROM constantes_banco WHERE constante = '$constante'";
				return $this->repositorio->buscar_arr($consulta);
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		private function descobrirId() {

		}
		
		public function existeCliente() {
		}
		
		
		public function validar() {
		}
		
		
		public function inserir() {
			
		}
		
	}

?>