<?php

require_once( "class/BoletoBradesco.class.php" );

$boleto = new BoletoBradesco();

$boleto->setBeneficiario('Beontop Serviços Digitais ltda'); //Nome ou razão social do beneficiário
$boleto->setAgencia(6523); //Agencia sem digito
$boleto->setAgenciaDigito(5); //Digito agencia
$boleto->setConta(2074); //Conta sem digito
$boleto->setContaDigito(5); //Digito da conta
$boleto->quantidade = 001; //Quantidade

$boleto->setNumDoc('00000001000'); //Número do documento - Max: 11 - OBS: nunca pode repetir
$boleto->setCpfCnpj('05.799.916/0001-77'); //CPF ou CNPJ do beneficiário
$boleto->setVencimento(15); //Dias para vencimento do boleto
$boleto->setValor('3,50'); //Valor
$boleto->setCarteira(25); //Carteira

$boleto->descricao = "Descrição"; //Descrição

$boleto->instrucoes = "Instruções"; //Instruções
$boleto->sacado = "Erick"; //Descrição do sacado
$boleto->sacadoDesc = "Erick Tatsui<br>Rua Tupi, 580<br>São Paulo - SP - CEP: 01233-001"; //Descrição do sacado

require_once( "layout.php" );


?>