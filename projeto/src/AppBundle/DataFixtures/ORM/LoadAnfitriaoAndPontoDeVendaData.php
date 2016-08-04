<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Anfitriao;
use AppBundle\Entity\ClassePontoDeVenda;
use AppBundle\Entity\PontoDeVenda;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAnfitriaoAndPontoDeVendaData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    const ANFITRIAO_COUNT      = 5;
    const PONTO_DE_VENDA_COUNT = 1;

    /** @var ContainerInterface $container */
    private $container;

    /** @var Generator */
    private $faker;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var ClassePontoDeVenda $classePontoDeVenda */
        $classePontoDeVenda = $manager->getRepository('AppBundle:ClassePontoDeVenda')->findOneBy(['descricao' => 'EVS padrão']);

        // criamos primeiro os pontos de venda
        for ($i=0; $i<self::PONTO_DE_VENDA_COUNT; $i++) {
            $pontoDeVenda = (new PontoDeVenda())
                ->setClassePontoDeVenda($classePontoDeVenda)
                ->setEndereco($this->getFaker()->address)
                ->setNome($this->getFaker()->name)
                ->setSite(strtolower($this->getFaker()->url))
                ->setTelefone($this->getFaker()->phoneNumber)
                ->setUf($manager->getRepository('AppBundle:Uf')->findOneBy(['nome' => 'SP']));
            $manager->persist($pontoDeVenda);
        }
        $manager->flush();

        /** @var PontoDeVenda[] $pontosDeVenda */
        $pontosDeVenda = $manager->getRepository('AppBundle:PontoDeVenda')->findAll();

        $faixas = [ 25, 35, 42, 50 ];

        // depois criamos os anfitrioes
        for ($i=0; $i<self::ANFITRIAO_COUNT; $i++) {

            /** @var Anfitriao $anfitriao */
            $anfitriao = $this->getUserManager()->createUser();
            $anfitriao->setEmail(strtolower($this->getFaker()->email));
            $anfitriao->setUsername($this->getFaker()->userName);
            $anfitriao->setNome($this->getFaker()->name);
            $anfitriao->setFaixa($faixas[rand(0, count($faixas)-1)]);
            $anfitriao->setEndereco($this->getFaker()->address);
            $anfitriao->setPlainPassword('12345678');
            $anfitriao->setEnabled(true);
            $anfitriao->setPontoDeVenda($pontosDeVenda[rand(0, count($pontosDeVenda)-1)]);
            $manager->persist($anfitriao);
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @return Generator
     */
    private function getFaker()
    {
        if (!$this->faker) {
            $this->faker = Factory::create($this->container->getParameter('locale'));
        }

        return $this->faker;
    }

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }
}