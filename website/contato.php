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
		<script src="js/jquery-migrate-1.0.0.js"></script>

	</head>
	
<body>

	<a href="#top" id="toTop"></a>
		

		
		
    <!-- Fixed navbar -->
	<div id="nav-wrapper">
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

	<!-- FAQ Section -->	
	<div id="section8">

			<div class="container" id="Cadastro">
				<div class="row">				
					<div class="contFrm">
							<div class="col-md-8 col-md-offset-2">
								<form action="sendMail.php" method="post">	
									<div class="col-md-12 alt_heading uk-scrollspy-init-inview uk-scrollspy-inview uk-animation-scale-up" data-uk-scrollspy="{cls:'uk-animation-scale-up', repeat: true}">
										<h2 class="text-center" style="width: 100%;">Contato<span></span></h2>
									</div>
									<div class="col-md-12 text-center">
										<fieldset>
											<input type="text" name="nome" placeholder="Nome">
										</fieldset>
										<fieldset>
											<input type="text" name="email" placeholder="Email">
										</fieldset>	
										<fieldset>
											<input type="text" name="tel" placeholder="Telefone">
										</fieldset>
										<fieldset>
											<select name="departamento">
												<option value="suporte">Suporte técnico</option>
												<option value="financeiro">Financeiro</option>
												<option value="contato">Contato</option>
											</select>
										</fieldset>
										<fieldset>
											<textarea name="mensagem" placeholder="Mensagem" style="width:100%"></textarea>
										</fieldset>
										<div class="col-md-10 col-md-offset-1">
											<input type="submit" value="ENVIAR!" class="button">
										</div>
									</div>
								</form>	
							</div>	
					</div>
				</div>
			</div>
		</div>
	</div>		
		
<?php include 'footer.php' ?>