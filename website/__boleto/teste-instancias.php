<?php



$geraBoleto = new BoletoBradesco();
$geraBoleto->setNossoNum('00000000002');
$geraBoleto->setCarteira('25');

echo $geraBoleto->geraNossoNum();

echo "<br>";



$geraBoleto->setVencimento('15'); //15 dias de vencimento
$geraBoleto->setValor('1.251,00');
$geraBoleto->setAgencia('6523'); // Agencia sem digito
$geraBoleto->setConta('2074'); // Agencia sem digito






/**
  * +----------------------+
  * |   CÓDIGO DE BARRAS   |
  * +----------------------+
  */

/**
 * 01-03 - numero do banco = 237
 * 04-04 - código da moeda = 9
 * 05-05 - digito codigo de barras = ?
 * 06-09 - fator de vencimento = ?
 * 10-19 - valor = 0000000300
 * 20-23 - numero da agencia = 6523
 * 24-25 - carteira = 25
 * 26-36 - nosso número = 00000000002 (sem digito)
 * 37-43 - conta = 0002074
 */
echo "2379";
echo $geraBoleto->digitoCodigoBarras();
echo $geraBoleto->fatorVencimento();
echo "0000125100";
echo "6523";
echo "25";
echo "00000000002";
echo "0002074";
echo "0";




echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";









/**
  * +---------------------+
  * |   LINHA DIGITAVEL   |
  * +---------------------+
  */

/**
 * 01-03 - numero do banco = 237
 * 04-04 - código da moeda = 9
 * 05-08 - numero da agencia = 6523
 * 09-09 - carteira - (se for 25 fica só 2)
 * 10-10 - digito verificador do campo
 */
echo "23796.5232". $geraBoleto->modulo10(237965232);
echo "<br>";
/**
 * 01-01 - carteira - (se for 25 fica só 5)
 * 02-10 - nosso número = 000000000.02 (0-8 casas)
 * 11-11 - digito verificador do campo
 */
echo "50000.00000". $geraBoleto->modulo10(5000000000);
echo "<br>";
/**
 * 01-03 - nosso número = 000000000.02 (10-11 casas)
 * 04-09 - conta = 0002074
 * 10-10 - zero (0)
 * 11-11 - digito verificador do campo
 */
echo "02000.20740". $geraBoleto->modulo10(0200020740);
echo "<br>";

// Digito verficador do código de barras
echo $geraBoleto->digitoCodigoBarras();
echo "<br>";

echo $geraBoleto->fatorVencimento() ."0000125100";




echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "23790.0310". $geraBoleto->modulo10(237900310);
echo " ";
echo "40031.77200". $geraBoleto->modulo10(4003177200);
echo " ";
echo "28009.52790". $geraBoleto->modulo10(2800952790);
echo " ";
echo "7";
echo " ";
echo "10010000000000";


echo "<br>";
echo "23790.03102 40031.772003 28009.527905 7 10010000000000";
echo "<br>";
echo "<br>";

echo $geraBoleto->formataLinhaDigitavel( $geraBoleto->linhaDigitavel() );



?>