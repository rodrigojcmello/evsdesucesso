<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="description">
		<p class="center">Instrucoes de Impressao</p>
		<ul>
			<li>Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (Nao use modo economico).</li>
			<li>Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens minimas a esquerda e a direita do formulario.</li>
			<li>Corte na linha indicada. Nao rasure, risque, fure ou dobre a regiao onde se encontra o codigo de barras.</li>
			<li>Caso nao apareca o codigo de barras no final, clique em F5 para atualizar esta tela.</li>
			<li>Caso tenha problemas ao imprimir, copie a sequencia numerica abaixo e pague no caixa eletronico ou no internet banking:</li>
		</ul>
		<p class="center"><strong>
			Linha Digitavel: <?php echo $boleto->getLinhaDigitavel() ?><br>
			Valor: R$ <?php echo $boleto->getValorMoeda() ?>
		</strong></p>
		<p>
			<img src="assets/pontilhado.png" height="1" width="670">
		</p>
	</div>
	<table class="content-full">
		<tr>
			<td>
				<table class="header">
					<tr>
						<td class="logo">
							<img src="http://evsdesucesso.com.br/land/lib/boleto/imagens/logo.jpg">
						</td>
						<td>
							<span class="content no-height">
								Espaço de Sucesso<br>
								12.231.446/0001-06<br>
								Aplicativo para gerenciar seu EVS<br>
								São Paulo / SP
							</span>
						</td>
					</tr>
					<tr><td height="20" colspan="2"></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="header">
					<tr>
						<td class="logo">
							<img src="assets/logo.jpg" height="40" width="150">
						</td>
						<td>
							<div class="num-logo">237-2</div>
						</td>
						<td>
							<span class="num-boleto"><?php echo $boleto->getLinhaDigitavel() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">Cedente</span>
							<span class="content"><?php echo $boleto->getBeneficiario() ?></span>
						</td>
						<td>
							<span class="title">Agencia/Codigo do Cedente</span>
							<span class="content"><?php echo $boleto->getAgencia() ?>/<?php echo $boleto->getConta() ?>-<?php echo $boleto->getContaDigito() ?></span>
						</td>
						<td width="30">
							<span class="title">Especie</span>
							<span class="content center">R$</span>
						</td>
						<td width="50">
							<span class="title">Quantidade</span>
							<span class="content center"><?php echo $boleto->quantidade ?></span>
						</td>
						<td width="139">
							<span class="title">Nosso numero</span>
							<span class="content right"><?php echo $boleto->getCarteira() ?>/<?php echo $boleto->getNossoNum() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">Numero do documento</span>
							<span class="content"><?php echo $boleto->getNumDoc() ?></span>
						</td>
						<td>
							<span class="title">CPF/CNPJ</span>
							<span class="content"><?php echo $boleto->getCpfCnpj() ?></span>
						</td>
						<td>
							<span class="title">Vencimento</span>
							<span class="content center"><?php echo $boleto->getVencimento() ?></span>
						</td>
						<td width="200">
							<span class="title">Valor documento</span>
							<span class="content right"><?php echo $boleto->getValorMoeda() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">(-) Desconto / Abatimentos</span>
							<span class="content"></span>
						</td>
						<td>
							<span class="title">(-) Outras deducoes</span>
							<span class="content"></span>
						</td>
						<td>
							<span class="title">(+) Mora / Multa</span>
							<span class="content"></span>
						</td>
						<td>
							<span class="title">(+) Outros acrescimos</span>
							<span class="content"></span>
						</td>
						<td width="200">
							<span class="title">(=) Valor cobrado</span>
							<span class="content"></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">Sacado</span>
							<span class="content"><?php echo $boleto->sacado ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data last">
					<tr height="80">
						<td>
							<span class="title">Demonstrativo</span>
							<span class="content">
								<?php echo $boleto->demonstrativo ?>
							</span>
						</td>
						<td>
							<span class="title right">Autenticacao mecanica</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span class="title right">Corte na linha pontilhada</span>
							<img src="assets/pontilhado.png" height="1" width="670">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>




	<table class="content-full">
		<tr>
			<td>

				<table class="header">
					<tr>
						<td class="logo">
							<img src="assets/logo.jpg" height="40" width="150">
						</td>
						<td>
							<div class="num-logo">237-2</div>
						</td>
						<td>
							<span class="num-boleto"><?php echo $boleto->getLinhaDigitavel() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">Local de pagamento</span>
							<span class="content">Pagavel em qualquer Banco até o vencimento</span>
						</td>
						<td width="200">
							<span class="title">Vencimento</span>
							<span class="content right"><?php echo $boleto->getVencimento() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">Cedente</span>
							<span class="content"><?php echo $boleto->getBeneficiario() ?></span>
						</td>
						<td width="200">
							<span class="title">Agencia/Codigo cedente</span>
							<span class="content right"><?php echo $boleto->getAgencia() ?>/<?php echo $boleto->getConta() ?>-<?php echo $boleto->getContaDigito() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td width="120">
							<span class="title">Data do documento</span>
							<span class="content"><?php echo date('d/m/Y') ?></span>
						</td>
						<td width="120">
							<span class="title">No documento</span>
							<span class="content"><?php echo $boleto->getNumDoc() ?></span>
						</td>
						<td>
							<span class="title">Especie doc</span>
							<span class="content"></span>
						</td>
						<td>
							<span class="title">Aceite</span>
							<span class="content"></span>
						</td>
						<td>
							<span class="title">Data processamento</span>
							<span class="content"><?php echo date('d/m/Y') ?></span>
						</td>
						<td width="200">
							<span class="title">Nosso numero</span>
							<span class="content right"><?php echo $boleto->getCarteira() ?>/<?php echo $boleto->getNossoNum() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td width="120">
							<span class="title">Uso do banco</span>
							<span class="content"></span>
						</td>
						<td>
							<span class="title">Carteira</span>
							<span class="content center"><?php echo $boleto->getCarteira() ?></span>
						</td>
						<td>
							<span class="title">Especie</span>
							<span class="content center">R$</span>
						</td>
						<td>
							<span class="title">Quantidade</span>
							<span class="content"><?php echo $boleto->quantidade ?></span>
						</td>
						<td>
							<span class="title">Valor Documento</span>
							<span class="content"><?php echo $boleto->getValorMoeda() ?></span>
						</td>
						<td width="200">
							<span class="title">(=) Valor documento</span>
							<span class="content right"><?php echo $boleto->getValorMoeda() ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td rowspan="6">
							<span class="title">Instrucoes (Texto de responsabilidade do cedente)</span>
							<span class="content"></span>
						</td>
					</tr>
					<tr>
						<td width="200">
							<span class="title">(-) Desconto / Abatimentos</span>
							<span class="content"></span>
						</td>
					</tr>
					<tr>
						<td width="200">
							<span class="title">(-) Outras deducoes</span>
							<span class="content"></span>
						</td>
					</tr>
					<tr>
						<td width="200">
							<span class="title">(+) Mora / Multa</span>
							<span class="content"></span>
						</td>
					</tr>
					<tr>
						<td width="200">
							<span class="title">(+) Outros acrescimos</span>
							<span class="content"></span>
						</td>
					</tr>
					<tr>
						<td width="200">
							<span class="title">(=) Valor cobrado</span>
							<span class="content"></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data">
					<tr>
						<td>
							<span class="title">Sacado</span>
							<span class="content no-height">
								<?php echo $boleto->sacadoDesc ?>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="data last">
					<tr>
						<td>
							<span class="title">Sacador/Avalista</span>
						</td>
						<td>
							<span class="title right">Autenticacao mecanica - <strong>Ficha de Compensacao</strong></span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="code-bar">
								<?php
								//echo $boleto->codigoBarras() ."<br><br>";
									$bar = $boleto->geraCodigoDeBarras();
									for($i = 0; $i < count($bar); $i++){
										if( $bar[$i] == "Eb" ){
											echo '<img src="assets/p.png" width="1" height="50" border="0">';
										}if( $bar[$i] == "Ep" ){
											echo '<img src="assets/b.png" width="1" height="50" border="0">';
										}if( $bar[$i] == "Lb" ){
											echo '<img src="assets/p.png" width="3" height="50" border="0">';
										}if( $bar[$i] == "Lp" ){
											echo '<img src="assets/b.png" width="3" height="50" border="0">';
										}
									}
								?>
								<!--br>
								<div>
									<img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="3" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="3" height="50" border="0"><img src="assets/b.png" width="1" height="50" border="0"><img src="assets/p.png" width="1" height="50" border="0">
								</div-->
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span class="title right">Corte na linha pontilhada</span>
							<img src="assets/pontilhado.png" height="1" width="670">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</body>
</html>