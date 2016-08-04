<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ClassePontoDeVenda;
use AppBundle\Entity\TabelaPrecos;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTabelaPrecosData extends AbstractFixture implements OrderedFixtureInterface
{
    const DATA_INICIO = '2015-01-01';
    const DATA_FIM    = '2030-12-31';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var ClassePontoDeVenda $classePontoDeVenda */
        $classePontoDeVenda = $manager->getRepository('AppBundle:ClassePontoDeVenda')->findOneBy(['descricao' => 'EVS padrão']);

        $manager->persist((new TabelaPrecos())
            ->setClassePontoDeVenda($classePontoDeVenda)
            ->setDataInicio(new \DateTime(self::DATA_INICIO))
            ->setDataFim(new \DateTime(self::DATA_FIM))
            ->setDescricao(''));
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}