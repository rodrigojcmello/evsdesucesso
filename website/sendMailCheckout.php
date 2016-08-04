<?php
if ( isset($_POST) ) {
		//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
	 	//====================================================
		$email_remetente = "contato@evsdesucesso.com.br"; // deve ser um email do dominio
		//====================================================
	 
	 
		//Configurações do email, ajustar conforme necessidade
		//====================================================
		$mailTo = "contato@evsdesucesso.com.br";
		$email_destinatario = $mailTo; // qualquer email pode receber os dados
		$email_reply = $mailTo;
		$email_assunto = "Um cliente se cadastrou - EVS de Sucesso";
		//====================================================
	 
	 
		//Variaveis de POST, Alterar somente se necessário
		//====================================================
		$nomeC = $_POST['entidade_nome'];
		$emailC = $_POST['usuario_email'];
		$telefoneC = $_POST['usuario_telefone'];
		$dataExpiracaoC = $dataExpiracao;
		//====================================================
	 
		//Monta o Corpo da Mensagem
		//====================================================
		$email_conteudo  = "<b>Nome:</b> ".$nomeC." \n";
		$email_conteudo .= "<b>Email:</b> ".$emailC." \n";
		$email_conteudo .= "<b>Telefone:</b> ".$telefoneC." \n";
		$email_conteudo .= "<b>Data de Expiração:</b> ".$dataExpiracaoC." \n";
	 	//====================================================
	 
		//Seta os Headers (Alerar somente caso necessario)
		//====================================================
		$email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
		//====================================================
	 
	 
		//Enviando o email
		//====================================================
		if (mail ($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)){
			//echo "<script>alert('Enviado com sucesso!');";
			//echo "window.location = 'index.php';</script>";
		}

	  	else{
			//echo "<script>alert('Erro ao enviar, tente novamente.');";
			//echo "window.location = 'index.php';</script>";
		}
		//====================================================
}else {
	//echo "<script>alert('Cuidado, preencha os dados corretamente..');";
	//echo "window.location = 'index.php';</script>";
}
?>