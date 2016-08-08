<?php
	$dbh = new PDO('pgsql:host=localhost user=rodrigo dbname=evs password=123456');

	// $stmt = $dbh->prepare('INSERT INTO ponto_de_venda (
	// 	id_classe_ponto_de_venda,
	// 	id_uf,
	// 	id_anfitriao,
	// 	nome,
	// 	endereco,
	// 	telefone,
	// 	site,
	// 	data_expiracao,
	// 	dta,
	// 	id_anfitriao_master
	// ) VALUES (
	// 	:id_classe_ponto_de_venda,
	// 	:id_uf,
	// 	:id_anfitriao,
	// 	:nome,
	// 	:endereco,
	// 	:telefone,
	// 	:site,
	// 	:data_expiracao,
	// 	:dta,
	// 	:id_anfitriao_master
	// )');
	//
	// $id_classe_ponto_de_venda = '1';
	// $id_uf = '25';
	// $id_anfitriao = '2';
	// $nome = 'PHP User 01';
	// $endereco = 'Rua Teste, 123';
	// $telefone = '11999999999';
	// $site = 'http://site.com';
	// $data_expiracao = '2222-01-01';
	// $dta = '2016-08-04 17:26:21';
	// $id_anfitriao_master = '2';
	//
	// $stmt->bindParam('id_classe_ponto_de_venda', $id_classe_ponto_de_venda);
	// $stmt->bindParam('id_uf', $id_uf);
	// $stmt->bindParam('id_anfitriao', $id_anfitriao);
	// $stmt->bindParam('nome', $nome);
	// $stmt->bindParam('endereco', $endereco);
	// $stmt->bindParam('telefone', $telefone);
	// $stmt->bindParam('site', $site);
	// $stmt->bindParam('data_expiracao', $data_expiracao);
	// $stmt->bindParam('dta', $dta);
	// $stmt->bindParam('id_anfitriao_master', $id_anfitriao_master);

	// if ($stmt->execute()) {
	// 	echo 'true';
	// } else {
	// 	echo 'false';
	// }

	$stmt = $dbh->prepare('INSERT INTO anfitriao (
		id_ponto_de_venda,
		username,
		username_canonical,
		email,
		email_canonical,
		enabled,
		salt,
		password,
		last_login,
		locked,
		expired,
		expires_at,
		confirmation_token,
		password_requested_at,
		roles,
		credentials_expired,
		credentials_expire_at,
		nome,
		endereco,
		faixa,
		telefone,
		cpf,
		dta
	) VALUES (
		:id_ponto_de_venda,
		:username,
		:username_canonical,
		:email,
		:email_canonical,
		:enabled,
		:salt,
		:password,
		:last_login,
		:locked,
		:expired,
		:expires_at,
		:confirmation_token,
		:password_requested_at,
		:roles,
		:credentials_expired,
		:credentials_expire_at,
		:nome,
		:endereco,
		:faixa,
		:telefone,
		:cpf,
		:dta
	)');

	$id_ponto_de_venda = '';
	$username = '';
	$username_canonical = '';
	$email = '';
	$email_canonical = '';
	$enabled = '';
	$salt = '';
	$password = '';
	$last_login = '';
	$locked = '';
	$expired = '';
	$expires_at = '';
	$confirmation_token = '';
	$password_requested_at = '';
	$roles = '';
	$credentials_expired = '';
	$credentials_expire_at = '';
	$nome = '';
	$endereco = '';
	$faixa = '';
	$telefone = '';
	$cpf = '';
	$dta = '';

	$stmt->bindParam(':id_ponto_de_venda', $id_ponto_de_venda);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':username_canonical', $username_canonical);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':email_canonical', $email_canonical);
	$stmt->bindParam(':enabled', $enabled);
	$stmt->bindParam(':salt', $salt);
	$stmt->bindParam(':password', $password);
	$stmt->bindParam(':last_login', $last_login);
	$stmt->bindParam(':locked', $locked);
	$stmt->bindParam(':expired', $expired);
	$stmt->bindParam(':expires_at', $expires_at);
	$stmt->bindParam(':confirmation_token', $confirmation_token);
	$stmt->bindParam(':password_requested_at', $password_requested_at);
	$stmt->bindParam(':roles', $roles);
	$stmt->bindParam(':credentials_expired', $credentials_expired);
	$stmt->bindParam(':credentials_expire_at', $credentials_expire_at);
	$stmt->bindParam(':nome', $nome);
	$stmt->bindParam(':endereco', $endereco);
	$stmt->bindParam(':faixa', $faixa);
	$stmt->bindParam(':telefone', $telefone);
	$stmt->bindParam(':cpf', $cpf);
	$stmt->bindParam(':dta', $dta);

	if ($stmt->execute()) {
		echo 'true';
	} else {
		echo 'false';
	}
?>
