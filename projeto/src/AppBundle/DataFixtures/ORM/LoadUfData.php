<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Uf;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUfData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $ufs = [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO',
        'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI',
        'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO',
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i<count($this->ufs); $i++) {
            $manager->persist((new Uf())->setNome($this->ufs[$i]));
        }
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