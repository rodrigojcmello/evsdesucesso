<?php
if ( isset($_POST) ) {
		//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
	 	//====================================================
		$email_remetente = "contato@evsdesucesso.com.br"; // deve ser um email do dominio
		//====================================================
	 
	 
		//Configurações do email, ajustar conforme necessidade
		//====================================================
		switch ( $_POST['departamento'] ) {
			case 'suporte':
				//$mailTo = "suporte@evsdesucesso.com.br";
				$mailTo = "contato@evsdesucesso.com.br";
				break;
			case 'financeiro':
				//$mailTo = "financeiro@evsdesucesso.com.br";
				$mailTo = "contato@evsdesucesso.com.br";
				break;
			case 'contato':
				//$mailTo = "contato@evsdesucesso.com.br";
				$mailTo = "contato@evsdesucesso.com.br";
				break;
			default:
				$mailTo = "contato@evsdesucesso.com.br";
				break;
		}
		$email_destinatario = $mailTo; // qualquer email pode receber os dados
		$email_reply = $mailTo;
		$email_assunto = "".strtoupper($_POST['departamento'])." - EVS DE SUCESSO";
		//====================================================
	 
	 
		//Variaveis de POST, Alterar somente se necessário
		//====================================================
		$nome = $_POST['nome'];
		$email = $_POST['email'];
		$telefone = $_POST['tel'];
		$assunto = $_POST['departamento'];
		$mensagem = $_POST['mensagem'];
		//====================================================
	 
		//Monta o Corpo da Mensagem
		//====================================================
		$email_conteudo  = "<b>Nome:</b> ".$nome." \n";
		$email_conteudo .= "<b>Email:</b> ".$email." \n";
		$email_conteudo .= "<b>Telefone:</b> ".$telefone." \n";
		$email_conteudo .= "<b>Assunto:</b> ".$assunto." \n";
		$email_conteudo .= "<br><b>Mensagem:</b><br>".$mensagem." \n";
	 	//====================================================
	 
		//Seta os Headers (Alerar somente caso necessario)
		//====================================================
		$email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
		//====================================================
	 
	 
		//Enviando o email
		//====================================================
		if (mail ($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)){
			echo "<script>alert('Enviado com sucesso!');";
			echo "window.location = 'index.php';</script>";
		}

	  	else{
			echo "<script>alert('Erro ao enviar, tente novamente.');";
			echo "window.location = 'index.php';</script>";
		}
		//====================================================
}else {
	echo "<script>alert('Cuidado, preencha os dados corretamente..');";
	echo "window.location = 'index.php';</script>";
}
?>