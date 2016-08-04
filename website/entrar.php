
<!DOCTYPE html>
<html lang="pt-br" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>EVS - Espaço de Sucesso!</title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="theweblab" />
		
		<link rel="icon" type="image/png" href="../favicon.png">
						
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<link href="lib/uikit/css/uikit.min.css" rel="stylesheet" />
		
		<link href="css/style.css" rel="stylesheet">
		<link href="css/responsive.css" rel="stylesheet">
		<link href="css/layout-style-parallax.css" rel="stylesheet">
		<link href="css/google-font-OpenSans.css" rel="stylesheet">
		
		<link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">	
							
		<link href="lib/CSS3AnnotationOverlayEffect/css/style.css" rel="stylesheet" />		

		<link href="lib/jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />
		
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.mask.min.js"></script>
		<!--script src="js/jquery-migrate-1.0.0.js"></script-->

		<script>
		$(function(){
			$("#entidade_numero").mask("00000000000");
			$("#usuario_telefone").mask("(00) 00000-0000");

			$("#close-app-download").on('click', function(e) {
				event.preventDefault();
				$("#app-evs").fadeOut('slow');
			});

			var usuarioFoto = document.getElementById("usuario_foto"), 
			    formdata = false,
			    usuario_foto = "";

			if (window.FormData) {
			    formdata = new FormData();
			}

			usuarioFoto.addEventListener("change", function (evt) {
			    var len = this.files.length, img, reader, file;
			        file = this.files[0];

		        if (!!file.type.match(/image.*/)) {
		            if ( window.FileReader ) {
		                reader = new FileReader();
		                reader.onloadend = function (e) { 
		                    //console.log(e.target.result + file.fileName);
		                    usuario_foto = e.target.result;

		                    tempImg = new Image();
		                    tempImg.src = e.target.result;
		                    widthImg = parseInt(tempImg.width);
		                    heightImg = parseInt(tempImg.height);

		                    //console.info("Largura: "+ widthImg + "px - Altura: "+ heightImg + "px");

		                    imgMargin = parseInt( (widthImg * 84) / widthImg ) - 84;
		                    console.log( imgMargin );

		                    var textMarginCss = "";
		                    if(imgMargin <= 0){
		                    	textMarginCss = imgMargin +'px';
		                    }else{
		                    	textMarginCss = '-'+ imgMargin +'px';
		                    }

		                    $("#beforeSelectImg").css({
		                    	background: 'url('+ e.target.result +')',
		                    	backgroundSize: 'cover',
		                    	backgroundPositionX: textMarginCss
		                    });
		                };
		                reader.readAsDataURL(file);
		            }
		        }
		    //}
			}, false);

			$("#entidade_nome").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#usuario_nome").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#usuario_email").change(function(e) {
				if( $(this).val != "" || validateEmail( $(this).val ) ){
					$(this).removeClass('field-error');
				}
			});
			$("#usuario_senha").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#usuario_telefone").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#usuario_genero_id").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#entidade_tipo_id").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#entidade_numero").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#entidade_endereco").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#estado").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#cidade").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			$("#entidade_bairro").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});
			/*$("usuario_foto").change(function(e){
				if( $(this).val != "" ){
					$(this).removeClass('field-error');
				}
			});*/
	
			var vPlan = '';
			if( $('#radio-plano-0').is(":checked") ){
				vPlan = '0';
			}else if( $('#radio-plano-1').is(":checked") ){
				vPlan = '1';
			}else if( $('#radio-plano-2').is(":checked") ){
				vPlan = '2';
			}else{
				vPlan = '1';
			}

			$("#contactform").submit(function(e){
				e.preventDefault();

				$("#loading").fadeIn('fast');
				//var dataForm = $(this).serialize();

				var dataForm = {
						"entidade_nome"		: $("#entidade_nome").val(),
						"usuario_nome"		: $("#entidade_nome").val(),
						"usuario_email"		: $("#usuario_email").val(),
						"usuario_senha"		: $("#usuario_senha").val(),
						"usuario_telefone"	: $("#usuario_telefone").val(),
						"usuario_genero_id"	: $("#usuario_genero_id").val(),
						"entidade_tipo_id"	: 1,
						"entidade_numero"	: $("#entidade_numero").val(),
						"entidade_endereco"	: $("#entidade_endereco").val(),
						"estado"			: $("#estado").val(),
						"cidade"			: $("#cidade").val(),
						"entidade_bairro"	: $("#entidade_bairro").val(),
						"usuario_foto"		: usuario_foto, //base64
						"plano"				: vPlan
					},
					fieldsVerify = [];

					//console.log(dataForm);

				if( $("#entidade_nome").val() == "" ){
					fieldsVerify.push("entidade_nome");
				}
				if( $("#usuario_nome").val() == "" ){
					fieldsVerify.push("usuario_nome");
				}
				if( $("#usuario_email").val() == "" || $("#usuario_email").val().indexOf('@') < 1 ){
					fieldsVerify.push("usuario_email");
				}
				if( $("#usuario_senha").val() == "" ){
					fieldsVerify.push("usuario_senha");
				}
				if( $("#usuario_telefone").val() == "" ){
					fieldsVerify.push("usuario_telefone");
				}
				if( $("#usuario_genero_id").val() == "" ){
					fieldsVerify.push("usuario_genero_id");
				}
				if( $("#entidade_tipo_id").val() == "" ){
					fieldsVerify.push("entidade_tipo_id");
				}
				if( $("#entidade_numero").val() == "" ){
					fieldsVerify.push("entidade_numero");
				}
				if( $("#entidade_endereco").val() == "" ){
					fieldsVerify.push("entidade_endereco");
				}
				if( $("#estado").val() == "" ){
					fieldsVerify.push("estado");
				}
				if( $("#cidade").val() == "" ){
					fieldsVerify.push("cidade");
				}
				if( $("#entidade_bairro").val() == "" ){
					fieldsVerify.push("entidade_bairro");
				}
				/*if( $("usuario_foto").val() == "" ){
					fieldsVerify.push("usuario_foto");
				}*/
				if( !($("#termos").is(":checked")) ){
					fieldsVerify.push("termos");
				}

				//console.log(usuario_foto);
				if( fieldsVerify.length == 0 ){
					$.ajax({
						url: 'send.php',
						type: 'post',
						data: dataForm,
			            success: function (data) {
			                console.log(data);
			                if( data.sucesso == true ){
			                	$("#loading").fadeOut('fast', function(){
			                		$("#loading:after").hide();
			                		$("#app-evs").fadeIn('slow');
			                	});

			                	$("#entidade_nome").val("");
								$("#usuario_nome").val("");
								$("#usuario_email").val("");
								$("#usuario_senha").val("");
								$("#usuario_telefone").val("");
								$("#usuario_genero_id").val("");
								$("#entidade_tipo_id").val("");
								$("#entidade_numero").val("");
								$("#entidade_endereco").val("");
								$("#estado").val("");
								$("#cidade").val("");
								$("#entidade_bairro").val("");
			                }else{
			                	$("#loading").fadeOut('fast', function(){
				            		$("#loading:after").hide();
				                	alert('Verifique seus dados ou tente usar outro e-mail e tente novamente.');
				                });
			                }
			                //window.location = data[0].usuario_foto;
			            },
			            error: function(data){
			            	//console.error(data);
			            	console.error(data);
			            	console.error('erro ajax');
			            	$("#loading").fadeOut('fast', function(){
			            		$("#loading:after").hide();
			                	alert('Não foi possível enviar sua solicitação, por favor tente mais tarde.');
			                });
			            }
					});
				}else{
					console.log("Preencha os campos corretamente");
					$("#loading").fadeOut('fast', function(){
						$("#loading:after").hide();
			           	alert('Preencha os campos corretamente');
					});
					for(i = 0; i < fieldsVerify.length; i++){
						//console.log( "Preencha corretamente o campo: "+ fieldsVerify[i] );
						$("#"+ fieldsVerify[i]).addClass('field-error');
						console.error("campo: "+ fieldsVerify[i]);
					}

					if( fieldsVerify.indexOf('termos') ){
						$("#termos_alert").show();
					}


				}
			});
		});
		</script>

		<style type="text/css">
			.priceTime{
				display: none;
			}
			table{
				width: 100%;
			}
				table th{
					padding: 15px;
					font-size: 20px;
					line-height: 24px;
					color: #FFF;
					background: #66BC29;
					margin-bottom: 30px;
					border-top: 0;
				}
					table th.title-left{
						border-radius: 6px 0 0 0;
						border-right: 1px #ccc solid;
					}
					table th.title-right{
						border-radius: 0 6px 0 0;
					}
				table td{
					padding: 10px 15px;
					color: #979CA2;
					border-bottom: 1px solid #F5F5F5;
					-webkit-transition: 300ms;
					transition: 300ms;
				}
					table td.text-center{ text-align: center; }
					table td.text-right{ text-align: right; }

				table tr > td:first-child{
					border-right: 1px #F5F5F5 solid;
				}

				table tr.bg-gray{
					background: #F5F5F5;
				}

				.forma-pagamento{
					border: 1px solid #66BC29;
					color: #979CA2;
					margin-top: 5px;
					padding: 12px 8px;
					width: 100%;
					border-radius: 3px;
					margin: 0 auto 20px auto;
					display: block;
					text-align: left;
					position: relative;
				}
					.forma-pagamento .sticker{
						width: 148px;
						height: 148px;
						position: absolute;
						background-image: url(images/sticker-30-dias.png);
						right: -80px;
						top: -20px;
					}
					.forma-pagamento label{
						display: inline-block;
						cursor: pointer;
					}
					input.radio{
						margin: 0 10px;
						padding: 0;
						display: inline-block;
						vertical-align: middle;
						height: initial;
						width: initial !important;
					}
			.clear-fix{ clear: both; }
			.fields3{ float: none; margin: 0 auto; }
			.field-error{
				border-color: #D21313;
				box-shadow: 0 0 5px -3px #D21313;
				background: #FFF0F2;
			}
			.field-error-text{
				font-size: 10px;
				color: #D21313;
				display: none;
			}
			input{
				color: #000;
			}
			#termos_alert{
				color: #D21313;
				display: none;
			}
			.inputs{ width: 58.5%; margin: 0 auto }
				.inputs input, .inputs select{ width: 100% }
				.inputs .text-right{ padding-left: 0 }
				.inputs .text-left{ padding-left: 30px }
				#usuario_foto{
					position: absolute;
					border: 0;
					top: -5px;
					width: 100%;
					height: 40px;
					opacity: 0;
					cursor: pointer;
				}
				.button-select-img{
					width: 100%;
					padding: 10px;
				}
				#beforeSelectImg{
					background: #E5E5E5;
					border: 1px solid #66BC29;
					width: 84px;
					height: 84px;
					position: absolute;
					top: -42px;
					right: 2px;
				}

				
			@media screen and (max-width: 990px) {
				.inputs .text-left{
					padding-left: 0;
				}

				#beforeSelectImg {
					position: initial;
					margin: 0 auto;
				}

				.forma-pagamento .sticker {display: none;}
			}
		</style>
	</head>
<body>

	<a href="#top" id="toTop"></a>
		
    <!-- Fixed navbar -->
	<div>
		<div id="nav" class="navbar">
			<div class="container">
				<div class="navbar-header">					
					<a class="navbar-brand" href="index.php"><img src="images/icon-app.png" alt="logo"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="current"><a href="#section1" onclick="window.location = this.href">Topo</a></li>
						<li><a href="index.php#section2" onclick="window.location = this.href">Serviços</a></li>
						<li><a href="index.php#section3" onclick="window.location = this.href">Infos</a></li>
						<li><a href="index.php#section4" onclick="window.location = this.href">Clientes</a></li>
						<li><a href="index.php#section6" onclick="window.location = this.href">Preços</a></li>
						<li><a href="index.php#section7" onclick="window.location = this.href">FAQ</a></li>
						<li><a href="contato.php" onclick="window.location = this.href">Contato</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>  	
	</div>

	<!-- Pricing Table -->	
	<div>	
			
		<div> <!-- <div class="price lightgray"> -->

			<br>
			<div class="container text-center">
				<div class="row">
					<div class="col-md-12 alt_heading">
						<h2>Sistema em Manutenção<span></span></h2>
						<h4>Desculpem o transtorno, estamos trabalhando para melhor atende-los.</h4>
					</div>
				</div>
			</div>

		</div>	

<br><br><br><br>

	</div>


<?php include 'footer.php' ?>