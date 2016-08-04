<?php
if ( isset($_POST) ) {
		//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
	 	//====================================================
		$email_remetente = "financeiro@evsdesucesso.com.br"; // deve ser um email do dominio
		//====================================================
	 
	 
		//Configurações do email, ajustar conforme necessidade
		//====================================================
		$email_destinatario = $fields->usuario_email; // qualquer email pode receber os dados
		$email_reply = $fields->usuario_email;
		$email_assunto = "EVS de Sucesso - Instruções de Pagamento";
		//====================================================
	 
	 
		//Variaveis de POST, Alterar somente se necessário
		//====================================================
		$nome = $fields->entidade_nome;
		$cpf = $fields->entidade_endereco;
		$v = $_POST['plano'];
		//====================================================

		switch ($v) {
			case 1:
				$vPlan = "R$29,90 /mês";
				break;

			case 2:
				$vPlan = "R$229,90 /ano";
				break;
			
			default:
				$vPlan = "R$29,90 /mês";
				break;
		}

		//Monta o Corpo da Mensagem
		//====================================================
		$email_conteudo  = $nome .' você se cadastrou no EVS de Sucesso e selecionou o plano de '. $vPlan .'.<br>Para realizar o pagamento clique no link abaixo, você será redirecionado para o boleto onde deverá imprimir e pagar em uma lotérica ou agência bancária.<br><br><a href="http://evsdesucesso.com.br/lib/boleto/?v='. $v .'&c='. $cpf .'">http://evsdesucesso.com.br/lib/boleto/?v='. $v .'&c='. $cpf .'</a><br><br><strong>IMPORTANTE:</strong> Após realizar o pagamento você deve enviar uma foto ou scan do comprovante em resposta a este e-mail para que seja liberado no sistema.<br>Atenciosamente.<br><br><table style="width:700px"><tr><td><img src="http://evsdesucesso.com.br/signature/financeiro.jpg" style="width:700px;display:block;border:0"></td></tr></table>';
	 	//====================================================
	 
		//Seta os Headers (Alerar somente caso necessario)
		//====================================================
		$email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
		//====================================================
	 
	 
		//Enviando o email
		//====================================================
		if (mail($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)){
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