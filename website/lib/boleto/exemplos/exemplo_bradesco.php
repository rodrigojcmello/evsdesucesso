<?php
    header('Content-type: text/html; charset=utf-8');
    include '../OB_init.php';

    $ob = new OB('237');

    //*
    $ob->Vendedor
            ->setAgencia('6523')
            ->setConta('2074')
            ->setCarteira('25')
            ->setRazaoSocial('EVS De Sucesso')
            ->setCpf('012.231.446/0001-06')
            ->setEndereco('Rua Tupi 580 - Sala 4 - Santa Cecília, São Paulo/SP CEP 01223-001')
            ->setEmail('contato@evsdesucesso.com.br')
			//->setCodigoCedente('0403005')
            ->setCodigoCedente('4672471')
			->setInsertDVAtPosFour(false)
        ;

    $ob->Configuracao
            ->setLocalPagamento('Pagável em qualquer banco até o vencimento')
        ;

    $ob->Template
            ->setTitle('Boleto EVS de Sucesso')
            ->setTemplate('html5')
        ;

    $ob->Cliente
            ->setNome('Maria Joelma Bezerra de Medeiros')
            ->setCpf('111.999.888-39')
            ->setEmail('mariajoelma85@hotmail.com')
            ->setEndereco('')
            ->setCidade('')
            ->setUf('')
            ->setCep('')
        ;

    $ob->Boleto
            ->setValor(1.00)
            //->setDiasVencimento(5)
            ->setVencimento(6,9,2011)
            ->setNossoNumero('75896452')
            ->setNumDocumento('27.030195.10')
            ->setQuantidade(1)
        ;

    $ob->render(); /**/
