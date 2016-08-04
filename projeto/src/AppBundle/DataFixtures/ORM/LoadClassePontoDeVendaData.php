<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ClassePontoDeVenda;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadClassePontoDeVendaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist((new ClassePontoDeVenda())->setDescricao('EVS padrão'));
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}