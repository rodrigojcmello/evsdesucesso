<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa			       	  |
// | 																	                                    |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto Bradesco: Ramon Soares						            |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 0;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$valor_cobrado = "2.50"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = "0000145";  // Nosso numero sem o DV - REGRA: Máximo de 11 caracteres!
$dadosboleto["numero_documento"] = $dadosboleto["nosso_numero"];	// Num do pedido ou do documento = Nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = "Nome do seu Cliente";
$dadosboleto["endereco1"] = "Endereço do seu Cliente";
$dadosboleto["endereco2"] = "Cidade - Estado -  CEP: 00000-000";

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "Pagamento referente ao aplicativo Espaço de Sucesso.";
$dadosboleto["demonstrativo2"] = "Mensalidade referente a liberação do aplicativo Espaço de Sucesso.<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"] = "Espaço de Sucesso - http://www.evsdesucesso.com.br";
$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 5% após o vencimento";
$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: contato@espacodesucesso.com.br";
$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "001";
$dadosboleto["valor_unitario"] = $valor_boleto;
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DS";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //



// DADOS DA SUA CONTA - Bradesco
$dadosboleto["agencia"] = "6523"; // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = "4"; // Digito do Num da agencia
$dadosboleto["conta"] = "2074"; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = "5"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - Bradesco
$dadosboleto["conta_cedente"] = "65230002074"; // ContaCedente do Cliente, sem digito (Somente Números)
$dadosboleto["conta_cedente_dv"] = "5"; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = "25";  // Código da Carteira: pode ser 06 ou 03

// SEUS DADOS
$dadosboleto["identificacao"] = "Espaço de Sucesso";
$dadosboleto["cpf_cnpj"] = "12.231.446/0001-06";
$dadosboleto["endereco"] = "Aplicativo para gerenciar seu EVS";
$dadosboleto["cidade_uf"] = "São Paulo / SP";
$dadosboleto["cedente"] = "Espaço de Sucesso";

// NÃO ALTERAR!
include("include/funcoes_bradesco.php");
include("include/layout_bradesco.php");
?>
