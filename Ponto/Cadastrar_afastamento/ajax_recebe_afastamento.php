<?php
	

?>

<?php require_once "../conf_ponto/funcoes.php"; 
	//
	
	$title=safeget("titulo");
	//$tt='';
	
	$tipo_afastamento= safepost('tipo_afastamento');
	$user_ponto= safepost('user_ponto');
	$date_beginning= safepost('date_beginning');
	$date_ending= safepost('date_ending');	
	$user_Cerb= safepost('user_Cerb');
	
	$tipo_afastamento=utf8_decode($tipo_afastamento);
	$data_rg=$date_beginning."-". $date_ending;	
	
	
?>
	<div>
		<p><?php /*echo
		$tipo_afastamento=utf8_decode($tipo_afastamento).
		$user_ponto.
		$date_beginning.
		$date_ending;	
		$data_rg=$date_beginning."-". $date_ending;	
		echo $data_rg;
		*/
		?>
		</p>
	</div>
	
<?php
	if($date_beginning!="" and $date_ending !=""){
		$array_date_beg = explode("/", $date_beginning);
		$array_date_end = explode("/", $date_ending);	
		
			$dia_b = $array_date_beg[0];
			$dia_e = $array_date_end[0];
		
			$mes_b = $array_date_beg[1];
			$mes_e = $array_date_end[1];
			
			$ano_b = $array_date_beg[2];
			$ano_e = $array_date_end[2];
	
//date formated	
	$data_beg_edit = $ano_b."-".$mes_b."-".$dia_b;
	$data_end_edit = $ano_e."-".$mes_e."-".$dia_e;
	
//Inserir Log
	$sql_inserir_log="
		insert into log_cadastro_afastamento
			(data_log,user,tipo_afastamento,data_rg,user_afastado)
		values
			(CURRENT_TIMESTAMP,".$user_Cerb.",".$tipo_afastamento.",'".$data_rg."',".$user_ponto.")	";
			
	//echo $sql_inserir_log; //debug
	$inserir_log=$ponto->prepare($sql_inserir_log);
	$inserir_log->execute();
	
	echo "debug ";
		if($inserir_log->rowCount()=='1'){
			echo "log registrado";
		}else{
			echo "n registrado log";
		}
	
//	
	$sql_inserir_evento_afastamento= "
		insert into evento_afastamento
			(user_domain,user_afastado,data_inicio,data_final,tipo_afastamento,starting_ending)
		values
			(".$user_Cerb.",".$user_ponto.",'".$data_beg_edit."','".$data_end_edit."',".$tipo_afastamento.",0)
		";
	
	//echo $sql_inserir_evento_afastamento; //debug
	
	$inserir_evento_afastamento = $ponto->prepare($sql_inserir_evento_afastamento);
	$inserir_evento_afastamento->execute();
	
		if($inserir_evento_afastamento->rowCount()=='1'){
			echo "evento registrado";
		}else{
			echo "evento n registrado";
		}
	
	}else{
		echo "data n registrada";
	}
	
	//$inserir_log->execute();
	

?>

