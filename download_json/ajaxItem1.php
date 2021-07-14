<?php 

require_once "funcoes.php";  
/*
Duas tabelas foram utilizadas
	Table1 (download_menu) - colunas (download_menu_id,descricao_menu) para registrar id e a descriчуo do menu para donwloads
	Table2 (download_menu_cliente1) - Colunas (download_menu_cliente_id,download_cliente,cliente_id,data,quantidade,fim_de_fila,download_cliente_int
		para armazenar dados do cliente, data que efetuou o download, titulo do menu selecionado, a quantidade do registro em cada donwload, cliente id 
*/
$item = safepost("item");
$id = safepost("id");

$pacote['resposta']='';
$pacote['debug']='';
$pacote['title']='';


function nao_numerico($var) {
	return !is_numeric($var);
}
/*function mask_processo($processo){
	//["processo_id"]
	//["processo_id_int"]
	if(array_key_exists($processo["processo_id"]){}
	
	return !array_key_exists($processo["processo_id"]);
}
*/
$ultimo_id = $pdo->query("select max(download_cliente_int) as ultimo_id from download_menu_cliente1")->fetchAll()[0];

$ultimo_id['uid']=$ultimo_id['ultimo_id']+1;  
//ultimo id em varchar
$ultimo_id_varchar=strval($ultimo_id['uid']);

$id_count = strlen($id); //verificar cpf ou cnpj

//verificar o cliente_id cpf ou cnpj
if($id_count==11){
$sql_cliente_id = "select cliente_id_int from cliente where cpf='".$id."'";
$res_cliente_id=$pdo->query($sql_cliente_id)->fetchAll()[0];
$cliente_id = $res_cliente_id["cliente_id_int"];
}
elseif($id_count==14){
$sql_cliente_id = "select cliente_id_int from cliente where cnpj='".$id."'";
$res_cliente_id=$pdo->query($sql_cliente_id)->fetchAll()[0];
$cliente_id = $res_cliente_id["cliente_id_int"];
}



//$new_result[]=$unir_processos; //debug

		

if($item=="1"){
	
	$sql="BEGIN TRAN
		select (((processo_id*11)/7)-((responsavel_id*11)%7)) as p_id,(((responsavel_id*11)/7)-((processo_id*11)%7)) as r_id, contato_id,tipo_processo_id,familia_processo_id,grupo_tipo_processo_id,numero,descricao,porte_id,potencial_poluidor_id,devolucao,excluido
,proprietario_empreendimento,cpf_proprietario,processos_vinculados,processos_vinculados_virtual,processos_vinculados_fisicamente,codigo_antigo,processo_cepram
,responsavel_parecer_id,parecer,dnpm_numero,data_formacao,op_concluido,corresp_empreendedor,corresp_contato,corresp_empreendimento,destinatario,endereco,bairro
,municipio,uf,cep,telefone,fax,celular,cargo,email,obs_auto_infracao,status_auto,local_auto,data_auto,alex_temo,bkp_responsavel_id,apelido,taxa_licenciamento
,ID_BOLETO,requerimento_id,renovavel,nosso_numero_boleto,data_prazo_conclusao,status_mudanca_titularidade,nosso_numero_boleto1,complemento_numero
,complemento_descricao,forma_conclusao,uf_id,municipio_id,processo_sislia_id,quantidade_emprego_atual,quantidade_emprego_possivel,inexigibilidade_liberada
,complemento_autorizacao_captura,coord_X_UTM,coord_Y_UTM,coord_X_graus,coord_Y_graus,endereco_correspondencia,cep_correspondencia,taxa_licenciamento_bkp,taxa_atualizada
,sem_lp,sem_li,flag_ataque_2019,data_prazo_arquivamento_inicial,data_prazo_arquivamento_final 

	from processo where processo_id_int in(
		select processo_id_int as processos from processo_cliente where cliente_id_int =(
			select cliente_id_int from cliente where cpf='".$id."'))
		
		COMMIT";
	$result = $pdo->query($sql)->fetchAll();
	//$pacote_item1->execute();
	foreach($result as $itens){
		$new_result[]=array_filter($itens, "nao_numerico", ARRAY_FILTER_USE_KEY);

	}
		$reg=count($new_result);
		//atualizando fila para 0
		//inserindo registro de acesso log para registro de download
	$sql_update_fila= "BEGIN TRAN
		
		update download_menu_cliente1  set fim_de_fila=0 where download_cliente_int=(select max(download_cliente_int) from download_menu_cliente1 where cliente_id='".$id."' and download_menu_cliente_id='$item' )
		
		insert into download_menu_cliente1 
			(download_menu_cliente_id,download_cliente,cliente_id,data,quantidade,fim_de_fila,download_cliente_int) 
			values ('$item','".$ultimo_id_varchar."','".$id."',GETDATE(),'".$reg."',1,".$ultimo_id['uid'].")
		
		
		
	COMMIT";
	//echo $sql_update_fila; //debug
	//$new_result[]=$sql_update_fila; //debug
	$update_fila= $pdo->prepare($sql_update_fila);
	$update_fila->execute();
}
//licenca
elseif($item=="2"){

	$result=$unir_processos;
	$new_result[]=$unir_processos;
	
	$sql=" BEGIN TRAN
		select minuta_id,minuta_id_int,minuta_anterior_id,cliente,cpf,cnpj,inscricao_estadual,minuta_tipo_id,minuta_classificacao_id
,processo_id,processo_id_int,numero,data_emissdao,data_emissao,data_publicacao3,data_publicacao,data_validade,data_registro,data_reuniao,data_hora,edicao,excluido,prazo,motivo,atualizacoes,responsavel_id,area_usuario_insercao_id
,tipo_prazo,tipo_validade,nome_doc,exibir_end_empreendimento,indeferido,fonte,concluido,endereco_empreendedor,endereco_empreendimento,data_emissao_licenca
,renovacao_licenca,numero_processo_renovacao,titulo_minuta,usuario_insercao_id,texto_cabecalho,minuta_id_identity
,teste,documento_generico_id,lo_la,prazo_anos,prazo_dias,reemissao_licenca,dono_empreendimento,processo_anterior_id,check_nao_renovacao,minuta_ciencia,data_ciencia,data_finalizacao
,usuario_empreendedor_aceite_id,usuario_aceite_id,(((processo_id*11)/7)-((processo_id*11)%7)) as p_id


		from minuta as m_temp 
			where processo_id_int in(
				select processo_id_int as processos 
				from processo_cliente 
					where cliente_id_int =(".$cliente_id."))
		
		COMMIT";
	$result = $pdo->query($sql)->fetchAll();

	//$pacote_item1->execute();
	foreach($result as $itens){
		$new_result[]=array_filter($itens, "nao_numerico", ARRAY_FILTER_USE_KEY);
		
	}
	/*$new_result[]=array_filter($new_result[0], function($k) {
							return $k != 'processo_id' || $k!= 'processo_id_int';
														}, ARRAY_FILTER_USE_KEY);
	*/
		$reg=count($new_result);
	$sql_update_fila= "BEGIN TRAN
	
		update download_menu_cliente1  set fim_de_fila=0 where download_cliente_int=(select max(download_cliente_int) from download_menu_cliente1 where cliente_id='".$id."' and download_menu_cliente_id='$item' )
		
		insert into download_menu_cliente1 
			(download_menu_cliente_id,download_cliente,cliente_id,data,quantidade,fim_de_fila,download_cliente_int) 
			values ('$item','".$ultimo_id_varchar."','".$id."',GETDATE(),'".$reg."',1,".$ultimo_id['uid'].")
		
		
		
	COMMIT";
	//$new_result[]=$sql_update_fila; //debug
	$update_fila= $pdo->prepare($sql_update_fila);
	$update_fila->execute();

}
//empreendimentos por cliente
elseif($item=="3"){

	if($id_count==11){
		$sql=" select * from empreendimento where cliente_id_int=
				(select cliente_id_int from cliente where cpf='".$id."')";
		
	}
	elseif($id_count==14){
		$sql=" select * from empreendimento where cliente_id_int=
				(select cliente_id_int from cliente where cnpj='".$id."')";
	}
	else{
		$sql="erro";
	}
		
	$result = $pdo->query($sql)->fetchAll();

	foreach($result as $itens){
		$new_result[]=array_filter($itens, "nao_numerico", ARRAY_FILTER_USE_KEY);
	}
		$reg=count($new_result);
	// atualizar fila
	// adicionar log
	$sql_update_fila= "BEGIN TRAN
	
		update download_menu_cliente1  set fim_de_fila=0 where download_cliente_int=(select max(download_cliente_int) from download_menu_cliente1 where cliente_id='".$id."' and download_menu_cliente_id='$item' )
		
		insert into download_menu_cliente1 
			(download_menu_cliente_id,download_cliente,cliente_id,data,quantidade,fim_de_fila,download_cliente_int) 
			values ('$item','".$ultimo_id_varchar."','".$id."',GETDATE(),'".$reg."',1,".$ultimo_id['uid'].")
		
		
		
	COMMIT";
	//$new_result[]=$sql_update_fila; //debug
	$update_fila= $pdo->prepare($sql_update_fila);
	$update_fila->execute();
	
	//$new_result[]=$sql_update_fila;
}
elseif($item=="4"){
		$sql="BEGIN TRAN
	
		select (((processo_id*11)/7)-((processo_id*11)%7)) as p_id,fundamento_legal,prazo_auto_infracao,texto_auto_infracao,disposicao_infrigida,prazo_defesa
				,prazo_recurso,emitido,rfc,tipo_auto_infracao,fiel_depositario,nome_depositario,endereco_depositario
				,cpf_depositario,cnpj_depositario,testemunha,endereco_testemunha,cpf_testemunha,rg_testemunha,autoridade_fiscalizadora
				,area_id,data_infracao,empreendimento_id,local_infracao,divisao_atividade_id,grupo_atividade_id,x,y,zona,endereco_infracao
			
				from auto_infracao 
			where processo_id in(
				select processo_id as processos from processo_cliente where cliente_id_int =(
				select cliente_id_int from cliente where cpf='".$id."'))
		
		COMMIT";
	//$new_result[]=$sql;	//debug
	$result = $pdo->query($sql)->fetchAll();
	
	//$pacote_item1->execute();
	foreach($result as $itens){
		$new_result[]=array_filter($itens, "nao_numerico", ARRAY_FILTER_USE_KEY);

	}
		$reg=count($new_result);
	$sql_update_fila= "BEGIN TRAN
	
		update download_menu_cliente1  set fim_de_fila=0 where download_cliente_int=(select max(download_cliente_int) from download_menu_cliente1 where cliente_id='".$id."' and download_menu_cliente_id='$item' )
		
		insert into download_menu_cliente1 
			(download_menu_cliente_id,download_cliente,cliente_id,data,quantidade,fim_de_fila,download_cliente_int) 
			values ('$item','".$ultimo_id_varchar."','".$id."',GETDATE(),'".$reg."',1,".$ultimo_id['uid'].")
		
		
		
	COMMIT";
	//$new_result[]=$sql_update_fila; //debug
	$update_fila= $pdo->prepare($sql_update_fila);
	$update_fila->execute();

}
elseif($item=="5"){
		$sql="select * from download_menu_cliente1 where cliente_id='".$id."'";
	$result = $pdo->query($sql)->fetchAll();
	//$pacote_item1->execute();
	foreach($result as $itens){
		$new_result[]=array_filter($itens, "nao_numerico", ARRAY_FILTER_USE_KEY);
	}
		$reg=count($new_result);

}

$id_hash=hash('md5',$id);

//$setar_registro = "";
//echo $setar_registro;  //debug;

//$inserir = $pdo->prepare($setar_registro);

//$inserir->execute();


//$pacote['debug']=$inserir->rowCount();





echo json_encode($new_result);
?>