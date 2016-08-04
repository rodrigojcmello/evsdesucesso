<?php
    header('Content-type: text/html; charset=utf-8');
    include 'Trate.class.php';
    include 'OB_init.php';

    switch ($_GET['v']) {
        case 1:
            $valor = 29.90;
            break;

        case 2:
            $valor = 229.90;
            break;
        
        default:
            $valor = 29.90;
            break;
    }

    $treat = new Treat();
    $ob = new OB('237');

    $ob->Vendedor
        ->setAgencia('6523')
        ->setConta('2074')
        ->setCarteira('25')
        ->setRazaoSocial('EVS De Sucesso')
        ->setCnpj('12.231.446/0001-06')
        ->setEndereco('Rua Tupi 580 - Sala 4 - Santa Cecília, São Paulo/SP CEP 01223-001')
        ->setEmail('contato@evsdesucesso.com.br')
        //->setCodigoCedente('0403005')
        //->setInsertDVAtPosFour(false)
    ;

    $ob->Configuracao
        ->setLocalPagamento('Pagável em qualquer banco até o vencimento')
    ;

    $ob->Template
        ->setTitle('Boleto EVS de Sucesso')
        ->setTemplate('html5')
    ;

    $ob->Cliente
        //->setNome($_GET['n'])
        ->setCpf( $treat->cpf( $_GET['c'] ) )
        //->setEmail($_GET['e'])
        //->setEndereco('')
        //->setCidade('')
        //->setUf('')
        //->setCep('')
    ;

    $ob->Boleto
        ->setValor( $valor )
        ->setDiasVencimento(31)
        //->setVencimento(6,9,2011)
        ->setNossoNumero('75896452')
        ->setNumDocumento('27.030195.10')
        ->setQuantidade(1)
    ;

    $ob->render();