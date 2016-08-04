<?php
	$bairro = $_POST["entidade_bairro"];
	$cidade = $_POST["plano"];
	$cpf = $_POST["entidade_numero"];
	$email = $_POST["usuario_email"];
	$endevs = $_POST["entidade_endereco"];
	$estado = $_POST["estado"];
	$genero = $_POST["usuario_genero_id"];
	$imagem = $_FILES["usuario_foto"];
	$nome = $_POST["entidade_nome"];
	$plano = $_POST["cidade"];
	$senha = $_POST["usuario_senha"];
	$telefone = $_POST["usuario_telefone"];

	echo json_encode($_POST);
	$dbh = new PDO('pgsql:user=rodrigo dbname=evs password=123456');

?>
