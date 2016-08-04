<?php

/* VALORES RECEBIDOS DO FORMULÁRIO */
//================================================
$nomeTwo = $_POST['entidade_nome'];
$emailTwo = $_POST['usuario_email'];
//================================================

// Verifica se todos campos foram preenchidos
//=======================================================
if ($emailTwo) {
	$confirmFields = true;
}
else {
	$confirmFields = false;
}
//=======================================================

if ( $confirmFields ) {
	//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
	//======================================================
	$email_remetente = "contato@evsdesucesso.com.br";
	//====================================================== 

	
	//Configurações do email, ajustar conforme necessidade
	//====================================================
	$email_destinatario = $emailTwo; //  qualquer email pode receber os dados
	$email_reply = $email;
	$email_assunto = "Bem Vindo ao Sistema EVS de Sucesso!";
	//==================================================== 	

	 
	//Monta o Corpo da Mensagem
	//====================================================
	/* Código do email minificado */
	$email_conteudo = "<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><title>Bem Vindo ao Sistema EVS de Sucesso!</title></head><body style=\"margin: 0; padding: 0;\"><table style=\"margin: 0 auto;\"><tr><td><a href=\"http://evsdesucesso.com.br\" target=\"_blank\"><img src=\"http://evsdesucesso.com.br/thank-you/img/header-1.jpg\" style=\"display: block;\"></a><a href=\"mailto:contato@evsdesucesso.com.br\" target=\"_blank\"><img src=\"http://evsdesucesso.com.br/thank-you/img/text-1.jpg\" style=\"display: block;\"></a><a href=\"https://play.google.com/store/apps/details?id=br.com.evsdesucesso\" target=\"_blank\"><img src=\"http://evsdesucesso.com.br/thank-you/img/appDown-1.jpg\" style=\"display: block;\"></a><a href=\"http://evsdesucesso.com.br\" target=\"_blank\"><img src=\"http://evsdesucesso.com.br/thank-you/img/footer-1.jpg\" style=\"display: block;\"></a></td></tr></table></body></html>";
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