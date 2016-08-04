<?php

/**
 * Class GeraBoleto
 * @version   0.0.1
 * @author    Erick R. Alves Aguiar <ericktatsui@gmail.com>
 * @copyright Erick R. Alves Aguiar
 */

/**
 * Class upload
 * 
 * <b>O que ela faz?</b>
 * 
 * Essa classe é responsável por gerar os números para no final gerar um boleto
 * 
 * <b>Como ela funciona?</b>
 * 
 * escrever aqui detalhes do funcionamento
 * 
 */

class BoletoBradesco
{

    /**
     * Versão do script
     * @access public
     * @var float
     */
    public $version = 0.01;

    /**
     * Nome ou razão social do beneficiário
     * @access private
     * @var string
     */
    private $beneficiario;

    /** 
     * Número de Agência
     * @access private
     * @var integer
     */
    private $agencia;

    /** 
     * Número do Digito da Agência
     * @access public
     * @var integer
     */
    private $agenciaDigito;

    /** 
     * Número da conta
     * @access public
     * @var integer
     */
    private $conta;

    /** 
     * Número do digito da conta
     * @access public
     * @var integer
     */
    private $contaDigito;

    /** 
     * Quantidade
     * @access public
     * @var integer
     */
    public $quantidade;

    /**
     * Número do documento
     * @access public
     * @var integer
     */
    private $numDoc;

    /**
     * Nosso número
     * @access public
     * @var string
     */
    private $nossoNum;

    /**
     * Número do CPF e CNPJ
     * @access public
     * @var integer
     */
    private $cpfCnpj;

    /**
     * Data de vencimento
     * @access public
     * @var string
     */
    public $expDays;

    /**
     * Data de vencimento
     * @access private
     * @var string
     */
    private $vencimento;

    /**
     * Data de vencimento
     * @access private
     * @var float
     */
    private $valor;

    /**
     * Data de vencimento
     * @access private
     * @var float
     */
    private $valorMoeda;

    /**
     * descricao
     * @access public
     * @var string
     */
    public $demonstrativo;

    /**
     * instrucoes
     * @access public
     * @var string
     */
    public $instrucoes;

    /**
     * sacado
     * @access public
     * @var string
     */
    public $sacado;

    /**
     * sacadoDesc
     * @access public
     * @var string
     */
    public $sacadoDesc;

    /**
     * Aceite
     * @access public
     * @var string
     */
    private $aceite;

    /**
     * Espéicie - tipo de moeda
     * @access public
     * @var string
     */
    private $especie;

    /** 
     * Espécie do documento
     * @access public
     * @var string
     */
    private $especieDoc;


    /**
     * Número da carteira
     * @access private
     * @var integer
     */
    private $carteira;

    /**
     * Linha digitavel
     * @access private
     * @var integer
     */
    private $linhaDigitavel;

    /**
     * Arredonda para número multiplo de 10
     *
     * @access public
     * @param string $num - Número para calcular
     */
    private function multiplo10( $num )
    {
        if( ($num % 10) == 0 ){
            return $num;
        }else{
            $num = $num / 10;
            $num = ceil($num);
            $num = $num . 0;
            return (int)$num;
        }
    }

    /**
     * Calcula o digito verificador linha digitavel
     *
     * @access public
     * @param string $num - Número para calcular
     * @param int $base - Tipo de calculo;
     */
    public function modulo10($num)
    {
        $arrNum = array_reverse( str_split($num) );
        $result = 0;
        $baseCount = 2;

        for($i = 0; $i <= count($arrNum) - 1; $i++){
            //echo $result ." = ". (int)$arrNum[$i] ." * ". $baseCount ."<br>";

            $mult = ((int)$arrNum[$i] * $baseCount);
            if( $mult >= 10 ){
                $mult = str_split($mult);
                $mult = (int)$mult[0] + (int)$mult[1];
            }
            $result = $result + $mult;

            if($baseCount == 2){
                $baseCount = 1;
            }else if($baseCount == 1){
                $baseCount = 2;
            }
        }

        return $this->multiplo10( $result ) - $result;
    }
	
    /**
     * Calcula o digito de auto-conferência do nosso número
     *
     * @access public
     * @param string $num - Número para calcular
     * @param int $base - Tipo de calculo;
     */
	private function modulo11($num, $base)
	{
		$arrNum = str_split($num);
		$result = 0;
		$baseCount = 2;

		for($i = 0; $i <= count($arrNum) - 1; $i++){
			$result = $result + ((int)$arrNum[$i] * $baseCount);

			if($i == 0 || $baseCount == 2){
				if($base == 9){
					$baseCount = 10;
				}else{
					$baseCount = 8;
				}
			}
			$baseCount--;
		}

		$result = $result % 11;

		if( $base == 7 ){
            if( $result == 0 ){
                return 0;
            }else{
                $result = 11 - $result;
                if( $result == 10 ){
                    return "P";
                }else{
                    return $result;
                }
            }
        }else if( $base == 9 ){
            $result = 11 - $result;
            if( $result == 0 || $result == 1 || $result > 9 ){
                $result = 1;
            }
            return $result;
        }else{
            return false;
        }
	}

	/**
     * Calcula o digito de auto-conferência do nosso número
     *
     * @access public
     * @param  
     */
    public function geraNossoNum()
    {
        $num = $this->carteira . $this->nossoNum;
        return $this->nossoNum  . $this->modulo11($num, 7);
    }


    /**
     * Calcula o digito do código de barras
     *
     * @access public
     */
    public function digitoCodigoBarras()
    {
        /**
         * 01-03 - numero do banco = 237
         * 04-04 - código da moeda = 9
         * 06-09 - fator de vencimento = ?
         * 10-19 - valor = 0000000300
         * 20-23 - numero da agencia = 6523
         * 24-25 - carteira = 25
         * 26-36 - nosso número = 00000000002 (sem digito)
         * 37-43 - conta = 0002074
         */

        /*    237 9 ? 6251 0000000350 6523 25 00000001000 0002074 0  */
        $num =  "237" . 
                "9" . 
                $this->fatorVencimento() . 
                $this->valor . 
                $this->agencia . 
                $this->carteira . 
                $this->nossoNum . 
                str_pad($this->conta, 7, "0", STR_PAD_LEFT) .
                "0";

        $this->codigoDeBarras = $num;

        return $this->modulo11($num, 9);
    }

    /**
     * Calcula o digito do código de barras
     *
     * @access public
     */
    public function codigoBarras()
    {
        $code = $this->codigoDeBarras;
        $codeDiv[1] = substr($code, 0, 4);
        $codeDiv[2] = substr($code, 4, 43);

        $code = $codeDiv[1] . $this->digitoCodigoBarras() . $codeDiv[2];

        return $code;
    }

    /**
     * Calcula o digito do código de barras
     *
     * @access public
     */
    public function linhaDigitavel()
    {
        $campo[1] = "237" . "9" . $this->agencia . substr($this->carteira, 0, 1);
        $digito[1] = $this->modulo10($campo[1]);

        $campo[2] = substr($this->carteira, 1, 2) . substr($this->nossoNum, 0, 8);
        $digito[2] = $this->modulo10($campo[2]);

        $campo[3] = substr($this->nossoNum, 8, 11) . str_pad($this->conta, 7, "0", STR_PAD_LEFT) . "0";
        $digito[3] = $this->modulo10($campo[3]);

        $campo[4] = $this->digitoCodigoBarras();

        $campo[5] = $this->fatorVencimento() . $this->valor;

        return $campo[1].$digito[1].$campo[2].$digito[2].$campo[3].$digito[3].$campo[4].$campo[5];
    }

    /**
     * Calcula o digito do código de barras
     *
     * @access public
     */
    public function formataLinhaDigitavel()
    {
        $format = "";
        $num = str_split( $this->linhaDigitavel() );
        for($i = 0; $i <= count($num); $i++){
            if( isset($num[$i]) ){
                if( $i == 4 ){
                    $format .= $num[$i] .".";
                }else if( $i == 9 ){
                    $format .= $num[$i] ." ";
                }else if( $i == 14 ){
                    $format .= $num[$i] .".";
                }else if( $i == 20 ){
                    $format .= $num[$i] ." ";
                }else if( $i == 25 ){
                    $format .= $num[$i] .".";
                }else if( $i == 31 ){
                    $format .= $num[$i] ." ";
                }else if( $i == 32 ){
                    $format .= $num[$i] ." ";
                }else{
                    $format .= $num[$i];
                }
            }
        }

        return $format;
    }

    /**
     * Retorna segundos de uma data DD/MM/AAAA
     *
     * @access private
     * @param  string $date
     */
    private function timestamp( $date )
    {
        $dateArr = explode('/', $date);
        return mktime(0,0,0, $dateArr[1], $dateArr[0], $dateArr[2]);
    }

    /**
     * Calcula o digito de auto-conferência do nosso número
     *
     * @access public
     * @param  
     */
    public function fatorVencimento()
    {
        $base = "07/10/1997"; // Data base para calculo do fator de vencimento (padrão)

        $dataVencimento = date('d/m/Y', time() + ($this->expDays * 24 * 60 * 60) ); //calcula data de vencimento retorna em segundos
        $this->vencimento = $dataVencimento;

        $stampBase = $this->timestamp($base); //retorna segundos da data base
        $stampExp = $this->timestamp($dataVencimento); //retorna segundos da data de vencimento

        $calcDate = $stampExp - $stampBase; //calcula os segundos entre a data base e a data de vencimento

        return floor($calcDate / (60 * 60 * 24)); // transforma os segundos em dias
    }

    /**
     * Calcula o digito de auto-conferência do nosso número
     *
     * @access public
     * @param  
     */
    private function valorFormat( $numValue )
    {
        $valorArr = str_split($numValue); //valor to array
        $valorLength = count($valorArr); //tamanho do array
        $valor[1] = substr($numValue, 0, $valorLength - 2); //tira os centavos
        $valor[2] = substr($numValue, -2); //pega somente os centavos
        $valorFormat = (int)$valor[1] .".". (int)$valor[2]; //junta os dois colocando ponto nos centavos
        
        setlocale(LC_MONETARY, 'pt_BR');
        $valorFormat =  money_format('%.2n', $valorFormat);
        $valorFormat = substr($valorFormat, 3); //remove simbolo R$ 

        return $valorFormat;
    }

    /**
     * Calcula o digito de auto-conferência do nosso número
     *
     * @access public
     * @param  
     */
    public function geraCodigoDeBarras()
    {
        $num = $this->codigoBarras();
        $num = str_split($num);
        $numLength = count($num);

        if( ($numLength % 2) != 0 ){
            array_unshift($num, 0);
        }

        //Representação binária dos números de 0 a 9
        $bin = array(
            "00110",
            "10001",
            "01001",
            "11000",
            "00101",
            "10100",
            "01100",
            "00011",
            "10010",
            "01010"
        );
        //echo "<script>console.log('".$bin[0]."')</script>";

        $bar = "";
        $cor = 1; //0 = PRETO // 1 = BRANCO

        for($i = 0; $i < $numLength; $i+=2){
            $number1 = $num[$i];
            $number2 = $num[$i+1];
            $comb = 0 . $bin[$number2] . $bin[$number2];
            echo "<script>console.log('". $number1.$number2 ." = ". $bin[$number1] ." ". $bin[$number2]."')</script>";

            $comb = str_split($comb);

            for($j = 0; $j < count($comb); $j++){

                if( $j == count($comb) - 1 ){
                    $comma = "";
                }else{
                    $comma = ",";
                }
                if( $comb[$j] == "0" && $cor == 0 ){
                    $bar .= "Ep". $comma;
                }else if( $comb[$j] == "0" && $cor == 1 ){
                    $bar .= "Eb". $comma;
                }else if( $comb[$j] == "1" && $cor == 0 ){
                    $bar .= "Lp". $comma;
                }else if( $comb[$j] == "1" && $cor == 1 ){
                    $bar .= "Lb". $comma;
                }

                if( $cor == 0 ){
                    $cor = 1;
                }else{
                    $cor = 0;
                }
            }

            $comb = null;
        }

        //$bar = substr($bar, 0, );

        return explode(",", $bar);
    }










    /**
     * Metodo GET e SET para Beneficiário
     */
    public function setBeneficiario($beneficiario)
    {
        $this->beneficiario = $beneficiario;
    }
    public function getBeneficiario()
    {
        return $this->beneficiario;
    }

    /**
     * Metodo GET e SET para número da agencia
     */
    public function setAgencia($agencia)
    {
        $agencia = str_pad($agencia, 4, "0", STR_PAD_LEFT);
        $this->agencia = $agencia;
    }
    public function getAgencia()
    {
        
        return str_pad($this->agencia, 5, "0", STR_PAD_LEFT);;
    }
    /**
     * Metodo GET e SET para número da agencia
     */
    public function setAgenciaDigito($digito)
    {
        $this->agenciaDigito = $digito;
    }
    public function getAgenciaDigito()
    {
        return $this->agenciaDigito;
    }

    /**
     * Metodo GET e SET para número da conta
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
    }
    public function getConta()
    {
        return $this->conta;
    }
    /**
     * Metodo GET e SET para número da conta
     */
    public function setContaDigito($digito)
    {
        $this->contaDigito = $digito;
    }
    public function getContaDigito()
    {
        return $this->contaDigito;
    }

    /**
     * Metodo GET e SET para número do documento
     */
    public function setNumDoc($numDoc)
    {
        $this->numDoc = $numDoc;
        $this->nossoNum = $numDoc;
    }
    public function getNumDoc()
    {
        return $this->numDoc;
    }

    /**
     * Metodo GET e SET para nosso número
     */
    public function getNossoNum()
    {
        return $this->nossoNum;
    }

    /**
     * Metodo GET e SET para número do documento
     */
    public function setCpfCnpj($cpfCnpj)
    {
        $this->cpfCnpj = $cpfCnpj;
    }
    public function getCpfCnpj()
    {
        return $this->cpfCnpj;
    }

    /**
     * Metodo GET e SET para dias de vencimento
     */
    public function setVencimento($expDays)
    {
        $this->expDays = $expDays;
        $this->fatorVencimento();
    }
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Metodo GET e SET para Valor do boleto
     */
    public function setValor( $valor )
    {
        $this->valorMoeda = $valor; //Seta o valor da forma digitada
        $valor = str_replace(array(".",","), "", $valor);
        $valor = str_pad($valor, 10, "0", STR_PAD_LEFT);
        $this->valor = $valor; //seta o valor sem pontos ou virgula
    }
    public function getValorMoeda()
    {
        return $this->valorFormat($this->valor);
    }

    /**
     * Metodo GET e SET para Carteira
     */
    public function setCarteira($carteira)
    {
        $this->carteira = $carteira;
    }
    public function getCarteira()
    {
        return $this->carteira;
    }

    /**
     * Metodo GET e SET para Linha Digitavel
     */
    public function getLinhaDigitavel(){
        return $this->formataLinhaDigitavel();
    }


}


?>