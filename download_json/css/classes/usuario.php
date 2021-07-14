<?php
	class Usuario{
		
		private $id_usuario;
		private $nome;
		private $email;
		private $telefone;
		private $celular;
		private $login;
		private $senha;
		private $id_tipo_usuario;
		
		public function getId(){
			return $this->id_usuario;
		}
		
		public function getNome(){
			return $this->nome;
		}
		
		public function getEmail(){
			return $this->email;
		}
		
		public function getTelefone(){
			return $this->telefone;
		}
		
		public function getCelular(){
			return $this->celular;
		}
		
		public function getLogin(){
			return $this->login;
		}
		
		public function getSenha(){
			return $this->senha;
		}
		
		public function getTipo(){
			return $this->id_tipo_usuario;
		}
		
		
		public function setId($id_usuario){
			$this->id_usuario = $id_usuario;
		}
		
		public function setNome($nome){
			$this->nome = $nome;
		}
		
		public function setEmail($email){
			$this->email = $email;
		}
		
		public function setTelefone($telefone){
			$this->telefone = $telefone;
		}
		
		public function setCelular($celular){
			$this->celular = $celular;
		}
		
		public function setLogin($login){
			$this->login = $login;
		}
		
		public function setSenha($senha){
			$this->senha = $senha;
		}
		
		public function setTipo($id_tipo_usuario){
			$this->id_tipo_usuario = $id_tipo_usuario;
		}
		
		public function save(){
			
			//Verifica se a pessoa já existe no banco dedados
			$sql_busca = "select id_usuario from usuario where id_usuario = ".$this->id_usuario;
			$qry_busca = pg_query($sql_busca);
			
			if(pg_num_rows($qry_busca)){	//Update
				if($this->senha == ""){
					$sql = "UPDATE usuario SET id_usuario= ".$this->id_usuario.", nome= '".$this->nome."', email= '".$this->email."', telefone= '".$this->telefone."', celular= '".$this->celular."', login= '".$this->login."', id_tipo_usuario= ".$this->id_tipo_usuario." WHERE id_usuario = ".$this->id_usuario;
				}else{
					$sql = "UPDATE usuario SET id_usuario= ".$this->id_usuario.", nome= '".$this->nome."', email= '".$this->email."', telefone= '".$this->telefone."', celular= '".$this->celular."', login= '".$this->login."',  senha= md5('".$this->senha."'), id_tipo_usuario= ".$this->id_tipo_usuario." WHERE id_usuario = ".$this->id_usuario;
				}
				
			}else{	//Insert
				
				if($this->senha == ""){
					$sql = "INSERT INTO usuario (nome, email, telefone, celular, login, id_tipo_usuario) VALUES ('".$this->nome."', '".$this->email."', '".$this->telefone."', '".$this->celular."', '".$this->login."', ".$this->id_tipo_usuario.")";
				}else{
					$sql = "INSERT INTO usuario (nome, email, telefone, celular, login, senha, id_tipo_usuario) VALUES ('".$this->nome."', '".$this->email."', '".$this->telefone."', '".$this->celular."', '".$this->login."', md5('".$this->senha."'), ".$this->id_tipo_usuario.")";
				}
				
			}
			
			$qry = pg_query($sql);
			if($qry){
				
				if($this->getId() == "" || $this->getId() == "0"){
					
					//Pega o ID que acabou de ser inserido
					$sql_id = "select id_usuario from usuario where login = '".$this->getLogin()."'";
					$qry_id = pg_query($sql_id);
					$rs_id = pg_fetch_array($qry_id);
					$this->setId($rs_id['id_usuario']);
					
				}
				
				/*
				//Atualiza as permissões
				$permissoes = array();
				
				//Primeiro deleta as permissões
				$sql_del = "delete from permissao where id_usuario = ".$this->getId();
				$qry_del = pg_query($sql_del);
				
				//agora insere as permissões
				if($this->getTipo() == 1){	//Administrador
					
					//Todas as permissões
					$sql_func = "select distinct(id_funcionalidade) from funcionalidade order by id_funcionalidade";
					$qry_func = pg_query($sql_func);
					
					while($rs_func = pg_fetch_array($qry_func)){
						$permissoes[] = $rs_func['id_funcionalidade'];
					}
					
				}elseif($this->getTipo() == 2){	//Gerente
					
					$permissoes[] = 1;
					$permissoes[] = 2;
					$permissoes[] = 3;
					$permissoes[] = 4;
					
				}elseif($this->getTipo() == 3){	//Operador
						
					$permissoes[] = 1;
					$permissoes[] = 2;
					$permissoes[] = 3;
					$permissoes[] = 4;
					$permissoes[] = 5;
					$permissoes[] = 6;
				}
								
				$sql_perm = "";
				for($i = 0; $i < count($permissoes); $i++){
					$sql_perm .= "(".$this->getId().", ".$permissoes[$i]."), ";
				}
				
				if(strlen($sql_perm) > 0){
					$sql_perm = "insert into permissao (id_usuario, id_funcionalodade) values ".substr($sql_perm, 0, (strlen($sql_perm) - 2))."; ";
				}
				
				$qry_perm = pg_query($sql_perm);
				*/
				
				
				return true;
			}else{
				return false;
			}
		}
		
		public function delete(){
			
			//Primeiro deleta os logs
			$sql_del_log = "delete from log where id_usuario = ".$this->getId();
			$qry_del_log = pg_query($sql_del_log);
			
			//Segundo deleta as permissões
			$sql_del_permissao = "delete from permissao where id_usuario = ".$this->getId();
			$qry_del_permissao = pg_query($sql_del_permissao);
			
			//Terceiro deleta o usuário
			$sql_del_usuario = "delete from usuario where id_usuario = ".$this->getId();
			$qry_del_usuario = pg_query($sql_del_usuario);
			
		}
				
		public function __construct($id_usuario = 0, $nome = "", $email = "", $telefone = "", $celular = "", $login = "", $senha = "", $id_tipo_usuario = 0){
			
			//Se so preencheu o ID, vai buscar os dados no banco
			//É como se quisesse criar uma pessoa só pelo ID (login)			
			if($id_usuario != 0 && $nome == "" && $email == "" && $telefone == "" && $celular == "" && $login == "" && $senha == "" && $id_tipo_usuario == 0){
				
				$sql_usuario = "select * from usuario where id_usuario = ".$id_usuario;
				$qry_usuario = pg_query($sql_usuario);
				$rs_usuario = pg_fetch_array($qry_usuario);
				
				$this->id_usuario = $rs_usuario['id_usuario'];
				$this->nome = $rs_usuario['nome'];
				$this->email = $rs_usuario['email'];
				$this->telefone = $rs_usuario['telefone'];
				$this->celular = $rs_usuario['celular'];
				$this->login = $rs_usuario['login'];
				$this->senha = $rs_usuario['senha'];
				$this->id_tipo_usuario = $rs_usuario['id_tipo_usuario'];
				
			}else{
			
				$this->id_usuario = $id_usuario;
				$this->nome = $nome;
				$this->email = $email;
				$this->telefone = $telefone;
				$this->celular = $celular;
				$this->login = $login;
				$this->senha = $senha;
				$this->id_tipo_usuario = $id_tipo_usuario;
				
			}
			
			
		}
		
	}
?>