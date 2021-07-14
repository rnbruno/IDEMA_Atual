<?php 


	require_once "funcoes.php";  
	$id='36352330720';
	
	?>

<html>
<head>




<script type="text/javascript" src="bootstrap3/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="bootstrap3/bootstrap.min.js"></script>

<!-- CSS do Sislia .78-->
<!--<link rel="stylesheet" type="text/css" href="css/style.css"> -->
<link rel="stylesheet" type="text/css" href="bootstrap3/bootstrap.min.css">

<!-- J UI after document-->
<script type="text/javascript" src="jquery-ui-1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-ui-1.12.1/jquery-ui.css">
<script type="text/javascript" src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-ui-1.12.1/jquery-ui.min.css">

<script type="text/javascript">
	 
</script>
</head>	
<style>
.tipo-download{
	color: #002db3;
	cursor: pointer;
}
.tipo-download:hover{
	background-color:#e0ebeb;
}
</style>

<body>
<div class="navbar" id="tudo">
<?php echo "";//require_once("inc/header_index.php")?>

</div>



<div class="container">
  <h2>Menu para downloads</h2>
  <p>Fa&ccedil;a o download dos pacotes de dados</p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Tipo do Download</th>
        <th>Data (&Uacute;ltimo Download)</th>
        <th>Quantidade de Registros</th>
      </tr>
    </thead>
    <tbody>

			
	<?php $sql_imprimir_menu=  "
	
			select dm.download_menu_id, dm.descricao_menu, dmc.data, dmc.quantidade from download_menu dm left join	
				(select download_menu_cliente_id,download_cliente,cliente_id,data,quantidade,fim_de_fila
					from download_menu_cliente1 dmc
				where cliente_id='".$id."' and fim_de_fila=1) dmc
					on dm.download_menu_id=dmc.download_menu_cliente_id
	
		    ";
	$imprimir_menu = $pdo->query($sql_imprimir_menu)->fetchAll();
	
	//echo $sql_imprimir_menu; //debug
	?>
	 
	
	
	<?php 
	
	
	// for para montar a tabela html
	for($count=0;$count<count($imprimir_menu);$count++){ ?>

			<tr>
				<td class="tipo-download" id='<?php echo $imprimir_menu[$count]["download_menu_id"];?>' data-nome="<?php echo $imprimir_menu[$count]["descricao_menu"];?>">
					<?php echo $imprimir_menu[$count]["descricao_menu"];?>
				</td>
				<td><?php if(!is_null($imprimir_menu[$count]["data"]) ){
					echo substr($imprimir_menu[$count]["data"],0,16);}
						else{ echo "Sem data registrada";
						}
						
					 ?>
				</td>
				<td><?php if(!is_null($imprimir_menu[$count]["quantidade"]) ){
					echo $imprimir_menu[$count]["quantidade"];}
						else{ echo "Sem registro";
						}
						
					 ?>
				</td>
			</tr>
		

				
		<?php	

	  		 
	}?>
	
     
      
	  
	  
    </tbody>
  </table>
</div>
<!-- content -->

<?php echo "";//include("inc/footer.php"); ?>


</div>



<div class="footer">
</div>
<script>
var link_pega_id;
var topic_menu;
$(document).ready(function(){
	
	$(".tipo-download").click(function(a){
	a.preventDefault();
	link_pega_id = event.srcElement.id;
	//topic_menu=link_pega_id.replace("item","");
		$(function () {
							if (confirm('Voc\u00ea confirmar o download!?')) {
						
								$.ajax({
									url: 'ajaxItem1.php', 
									dataType: 'json',
									type: 'POST',
									data: {item: link_pega_id, id: "<?= $id?>",},
									success: function(data){
										var data_json = (data);
										//console.log(data_json);

										//Estructure for download arquive
										//attr value data_name
										exportName = $('#'+link_pega_id).attr('data-nome');
										
										//alert(exportName);
										
										var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(data_json));
										var downloadAnchorNode = document.createElement('a');
										downloadAnchorNode.setAttribute("href",     dataStr);
										downloadAnchorNode.setAttribute("download", exportName + ".json");
										document.body.appendChild(downloadAnchorNode); // required for firefox
										downloadAnchorNode.click();
										downloadAnchorNode.remove();
										
										atualizar();
										
										
									},
									error: function() {
										alert('erro');
									}
							
							});
							

							}else{}
							
							
			
		});
		
	});
});
function atualizar(){
	setTimeout(function() {
		window.location.reload(1);
							}, 500);
}
/*
function baixarTexto(guard) {
            guard.preventDefault();
            let data = document.querySelector('#item1').value;
            let blob = new Blob([data], { type: 'application/json;charset=utf-8;' });
            const anchor = window.document.createElement('a');

            anchor.href = window.URL.createObjectURL(blob);
            anchor.download = 'export.json';
            anchor.click();
            window.URL.revokeObjectURL(anchor.href);
}
*/




</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">

</script>
<!--End of Tawk.to Script-->
</body>
</html>