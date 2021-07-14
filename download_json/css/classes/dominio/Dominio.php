<?php

	require_once "C:/Inetpub/wwwroot/SISLIA/classes/repositorio/RepositorioPDO.php";

	class Dominio {
		
		const NAO_ALTERAR = "null_";
		
		protected $repositorio;
		
		public function __construct($pdo = null) {
			try {
				$this->repositorio = new RepositorioPDO($pdo);
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		public function isAtualizaCampo($campo) {
			return $campo != "null_";
		}
		
	}

?>