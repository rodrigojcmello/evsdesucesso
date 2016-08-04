<?php

namespace AppBundle\Command;

use AppBundle\DataFixtures\ORM\LoadProdutoAndTagData;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ImportaMuitosCommand extends ContainerAwareCommand
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('Importa:Muitos')
            ->setDescription('Importa produtos ou atualiza existentes com base na tabela do muitos.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        print "\n\n";

        $load = new LoadProdutoAndTagData();

        $load->load($this->getDoctrine()->getManager());

        print "\n\n";


    }

    public function getDoctrine(){
        $em = $this->getContainer()->get('doctrine');
        return $em;
    }


}
