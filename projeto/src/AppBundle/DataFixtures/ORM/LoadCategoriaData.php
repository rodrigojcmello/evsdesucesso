<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Categoria;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoriaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Categoria[] $categorias */
        $categorias = [
            (new Categoria('Sem categoria')),
            (new Categoria('Disposição'))
                ->addFilha(new Categoria('N. R. G.')),
            (new Categoria('Gerenciamento de peso'))
                ->addFilha(new Categoria('Nutri Soup'))
                ->addFilha(new Categoria('Shake Herbalife')),
            (new Categoria('Hidratação'))
                ->addFilha(new Categoria('Pó para o Preparo de Composto Líquido')),
            (new Categoria('Lanches saudáveis'))
                ->addFilha(new Categoria('Barras de proteína'))
                ->addFilha(new Categoria('Sopa instantânea')),
            (new Categoria('Nutrição complementar'))
                ->addFilha(new Categoria('Fiber Concentrate'))
                ->addFilha(new Categoria('Fiber & Herb'))
                ->addFilha(new Categoria('Fiberbond'))
                ->addFilha(new Categoria('Herbalifeline'))
                ->addFilha(new Categoria('Multivitaminas e minerais'))
                ->addFilha(new Categoria('Protein Powder'))
                ->addFilha(new Categoria('Xtra-Cal')),
            (new Categoria('Nutricão esportiva'))
                ->addFilha(new Categoria('Hydrate'))
                ->addFilha(new Categoria('Prolong'))
                ->addFilha(new Categoria('Rebuild Endurance'))
                ->addFilha(new Categoria('Rebuild Strength')),
            (new Categoria('Nutrição externa'))
                ->addFilha(new Categoria('Herbal Aloe'))
                ->addFilha(new Categoria('Linha Body'))
                ->addFilha(new Categoria('Lively Fragances'))
                ->addFilha(new Categoria('NouriFusion'))
                ->addFilha(new Categoria('Radiant C'))
                ->addFilha(new Categoria('Skin Activator'))
                ->addFilha(new Categoria('Soft Green')),
            (new Categoria('Trial Pack'))
        ];
        foreach ($categorias as $categoriaPai) {
            $manager->persist($categoriaPai);
            foreach ($categoriaPai->getFilhas() as $categoriaFilha) {
                $categoriaFilha->setPai($categoriaPai);
                $manager->persist($categoriaFilha);
            }
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