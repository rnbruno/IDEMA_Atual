<?php

interface Repositorio {
	
	public function buscar($consulta);
	public function iniciarTransacao();
	public function efetuarTransacao();
	public function desfazerTransacao();
	
}

?>