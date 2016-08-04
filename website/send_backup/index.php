<?php		
	require_once 'send_backup/class/Connect.class.php';
	
	$dataBase = new Connect();
	$dataBase->conection();
	$dataBase->query = "INSERT INTO form_checkout (
							entidade_tipo_id,
							entidade_nome,
							entidade_numero,
							entidade_endereco,
							entidade_bairro,
							entidade_estado,
							entidade_cidade,
							entidade_data_expiracao,
							usuario_nome,
							usuario_email,
							usuario_senha,
							usuario_genero_id,
							usuario_telefone,
							usuario_foto
						) 
						VALUES (
							'$_POST[entidade_tipo_id]',
							'$_POST[entidade_nome]',
							'$_POST[entidade_numero]',
							'$_POST[entidade_endereco]',
							'$_POST[entidade_bairro]',
							'$_POST[estado]',
							'$_POST[cidade]',
							'$dataExpiracao',
							'$_POST[usuario_nome]',
							'$email',
							'$senha',
							'$_POST[usuario_genero_id]',
							'$_POST[usuario_telefone]',
							'$foto'
						)";

	$dataBase->insert();
?>