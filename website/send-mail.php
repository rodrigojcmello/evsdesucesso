<?php

/* VALORES RECEBIDOS DO FORMULÁRIO */
//================================================
$nome = $_POST['entidade_nome'];
$phone = $_POST['usuario_telefone'];
$email = $_POST['usuario_email'];
$senha = $_POST['usuario_senha'];
$genero = $_POST['usuario_genero_id'];
$cpf = $_POST['entidade_numero'];
$entidade_endereco = $_POST['entidade_endereco'];
$bairro = $_POST['entidade_bairro'];
$estado = $_POST['estado'];
$cidade = $_POST['cidade'];
$foto;
$dataExpiracao;
//================================================

// DATA E HORA
//=====================
$data = date('d/m/Y');
$hora = date('H:i:s');
//=====================


// Verifica se todos campos foram preenchidos
//=======================================================
if ($nome && $phone && $email && $senha && $genero && $cpf && $entidade_endereco && $bairro && $estado && $cidade && $foto && $dataExpiracao && $senha && $foto) {
	$confirmFields = true;
}
else {
	$confirmFields = false;
}
//=======================================================

if ( $confirmFields ) {
	//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
	//======================================================
	$email_remetente = "financeiro@evsdesucesso.com.br";
	//====================================================== 

	
	//Configurações do email, ajustar conforme necessidade
	//====================================================
	$email_destinatario = "elinahum.br@gmail.com"; //  qualquer email pode receber os dados
	$email_reply = $email;
	$email_assunto = "Novo Cadastro - " . ucwords($nome);
	//==================================================== 	

	 
	//Monta o Corpo da Mensagem
	//====================================================
	$email_conteudo .= "<b>Nome: </b>" . $nome . "\n";
	$email_conteudo .= "<b>Telefone: </b>" . $phone . "\n";
	$email_conteudo .= "<b>Email: </b>" . $email . "\n";
	$email_conteudo .= "<b>Senha: </b>" . $senha . "\n";
	$email_conteudo .= "<b>Genero: </b>" . $genero . "\n";
	$email_conteudo .= "<b>CPF: </b>" . $cpf . "\n";
	$email_conteudo .= "<b>Endereço de EVS: </b>" . $entidade_endereco . "\n";
	$email_conteudo .= "<b>Bairro: </b>" . $bairro . "\n";
	$email_conteudo .= "<b>Estado: </b>" . $estado . "\n";
	$email_conteudo .= "<b>Cidade: </b>" . $cidade . "\n";
	$email_conteudo .= "<b>Data: </b>" . $data . "\n";
	$email_conteudo .= "<b>Horário: </b>" . $hora . "\n";
	//====================================================

	 
	//Seta os Headers (Alerar somente caso necessario)
	//====================================================
	$email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
	//====================================================
	 
	 
	//Enviando o email
	//====================================================
	if (mail ($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)){
		//echo "Enviado com sucesso!";
	}
	else{
		//echo "Erro ao enviar, tente novamente.";

	}
	//====================================================
}
else {
	//echo "Preencha todos os campos corretamente";
}

?>