<?php
	$dbh = new PDO('pgsql:host=localhost user=rodrigo dbname=evs password=123456');

	// Ponto de venda ----------------------------------------------------------

	$id_classe_ponto_de_venda = '1';
	$id_uf = '25';
	// $id_anfitriao = '2'; // duplicidade
	// $nome = 'PHP User 01'; // duplicidade
	// $endereco = 'Rua Teste, 123'; // duplicidade
	$telefone = '11999999999';
	$site = 'http://site.com';
	$data_expiracao = '2222-01-01';
	// $dta = '2016-08-04 17:26:21'; // duplicidade
	$id_anfitriao_master = '2';

	// Anfitrião ---------------------------------------------------------------

	// $id_anfitriao = '11'; //duplicidade
	$id_ponto_de_venda = '1';
	$username = $username_canonical = $_POST['username'];
	$email = $email_canonical = $_POST['usuario_email']; //'webmaster2@cm2tech.com.br';
	$enabled = 't';
	$salt = '37hnsd90fgn3hfdcgh26492jnsge23h';
	$password = 'calfat@109{37hnsd90fgn3hfdcgh26492jnsge23h}';
	// $last_login = '';
	$locked = 'f';
	$expired = 'f';
	// $expires_at = 'teste vázio 03';
	$confirmation_token = 'teste vázio 04';
	// $password_requested_at = 'teste vázio 05';
	$roles = 'tipo json';
	$credentials_expired = 'f';
	// $credentials_expire_at = 'teste vázio 06';
	// $nome = 'Webmaster'; // duplicidade
	// $endereco = 'Rua Comendador Miguel Calfat, 109'; // duplicidade
	$faixa = '25';
	$telefone = 'teste vázio 08';
	$cpf = 'teste vázio 09';
	// $dta = '2016-03-30 16:31:51'; //duplicidade

	// Ambos -------------------------------------------------------------------

	$dta = '2016-03-30 16:31:51';
	$nome = 'nome';
	// $id_anfitriao = '12'; // TODO gerado dinamicamente
	$endereco = 'Rua Comendador Miguel Calfat, 109';

	// ID Anfitrião ------------------------------------------------------------

	$stmt = $dbh->query('SELECT id_anfitriao AS id FROM anfitriao ORDER BY id_anfitriao DESC LIMIT 1');
	$id_anfitriao = $stmt->fetch()[id] + 1;

	// Validação de Username ---------------------------------------------------

	$stmt = $dbh->prepare('SELECT id_anfitriao FROM anfitriao WHERE username = :username');
	$stmt->bindParam(':username', $username);
	$stmt->execute();

	if (!empty($stmt->fetch())) {
		$retorno['codigo'] = 400;
		$retorno['mensagem'] = 'Tente usar outro "Username", este já está em uso.';
	} else {

		// Validação de E-mail -------------------------------------------------

		$stmt = $dbh->prepare('SELECT id_anfitriao FROM anfitriao WHERE email = :email');
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		if (!empty($stmt->fetch())) {
			$retorno['codigo'] = 400;
			$retorno['mensagem'] = 'Este e-mail já está registrado! Que tal recuperar sua senha?';
		} else {
			// $retorno['codigo'] = 200;
			// echo json_encode($retorno);




			$stmt = $dbh->prepare('INSERT INTO anfitriao (
				id_anfitriao,
				id_ponto_de_venda,
				username,
				username_canonical,
				email,
				email_canonical,
				enabled,
				salt,
				password,
				locked,
				expired,
				confirmation_token,
				roles,
				credentials_expired,
				nome,
				endereco,
				faixa,
				telefone,
				cpf,
				dta
			) VALUES (
				:id_anfitriao,
				:id_ponto_de_venda,
				:username,
				:username_canonical,
				:email,
				:email_canonical,
				:enabled,
				:salt,
				:password,
				:locked,
				:expired,
				:confirmation_token,
				:roles,
				:credentials_expired,
				:nome,
				:endereco,
				:faixa,
				:telefone,
				:cpf,
				:dta
			)');

			$stmt->bindParam(':id_anfitriao', $id_anfitriao);
			$stmt->bindParam(':id_ponto_de_venda', $id_ponto_de_venda);
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':username_canonical', $username_canonical);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':email_canonical', $email_canonical);
			$stmt->bindParam(':enabled', $enabled);
			$stmt->bindParam(':salt', $salt);
			$stmt->bindParam(':password', $password);
			// $stmt->bindParam(':last_login', $last_login);
			$stmt->bindParam(':locked', $locked);
			$stmt->bindParam(':expired', $expired);
			// $stmt->bindParam(':expires_at', $expires_at);
			$stmt->bindParam(':confirmation_token', $confirmation_token);
			// $stmt->bindParam(':password_requested_at', $password_requested_at);
			$stmt->bindParam(':roles', $roles);
			$stmt->bindParam(':credentials_expired', $credentials_expired);
			// $stmt->bindParam(':credentials_expire_at', $credentials_expire_at);
			$stmt->bindParam(':nome', $nome);
			$stmt->bindParam(':endereco', $endereco);
			$stmt->bindParam(':faixa', $faixa);
			$stmt->bindParam(':telefone', $telefone);
			$stmt->bindParam(':cpf', $cpf);
			$stmt->bindParam(':dta', $dta);

			if ($stmt->execute()) {
				// echo 'true';

				$stmt = $dbh->prepare('INSERT INTO ponto_de_venda (
					id_classe_ponto_de_venda,
					id_uf,
					id_anfitriao,
					nome,
					endereco,
					telefone,
					site,
					data_expiracao,
					dta,
					id_anfitriao_master
				) VALUES (
					:id_classe_ponto_de_venda,
					:id_uf,
					:id_anfitriao,
					:nome,
					:endereco,
					:telefone,
					:site,
					:data_expiracao,
					:dta,
					:id_anfitriao_master
				)');

				$stmt->bindParam('id_classe_ponto_de_venda', $id_classe_ponto_de_venda);
				$stmt->bindParam('id_uf', $id_uf);
				$stmt->bindParam('id_anfitriao', $id_anfitriao);
				$stmt->bindParam('nome', $nome);
				$stmt->bindParam('endereco', $endereco);
				$stmt->bindParam('telefone', $telefone);
				$stmt->bindParam('site', $site);
				$stmt->bindParam('data_expiracao', $data_expiracao);
				$stmt->bindParam('dta', $dta);
				$stmt->bindParam('id_anfitriao_master', $id_anfitriao_master);

				if ($stmt->execute()) {
					$retorno['codigo'] = 200;
				} else {
					// echo 'false';
				}
			} else {
				// echo 'false';
			}



		}


		echo json_encode($retorno);
	}
?>
