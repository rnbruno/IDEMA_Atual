<?php
	

?>

<?php require_once "../conf_ponto/funcoes.php"; 
	//
	
	$title=safeget("titulo");
	//$tt='';
	$id=$_SESSION["usuario_id"];
	//echo $id; //debug
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com    @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Cadastrar Afastamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Jquery -->
	<script type="text/javascript" src="../import/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="../import/jquery-3.6.0.js"></script>
	
	<!-- Jquery Ui -->
	
	<script type="text/javascript" src="../import/jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="../import/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
	

	
	<!-- Bootstrap 4.4.1 [Get] -->
	<link rel="stylesheet" type="text/css" href="../import/bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../import/bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/css/bootstrap.min.css">
	<!--
	<link rel="stylesheet" type="text/css" href="../../../bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/css/bootstrap-grid.css">
	<link rel="stylesheet" type="text/css" href="../../../bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/css/bootstrap-grid.min.css">
	<link rel="stylesheet" type="text/css" href="../../../bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/css/bootstrap-reboot.css">
	<script type="text/css" src="../import/bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/js/bootstrap.bundle.js"></script>
	<script type="text/css" src="../../../bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/js/bootstrap.bundle.min.js"></script>
	-->
	<script type="text/css" src="../import/bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/js/bootstrap.js"></script>
	<script type="text/css" src="../import/bootstrap-4.4.1-dist/bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
	
	<!--Style CSS for datapiker-->
	<link rel="stylesheet" type="text/css" href="../import/style.css">
	<link rel="stylesheet" type="text/css" href="../import/jquery-ui-1.12.1.custom/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../import/jquery-ui-1.12.1.custom/jquery-ui.structure.css">
	
	<!--import functio js
	<script type="module" src="function.mjs"></script>
	<script>
	import 'function.mjs'
	//  .import { data_compare } from 'function.mjs'
	console.log(data_compare(30, 20)) 
	</script>
	-->
	
	
	<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
	
	

</head>

<style>
	.form-cadastro{
		background-color:#f2f2ff;
	}
	
	<!--.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
		border: 1px solid #d3d3d3;
		background: #e6e6e6 url(images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x;
		font-weight: normal;
		color: #555555;
	}
	.ui-datepicker td span, .ui-datepicker td a {
		display: block;
		padding: .2em;
		text-align: right;
		text-decoration: none;
	}
	.ui-datepicker-calendar{
		background-color: #FFFFFF !important;
	}
	.ui-widget {
		font-family: Verdana,Arial,sans-serif;
	}
	.ui-widget-header {
		border: 1px solid #aaaaaa;
		background: #cccccc url(images/ui-bg_highlight-soft_75_cccccc_1x100.png) 50% 50% repeat-x;
		color: #222222;
		font-weight: bold;
	}
	.ui-datepicker-prev ui-corner-all
	

	div.p.comment{
		font-size:8px !important;
	}
	comment{
		font-size:8px;
	}
	-->
	.show{
		display:block;
	}
	.restringir{
		display:none;
	}
</style>

<title>
</title>

<body>
	<div class="container">
	
		<div class="row">
			
			<div class="col-md-12" >
			</br></br>
				<h4 class="mb-3"><?php echo $title;?></h4>
			</div>
			<div class="col-md-12 form-cadastro">
				<form id="forms">
					<div class="row">
						<div class="col-md-6 mb-3">
	<?php
	$result_tipo_frequencia = $ponto->query("SELECT cod_frequencia,nome_frequencia FROM tipo_frequencia order by nome_frequencia asc")->fetchAll();
	?>
							<label for="tipo_afastamento"><b>*Tipo de afastamento</b></label>
							<select class="custom-select d-block w-100" id="tipo_afastamento" required>
								<option value="">Escolha um tipo de frequ&ecirc;ncia</e</option></em>
	<?php	
	foreach($result_tipo_frequencia as $item_tipo_frequencia){
	?>
								<option value="<?php echo $item_tipo_frequencia["cod_frequencia"];?>"><?php echo strtoupper($item_tipo_frequencia["nome_frequencia"]);?></option>
	<?php
	}
	?>
							</select>
							<div class="invalid-feedback">
							  Por favor insira um tipo de afastamento v&aacute;lido.
							</div>
						</div>
						<!--<div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker">
							<input placeholder="Select date" type="text" id="example" class="form-control">
							<label for="example">Try me...</label>
							<i class="fas fa-calendar input-prefix" tabindex=0></i>
						</div>
						-->
						<div class="col-md-3 mb-3">
							<label for="data_inicial"><b>*Data Inicial</b></label>
							<input placeholder="Selecione a data in&iacute;cio" type="text" autocomplete="off" name="datepicker_inicio" id="datepicker_inicio" class="datepicker form-control" required>
							<div id="feedback_date_i" class="invalid-feedback">
							  Data inv&aacute;lida.
							</div>
						
						</div>
						
						<!-- <p>Date: <input type="text" id="datepicker"></p> Jquery Ui reference-->
						
						<div class="col-md-3 mb-3">
							<label for="data_final"><b>*Data Final</b></label>
							<input placeholder="Selecione a data final"type="text" autocomplete="off" class="datepicker form-control" name="datepicker_final" id="datepicker_final"  required>
							<div class="invalid-feedback">
							  Data inv&aacute;lida.
							</div>
						</div>
						
					</div>
					
	
					<div class="row">
						<div class="col-md-6 mb-3">
	<?php
	$result = $ponto->query("SELECT id_pessoa,nome_pessoa FROM `pessoa` order by nome_pessoa asc")->fetchAll();
	?>
							<label for="cc-name"><b>*Selecione usu&aacute;rio do Ponto</b></label>
							<select class="custom-select d-block w-100" id="user_ponto" required>
								<option value=""><i>Escolha um colaborador</i></option>
	<?php	
	foreach($result as $item_usuario){
	?>
								<option value="<?= $item_usuario["id_pessoa"];?>"><?= strtoupper($item_usuario["nome_pessoa"]);?></option>
	<?php
	}
	?>
								
							</select>
							<div class="invalid-feedback">
							  Selecione us&aacute;rio v&aacute;lido.
							</div>
						</div>
						
					</div>
					<!--
					<div class="row">
						<div class="col-md-6 mb-3">
					<?php
					$result = $pdo->query("select login from usuario")->fetchAll();
					?>
							<label for="cc-name">Selecione usu&aacute;rio Cerberus</label>
							<select class="custom-select d-block w-100" id="usuario" required>
					<?php	
					foreach($result as $item_usuario){
					?>
								<option value="<?= $item_usuario["login"];?>"><?= $item_usuario["login"];?></option>
					<?php
					}
					?>
								<option>Marques de Sales</option>
							</select>
							<div class="invalid-feedback">
							  Please select a valid country.
							</div>
						</div>
						
					</div>
					-->
					<div class="row">
						<div class="col-md-6" style="font-size:10px">
							<p class="comment"><em>*Arquivos com (*) s&atilde;o obrigat&oacute;rios.</em></p>
						</div>
					</div>
					<hr class="mb-4">
					<div class="col-md-3 mb-3">
						<button class="btn btn-primary btn-lg btn-block" type="submit">Submeter</button>
					</div>
			
				</form>
					<div class="row">
						<div class="col-md-12" style="font-size:10px">
							<p id="listagem" class=""></p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="font-size:10px">
							<p id="reg" class=""></p>
						</div>
					</div>
					
			</div>
			<div class="row">
						<div class="col-md-12" style="font-size:10px">

								<!-- Modal -->
								<div class="modal fade" id="princ_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Inform cadastro afastamento</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										  <span aria-hidden="true">&times;</span>
										</button>
									  </div>
									  <div class="modal-body">
										...
									  </div>
									  <div class="modal-footer">
										<button id="close_modal" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary">Save changes</button>
									  </div>
									</div>
								  </div>
								</div>
					
						</div>
			</div>
			
		</div>
    
		
	</div>
<script>

function data_compare(data_i,data_f){
		msg = new Array();
	if(data_i>data_f){
			msg["alert"]="A data inicial e maior que a final";
			msg["status"]=1;
		
	}else{
		msg["alert"]="Data Correta";
		msg["status"]=0;
		
	}
	return msg;
}

$( function() {
	var options = {
				dateFormat: 'dd/mm/yy',
				dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
				dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
				dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
				monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
				nextText: 'Próximo',
				prevText: 'Anterior',
				minDate: 0,
				onSelect: function (dateText, inst) {
					$.ajax({
						url: 'validate.php',
						type: 'POST',
						dataType: 'json',
						data: {
							data_visita: $("#data_visita").val()
						},
						success: function (data, textStatus, xhr) {
							$("#feedback_date_i").show();
							$("#feedback_date_i").html("A  <b>+data.vagas+</b> data  é invalidade!");
						}
					});
					
				}
	};
    $( ".datepicker" ).datepicker(options);
});
/*
$('select').each(function () {
	console.log(this);
	//cache number options
	var $this = $(this),
        numberOfOptions = $(this).children('option').length;
		console.log(numberOfOptions);
		$(this).children('option').css("color", "red");
});
*/
$(function(){
  $("#select option:first").css("color", "green");
});

//submit

$( "#forms" ).submit(function( a ) {
  a.preventDefault();
  alert("enviar");
  t_p=$('#tipo_afastamento option:selected').val();
  d_i=$('#datepicker_inicio').val();
  d_f=$('#datepicker_final').val();
  u_p=$('#user_ponto').val();

//    
  // console.log(data_compare(d_i,d_f)) // debug
  data_com=data_compare(d_i,d_f);
  console.log(data_com["alert"]);
  
	var result = confirm("O usuario já possui afastamento cadastrado, deseja continuar!?");
	  if (result==true) {
		  $.ajax({
					type: "POST",
					url: "ajax_recebe_afastamento.php",
					beforeSend:function(){
						$('#tipo_afastamento').prop( 'disabled', true );
						$('#datepicker_inicio').prop( 'disabled', true );
						$('#datepicker_final').prop( 'disabled', true );
						$('#user_ponto').prop( 'disabled', true );
					},
					data:{
						tipo_afastamento: t_p,
						date_beginning: d_i,
						date_ending: d_f,
						user_ponto: u_p,
						user_Cerb: '<?php echo $id;?>'
					},
					success: function( data ) {
						$("#listagem").html(data);
						$("#reg").html(data);
						$(".fade").addClass("show");
						
						
						//$('#area_id').prop( 'disabled', false );
					},  
					error: function(jqXHR, textStatus, errorThrown){
						alert("Erro: "+textStatus+","+errorThrown+" -> "+jqXHR.responseText);	
					},  
					cache:false
					
				});	
	  }
});


$("#close_modal").click(function(){
	$("#princ_Modal").removeClass("show");
	$('#tipo_afastamento').prop( 'disabled', false );
	$('#datepicker_inicio').prop( 'disabled', false );
	$('#datepicker_final').prop( 'disabled', false );
	$('#user_ponto').prop( 'disabled', false );
	$("#reg").addClass("restringir");
	setTimeout($("#reg").css("display:none"), 3000);
});






</script>
</script>
</body>

</html>
