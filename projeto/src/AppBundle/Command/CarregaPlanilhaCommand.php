<?php

namespace AppBundle\Command;

use AppBundle\Entity\Categoria;
use AppBundle\Entity\Produto;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

require __dir__ . "/../../../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";

class CarregaPlanilhaCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('carrega:excel')
            ->setDescription('Carga de dados de produtos')
            ->addArgument('arquivo', InputArgument::OPTIONAL, 'Nome da planilha');


    }

    protected function mostra($obj) {

        print_r($obj);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $arquivo = $input->getArgument('arquivo');

        if (!$arquivo)
            $arquivo = "/tmp/hl.xlsx";

        print "\nCarregando arquivo: " . $arquivo . "\n";

        $xlsdoc = \PHPExcel_IOFactory::load($arquivo);

        // primeira aba - produtos, descrições e tags principais

        $sheet = $xlsdoc->getSheet(0) ;

        $maiorLinha = $sheet->getHighestRow();

        $em = $this->getDoctrine()->getManager();

        for ($nLinha = 3; $nLinha <= $maiorLinha; $nLinha ++ ) {

            $area = $sheet->rangeToArray("A$nLinha:F$nLinha");

            $reg = $area[0];

            print "linha: $nLinha";

            if ($reg[0] == "") break ;

            $nome_categoria = $reg[ 4 ];

            $categoria = $em->getRepository('AppBundle:Categoria')->findOneByNome( $nome_categoria );

            print_r($reg) ;

            if ( !$categoria ) {
                $categoria = new Categoria();

                $categoria->setNome( $nome_categoria );

                $em->persist( $categoria );
                $em->flush() ;

            }

            $produto = $em->getRepository('AppBundle:Produto')->findOneBySku( $reg[0] );

            if (!$produto) {

                print "SKU nao localizado " . $reg[0] . "\nCriando novo\n\n";

                $produto = new Produto();

                $produto->setSku( $reg[0] );

            }

            $produto->setNome( $reg[2] );
            $produto->setDescricao( $reg[2] );
            $produto->setApelido( $reg[3] );
            $produto->setVisivel( true );

            $produto->setCategoria( $categoria );


            $produto = $em->getRepository('AppBundle:Tag')->findOneByNome( $reg[ 5 ] );


            $em->persist( $produto );






        }

        $em->flush() ;





    }

    public function getDoctrine(){
        $em = $this->getContainer()->get('doctrine');
        return $em;
    }
}