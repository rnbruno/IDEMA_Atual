<?

	
	function acesso_comum(){
		session_name("FAQ_IDEMA");
		session_start();	
	};
	acesso_comum();

	function login_user(){
		$_SESSION['usuario_id']="2160";
		session_destroy();
	};
	function exit_session(){
		session_unset();
	};
	session_unset();
	/*
	header ('Content-type: text/html; charset=ISO-8859-1');
	if (!isset($_SESSION["usuario_id"])){
		//tentando recuperar sessao por asp
		$cookie = "";
		$cookieval = "";
		//descobrindo o cookie de asp
		foreach($_COOKIE as $k => $v){
			if (preg_match('/^ASPSESS/',$k) === 1){
				$cookie = $k;
				$cookieval = $v;
			}
		}
		$opts = array(
		  'http'=>array(
			'header'=> "Accept-language: en\r\n" .
							"Cookie: $cookie=$cookieval\r\n"
		  )
		);
		$httpres = stream_context_create($opts);
		$httpstr = "http://".$_SERVER['HTTP_HOST']."/cerberus/conf/sessao_asp.asp";
		$asp_uid = file_get_contents($httpstr,false,$httpres);
		if (preg_match('/^\d{4}$/',$asp_uid) === 1){
			$_SESSION["usuario_id"] = $asp_uid;
		}else{
			?><script>
			alert('Sessão expirou!');
			window.top.location = "/default_cerberus.asp";</script><?
			//die();Sessão expirou!
			//echo("Sessão expirou! <br/>");
			//echo "Informacoes tecnicas: $cookie = $cookieval<br/>";
			//echo "httpstr: $httpstr<br/>";
			die();
		}
	}
	*/
	
	function safepost($what){
		if(isset($_POST[$what]))
			return str_replace("'", '`', $_POST[$what]);
		else
			return "";
	};
																		//mostrar diretorio
	function safeget($what){
		if(isset($_GET[$what]))
			return str_replace("'", '`', $_GET[$what]);
		else
			return "";
	};
	
	function titulopagina($tit, $txt){
	?>
		<div  id="tituloPagina" class="bgAzul" style="background:url(../imagens/bgtitulo.gif);color:#FFFFFF;height:38px;font-weight:bold" >
			<div class="titulopagina_titulo" style="float:left">
				<img src="/cerberus/imagens/ponteiro.gif" width="66" height="34" >
				<span class="branco" style="font-size:16px"><?=$tit?></span>
			</div>
			<div style="text-align:right;">
				<span class="branco"><?=$txt?></span> 
				<img src="/cerberus/imagens/print.gif" style="cursor:pointer" alt="Imprimir" class="Printer" width="37" height="33" onclick="window.print();">
			</div>
			<br style="clear:both"/>
		</div>

		<!--<div id="tituloImpressao">
			<div style="width:25%">
			<img src="/cerberus/imagens/orgao.gif" alt="" width="250" height="71">
			</div>
			<div style="width:50%;text-align:right">
				<strong class="titulo">Sistema Cerberus</strong><br><br>
				<strong class="subtitulo"><?=$tit?></strong><br>
				<strong class="subtitulo"><?=date("d/m/y")?></strong><br>
				<strong class="subtitulo"><?=$txt?></strong><br>	
			</div>
			<br style="clear:both"/>
		</div>-->

		<div id="menupopupdiv"> </div>
	<?
	}

	function footer($desenvolvedor,$dados){
		echo "teste";
	}
	function extensao_arquivo_segura_geral($ext){
		//se lembrar de mais alguma, bote aqui
		if (in_array(strtolower($ext), array('exe', 'com', 'bat', 'html', 'php', 'js', 'jsp', 'aspx', 'asp', 'ini'))){
			return false;
		}
		return true;
	}
	function extensao_pdf($ext){
		//se lembrar de mais alguma, bote aqui
		if (in_array(strtolower($ext), array('pdf'))){
			return false;
		}
		return true;
	}
	function extensao_foto($ext){
		//se lembrar de mais alguma, bote aqui
		if (in_array(strtolower($ext), array('jpg', 'jpeg', 'gif'))){
			return true;
		}
		return false;
	}
	
	function anexar_arquivos_geral($pdo, $usuario_id, $usuario_sigla, $processo_id, $nome_arquivo_post, $tipo_anexo_id, $descricao, $palavras_chave){
		if (trim($descricao) == ""){
			$descricao = "Sem descrição";
		}
		$ano = "ano".date("Y");
		$mes = "mes".date("m");
		$res = $pdo->query("select valor from constantes_banco where constante= 'pasta_arquivos_upload' ");
		$rs = $res->fetch();
		$caminho_upload = $rs['valor'];
		$pasta_arquivos_upload = $caminho_upload;
		//criando pasta do ano se n existir
		if (!file_exists($caminho_upload.$ano ) ){
			mkdir($caminho_upload.$ano);
		}
		
		//criando pasta do ano se n existir
		if (!file_exists($caminho_upload.$ano.DIRECTORY_SEPARATOR.$mes ) ){
			mkdir($caminho_upload.$ano.DIRECTORY_SEPARATOR.$mes);
		}
		
		
		$result = false;
		$processo_id_e = explode(',',$processo_id);
		if (is_array($_FILES[$nome_arquivo_post]['name'])){
			$name = $_FILES[$nome_arquivo_post]['name'];
			$error = $_FILES[$nome_arquivo_post]["error"];
			$tmp_name = $_FILES[$nome_arquivo_post]["tmp_name"];
		}else{
			$name = array($_FILES[$nome_arquivo_post]['name']);
			$error = array($_FILES[$nome_arquivo_post]["error"]);
			$tmp_name = array($_FILES[$nome_arquivo_post]["tmp_name"]);
		}
		foreach ($name as $key => $value){
			if ($error[$key] == 0){
				$pathinfo = pathinfo($name[$key]);
				$ext = $pathinfo['extension'];
				$nomedoarquivo = $key."_".$usuario_id."_".$usuario_sigla."_".date("d_m_Y_h_i_s").".".$ext; //processo + chave + usuario + sigla_usuario + datahora + extensao 
				
				if (extensao_arquivo_segura_geral($ext)){
					foreach ($processo_id_e as  $processo_id_este){
						$nome_arquivo_banco = "$ano/$mes/$nomedoarquivo";
						$sql = "set nocount on;
						declare @anx_id int;
						declare @top_id int;
						declare @topico varchar(100);
						set @topico = '$palavras_chave';
						if len(@topico) < 3
							set @topico = NULL;
						insert into anexo (tipo_anexo_id, processo_id, usuario_id, data_anexo, nome_arquivo, descricao, anexo_situacao_id, sigla_usuario, topico) values ($tipo_anexo_id, '$processo_id_este', '$usuario_id', getdate(),'$nome_arquivo_banco', '$descricao', 1, '$usuario_sigla', @topico); 
						select top 1 @anx_id = anexo_id from anexo where usuario_id = '$usuario_id' and nome_arquivo = '$nome_arquivo_banco' order by data_anexo desc;";
						
						//echo $sql;
						$res = $pdo->query($sql);
						if (!$res){
							echo $sql."<br/>";
							print_r($pdo->errorInfo());
							$result = false;
						}
					}
					move_uploaded_file($tmp_name[$key] , $caminho_upload.$ano.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$nomedoarquivo);
					$result = true;
				}else{
					$result = false;	
				}
			}else{
				$result = false;
			}
		}
		return $result;
	}
	function maskd($val, $mask){
		$maskared = '';
		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++){
			if($mask[$i] == '#'){
				if(isset($val[$k])){
					$maskared .= $val[$k++];
				}
			}else{
				if(isset($mask[$i])){
					$maskared .= $mask[$i];
				}
			}
		}
		return $maskared;
	}
	function enviarEmail($email, $assunto, $mensagem) {

		//para o envio em formato HTML
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		//endereço do remetente
		$headers .= "From: Idema Comunica <catidema-licencas@rn.gov.br>\r\n";
		$destinatario  = $email;
		
		if (mail($destinatario, $assunto, $mensagem, $headers)) {
			return true;
		}
		return false;
	}
	
	function date_add_f($data, $char, $dias=0, $meses=0, $anos=0) {
	  $array_data = explode($char, $data);
	  $dias = $dias*24*60*60;
	  $meses = $meses*30*24*60*60;
	  $anos = $anos*12*30*24*60*60;
	  if($char=="/"){
		  $timestamp = ($dias+$meses+$anos)+mktime(0, 0, 0, $array_data[1], $array_data[0], $array_data[2]);
		  $data_f = date("d".$char."m".$char."Y", $timestamp);	  
	  }else{
		  $timestamp = ($dias+$meses+$anos)+mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]);
		  $data_f = date("Y".$char."m".$char."d", $timestamp);	  
	  }
	  return $data_f;
	}
	
	function date_change_char($data,$char){
		$x =  explode(" ",$data);
		if(count($x)>1){
			if($char=="/"){
				$arr_d = explode("/",$x);
				$d = $arr_d[0];
				$m = $arr_d[1];
				$y = $arr_d[2];
				$ret = $y."-".$m."-".$d;
			
			}elseif($char=="-"){
				$arr_d = explode("-",$x);
				$y = $arr_d[0];
				$m = $arr_d[1];
				$d = $arr_d[2];
				$ret = $d."/".$m."/".$y;
				
			}
		}else{
			if($char=="/"){
				$arr_d = explode("/",$data);
				$d = $arr_d[0];
				$m = $arr_d[1];
				$y = $arr_d[2];
				$ret = $y."-".$m."-".$d;
			
			}elseif($char=="-"){
				$arr_d = explode("-",$data);
				$y = $arr_d[0];
				$m = $arr_d[1];
				$d = $arr_d[2];
				$ret = $d."/".$m."/".$y;
				
			}
		}
		return $ret;
	}
	
	function get_tempo_parado_proceso_not_sp_concluida_ounao($proc_id){
		global $pdo;
		$sql =  "select CONVERT(varchar(20),data_envio,103) as data_inicial, CONVERT(varchar(20),data_atendimento,103) as data_final, atendimento, data_envio from mensagem where atendimento = 'sim' and processo_id='$proc_id' union 	select CONVERT(varchar(20),data_envio,103) as data_inicial, CONVERT(varchar(20),data_atendimento,103) as data_final, atendimento,data_envio  from mensagem where atendimento = 'nao' and processo_id='$proc_id' order by data_envio";
		$rs = end(query($sql, NULL, $pdo));
		
		$periodosim = array();
		$periodonao = array();
		$arr_parte_sim = 0;
		$arr_parte_nao = 0;
		for($i=0;$i<count($rs);$i++){
			if (count($periodosim)<=0){
				$periodosim[0]['data_inicial'] = $rs[$i]['data_inicial'];
				if ($rs[$i]['atendimento'] == 'sim'){
					$periodosim[0]['data_inicial'] = $rs[$i]['data_inicial'];
					$periodosim[0]['data_final'] = $rs[$i]['data_final'];
				}else{
					$data_final = get_data_movimento_sp_nao_atend($proc_id, date_change_char($rs[$i]['data_final'],"/"));
					
					$periodosim[0]['data_inicial'] = $rs[$i]['data_inicial'];
					$periodosim[0]['data_final'] = $data_final;
				}
				//$arr_parte_sim = 0;
				//echo $periodosim[0]['data_inicial']."<br/>";
			}else{
				if ($rs[$i]['atendimento'] == 'sim'){
					$di_temp = $rs[$i]['data_inicial'];
					$df_temp = $rs[$i]['data_final'];
					
					if(date_change_char($di_temp,"/") < date_change_char($periodosim[$arr_parte_sim]['data_final'],"/")){
						
						if( date_change_char($df_temp,"/") > date_change_char($periodosim[$arr_parte_sim]['data_final'],"/")){
							$periodosim[$arr_parte_sim]['data_final'] = $df_temp;	
						}
					
					}else{
					
						$arr_parte_sim++;
						$periodosim[$arr_parte_sim]['data_inicial'] = $di_temp;
						$periodosim[$arr_parte_sim]['data_final'] = $df_temp;
					}
				}else{
					
					$data_final = get_data_movimento_sp_nao_atend($proc_id, date_change_char($rs[$i]['data_final'],"/"));
					$di_temp = $rs[$i]['data_inicial'];
					$df_temp = $data_final;
					
					if( date_change_char($di_temp,"/") < date_change_char($periodosim[$arr_parte_sim]['data_final'],"/")){
						
						if(date_change_char($df_temp,"/") > date_change_char($periodosim[$arr_parte_sim]['data_final'],"/")){
							$periodosim[$arr_parte_sim]['data_final'] = $df_temp;	
						}
						
					}else{
						$arr_parte_sim++;
						$periodosim[$arr_parte_sim]['data_inicial'] = $di_temp;
						$periodosim[$arr_parte_sim]['data_final'] = $df_temp;
					}
				}
			}
			
		}
		
		$tempo_total_sim = 0;
		$tempo_total_nao = 0;
		
		for ($j = 0 ;$j< count($periodosim);$j++){
			$di = date_change_char($periodosim[$j]['data_inicial'],'/');
			$df = date_change_char($periodosim[$j]['data_final'],'/');
		
			
			$di = date_create($di);
			$df = date_create($df);
		
			$interval = date_diff($di, $df);
			//	var_dump($interval);
			$tempo_total_sim = $tempo_total_sim+$interval->format('%r%a');
			$interval = "";
		}
		/*//var_dump($periodonao);
		for ($k = 0 ;$k< count($periodonao);$k++){
			$di = date_change_char($periodonao[$k]['data_inicial'],'/');
			$df = date_change_char($periodonao[$k]['data_final'],'/');
			$di = date_create($di);
			$df = date_create($df);
		
			$interval = date_diff($di, $df);
			//	var_dump($interval);
			$tempo_total_nao = $tempo_total_nao+$interval->format('%r%a');
			$interval = "";
		}*/
		
		
		return $tempo_total_sim;
	}
	function get_data_movimento_sp_nao_atend($processo_id, $data_spnot){
		global $pdo;
		$sql =  "select top 1 CONVERT(varchar(20),data_hora,103)as data_hora from movimento_processo where processo_id='".$processo_id."' and data_hora > '".$data_spnot."'";
		$rs = end(query($sql, NULL, $pdo));
		$data = "";
		if(count($rs)>0){
			$data = $rs[0]['data_hora'];	
		}else{
			if((is_null($data))||($data=="")){
				$data = date("d/m/Y");
			}
		}	
		
		return $data; 
	}
	//FAQ
	//para calcular a quantidade mostrada no campo categoria 
	function quantidade_categoria($categoria, $conn){
		$sql="select COUNT (p.pergunta_id) as valor 
			from pergunta p where p.categoria_id= ". $categoria ." and p.status_pergunta = 3" ;
		//echo $sql;
		$rs=$conn->query($sql)->fetchAll()[0];
		
		return $rs;
	}

	
	//FAQ X
	function get_data_ultimo_movimento_tempo_processo($processo_id){
		global $pdo;
		$sql3 =  "select top 1 CONVERT(varchar(20),data_hora,103) as data_hora from movimento_processo where processo_id='".$processo_id."' and status_processo_id='0001' order by data_hora";
		$rs3 = end(query($sql3, NULL, $pdo));
		$data_inicio = date_change_char($rs3[0]['data_hora'],"/");
		
		$sql =  "select top 1 CONVERT(varchar(20),data_hora,103) as data_hora from movimento_processo where processo_id='".$processo_id."' and status_processo_id='0002' order by data_hora";
		$rs = end(query($sql, NULL, $pdo));
		
		$data = $rs[0]['data_hora'];
		if((is_null($data))||($data=="")){
			$sql2 =  "select top 1 CONVERT(varchar(20),data_hora,103) as data_hora from movimento_processo where processo_id='".$processo_id."' and status_processo_id='0014' order by data_hora";
			$rs2 = end(query($sql2, NULL, $pdo));
			$data = $rs2[0]['data_hora'];
			if((is_null($data))||($data=="")){
				
				$data = date("Y-m-d");
			}else{
				$data = date_change_char($data,"/");
			}
		}else{
			$data = date_change_char($data,"/");
		}
		$di = date_create($data_inicio);
		$df = date_create($data);
	
		$interval = date_diff($di, $df);
		//	var_dump($interval);
		$tempo_total_sim = $interval->format('%r%a');		
		
		return $tempo_total_sim; 
	}
	
	function enviarEmailComRegistro($destinatario, $assunto, $mensagem) {
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		//endereço do remetente
		$headers .= "From: Idema Comunica <catidema-licencas@rn.gov.br>\r\n";
		//$assunto = "SISLIA - Reprovação na análise técnica de documento no processo ". $processo["numero"];
		//echo $empreendedor .' '. $email .'<br/>';
		if (mail($destinatario, $assunto, $mensagem, $headers)) {
		} else {
		}
	}
	
	
	//$conn = mssql_connect("localhost","user_cerberus_2002","c3rb3rus_2002");
	//$banco = mssql_select_db("cerberus_2002", $conn);
	//$odbc = odbc_connect("Cerberus","user_cerberus_2002","c3rb3rus_2002");
	//	$pdo = new PDO("cerberus","user_cerberus_2002","c3rb3rus_2002"); -- antiga conexão 24/11/2017
	
	require_once('constantes.php');
	$pdo = new PDO(DB_DSN_CERBERUS, DB_USER_CERBERUS, DB_PWD_CERBERUS);	
	$conn = new PDO(DB_DSN_ALO_IDEMA,DB_USER_CERBERUS, DB_PWD_CERBERUS);	
	
	/*try {
		$pdocbx = new PDO(DB_DSN_CARBOX, DB_USER_CARBOX, DB_PWD_CARBOX);
	} catch (Exception $e) {
		echo 'Carbox temporariamente indisponível';
	}*/


	function query($sql, $params, $p) {
		$stmts = $p->prepare($sql);
		$fail = !$stmts->execute($params);
		$pdo = NULL;
		return array('fail' => $fail, 'result' => $stmts->fetchAll(PDO::FETCH_ASSOC));
	}

	function dataBR2SQL($data) {
		if ($data) {
			$data = explode("/", $data);
			return $data[2] .'-'. $data[1] .'-'. $data[0];
		}
		return ""; 
	}
	
	function convert10To26($b10) {
		$b26 = NULL;
		$digits = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		do {
			$b26 .= $digits[$b10%26];		
			$b10 = floor($b10/26);
		} while($b10 > 0);
		return str_pad(strrev($b26), 4, 'A', STR_PAD_LEFT);
	}

	function convert26To10($b26) {
		$size = strlen($b26);
		$exp = $size - 1;
		$digits = array('A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25);
		$b10 = 0;
		for($i = 0; $i < $size; ++$i) {
			$b10 += $digits[$b26[$i]] * pow(26, $exp);
			$exp -= 1;
		}
		return $b10;
	}
	function get_processo_id_diaria($sol_id){
		global $pdo;
		$sql = "select processo_id from solicitacao_diaria where solicitacao_id = $sol_id and processo_id is not null";
		$qry = $pdo->prepare($sql);
		$qry->execute();
		$res = $qry->fetchAll();
		if(count($res)>0){
			$proc_id = $res[0]['processo_id'];
		}else{
			$proc_id = "";
		}
		return $proc_id;
	}
	
	function validaCPF($cpf) {
	 
		// Extrai somente os números
		$cpf = preg_replace( '/[^0-9]/is', '', $cpf );
		 
		// Verifica se foi informado todos os digitos corretamente
		if (strlen($cpf) != 11) {
			return false;
		}
		// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
		if (preg_match('/(\d)\1{10}/', $cpf)) {
			return false;
		}
		// Faz o calculo para validar o CPF
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf{$c} * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf{$c} != $d) {
				return false;
			}
		}
		return true;
	}
	function calcula_verificador($num) {
		$sum = 0;
		$r_num = strrev((string) $num);		
		for($i = 0; $i < strlen($r_num); ++$i)
			$sum += ($i+2)*((int) $r_num[$i]);		
		return 11 - ($sum%11);
	}
	
	
	function gera_senha(){
		$digits = array('0','1','2','3','4','5','6','7','8','9','a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$a = rand(0,35);
		$s_ret = "";
		for($i=0;$i<=5;$i++){
			$temp = rand(0,35);
			$s_ret = $s_ret.$digits[$temp];
		}
		return $s_ret;
	}
	/*
	function convert10To36($b10) {
		$b36 = NULL;
		$digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		do {
			$b36 .= $digits[$b10%36];		
			$b10 = floor($b10/36);
		} while($b10 > 0);
		return str_pad(strrev($b36), 4, '0', STR_PAD_LEFT);
	}

	function convert36To10($b36) {
		$size = strlen($b36);
		$exp = $size - 1;
		$digits = array('0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34, 'Z' => 35);
		$b10 = 0;
		for($i = 0; $i < $size; ++$i) {
			$b10 += $digits[$b36[$i]] * pow(36, $exp);
			$exp -= 1;
		}
		return $b10;
	}
	*/
	
	//Registra no banco o uso de uma funcionalidade 
	function registrar_uso_funcionalidade($nome_da_funcionalidade, $informacao) {
		global $pdo;

		$sqlstr = "
			select funcionalidade_id 
			from cerberus_gerencial..funcionalidade 
			where nome_funcionalidade like '%$nome_da_funcionalidade%'
		";
		
		$funcionalidade = $pdo->query($sqlstr)->fetchAll();
		
		if (count($funcionalidade) == 0) {
			$registrar_uso_funcionalidade = "";
		} else {
			$funcionalidade_id = $funcionalidade['funcionalidade_id'];
			$sqlstr = "
				insert into cerberus_gerencial..uso_funcionalidade1
					(data, usuario_id, informacao, funcionalidade_id, ip) 
				values 
				(getdate(), '". $_SESSION['usuario_id'] ."', ". 
					( !is_null($informacao) && $informacao != '' ? "'$informacao'"  : 'NULL' ) 
				.", '". $_SERVER['REMOTE_ADDR'] ."' )";
			
			$pdo->query($sqlstr);
		}
	}
	
	//Retorna area funcional do usuario
	function get_area_funcional_id_usuario($id_usuario,$pdo){
		$sql="SELECT area_funcional_id FROM usuario WHERE usuario_id =" . $id_usuario;
		$rs = $pdo->query($sql)->fetchAll();
		foreach ($rs as $item_area){
			$item_area_funcional = $item_area["area_funcional_id"];
		}
		return $item_area_funcional;		
	}
	function opcoes_menu_coordenador($id_usuario,$pdo){
		$sql=" select usuario_id from usuario where excluido=0 and sigla_categoria='CO'and usuario_id ='" . $id_usuario. "' or usuario_id='2160'";
		$result_coordenador = $pdo->query($sql)->fetchAll()[0];
		return $result_coordenador['usuario_id'];		
		
	}
	function criar_menu_faq($id_usuario,$pdo,$conn){
		//$result = opcoes_menu_coordenador($id_usuario,$pdo);
		$result_itens_menu = array();
		
		if(is_null($result)){ //Menu para avaliador
		$sql="   select menu_cadastro_faq_id, descricao_menu_cadastro_faq,script_menu_cadastro_faq,menu_pai_faq,menu_faq,autorizacao
					FROM menu_cadastro_faq where autorizacao IS NULL order by menu_faq ASC";
		$result_itens = $conn->query($sql)->fetchAll();
		}		
		else{ // Menu para Coordenador
			$sql="   select menu_cadastro_faq_id, descricao_menu_cadastro_faq,script_menu_cadastro_faq,menu_pai_faq,menu_faq,autorizacao
					FROM menu_cadastro_faq order by menu_faq ASC";
		$result_itens = $conn->query($sql)->fetchAll();
		}
	
			for($i=0; $i < count($result_itens);$i++){		
				$resposta = array();
				$resposta['menu_cadastro_faq_id'] = $result_itens[$i]['menu_cadastro_faq_id'];
				$resposta['descricao_menu_cadastro_faq'] = utf8_encode($result_itens[$i]['descricao_menu_cadastro_faq']);
				$resposta['script_menu_cadastro_faq'] = utf8_encode($result_itens[$i]['script_menu_cadastro_faq']);
				$resposta['menu_pai_faq'] = utf8_encode($result_itens[$i]['menu_pai_faq']);
				$resposta['menu_faq'] = utf8_encode($result_itens[$i]['menu_faq']);
				$resposta['autorizacao'] = utf8_encode($result_itens[$i]['autorizacao']);
				
				
			$result_itens_menu[] = $resposta;
			}
		//var_dump($result_itens_menu);	
		return $result_itens_menu;	
	}
	function icon($tam,$type){ //função para criar icone
		$type_svg=explode(".",$type);
		echo		"<svg class='icon' width='".$tam."' height='".$tam."'>";
		echo			"<use xlink:href='../../../bootstrap-3.0.0/bootstrap-icons-1.4.1/bootstrap-icons.svg#".$type_svg[0]."'/>";
		echo		"</svg>";
	}
?>