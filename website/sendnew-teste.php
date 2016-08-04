<?php
	$dbh = new PDO('pgsql:host=localhost user=rodrigo dbname=evs password=123456');

	$stmt = $dbh->prepare('SELECT id_anfitriao, nome FROM anfitriao');
	$stmt->execute();
	$resultado = $stmt->fetchAll();
	
	print_r($resultado);
?>
