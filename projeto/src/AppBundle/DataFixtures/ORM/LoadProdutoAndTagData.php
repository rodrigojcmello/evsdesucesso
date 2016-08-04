<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Categoria;
use AppBundle\Entity\ClassePontoDeVenda;
use AppBundle\Entity\ItemTabelaPrecos;
use AppBundle\Entity\Produto;
use AppBundle\Entity\TabelaPrecos;
use AppBundle\Entity\Uf;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;

class LoadProdutoAndTagData extends AbstractFixture implements OrderedFixtureInterface
{
    const DATA_INICIO = '2015-01-01';

    /** @var Client */
    private $client;

    /** @var array */
    private $mappings = [
        'N. R. G.'                  => [ 'n.r.g.'],
        'Nutri Soup'                => [ 'nutri' ],
        'Shake Herbalife'           => [ 'shake' ],
        'Barras de proteína'        => [ 'barra de proteína' ],
        'Sopa instantânea'          => [ 'sopa instantânea' ],
//        'Fiber Concentrate'         => [ 'fiber concentrate' ],
        'Fiber & Herb'              => [ 'fiber & herb' ],
//        'Fiberbond'                 => [ 'fiberbond' ],
        'Herbalifeline'             => [ 'herbalifeline' ],
        'Multivitaminas e minerais' => [ 'multi' ],
        'Nutrição complementar'     => [ 'xtra', 'fiberbond', 'fiber concentrate' ],
        'Linha 24Hr'                   => [ '24 hours' ],
//        'Prolong'                   => [ '24 hours prolong' ],
//        'Rebuild Endurance'         => [ '24 hours rebuild endurance' ],
//        'Rebuild Strength'          => [ '24 hours rebuild strength' ],
        'Herbal Aloe'               => [ 'herbal aloe' ],
        'NouriFusion'               => [ 'loção', 'esfoliante', 'tônico', 'noturno', 'contorno', 'máscara', 'fps' ],
        'Skin Activator'            => [ 'skin activator' ],
        'Soft Green'                => [ 'sabonete em barra', 'creme hidratante', 'desodorante', 'óleo de banho', 'gel anti' ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var ClassePontoDeVenda $classePontoDevenda */
        $classePontoDeVenda = $manager->getRepository('AppBundle:ClassePontoDeVenda')->findOneBy(['descricao' => 'EVS padrão']);

        /** @var TabelaPrecos $tabelaPrecos */
        $tabelaPrecos = $manager->getRepository('AppBundle:TabelaPrecos')->findOneBy(['classePontoDeVenda' => $classePontoDeVenda, 'dataInicio' => new \DateTime(self::DATA_INICIO)]);

        /** @var Uf[] $ufs */
        $ufs = $manager->getRepository('AppBundle:Uf')->findAll();

        print "\n";

        foreach ($ufs as $uf) {

            $html = $this
                ->getHttpClient()
                ->get(sprintf('http://www.muitos.com/apoio/tabela.php?fonte=4&UF=%s', strtoupper($uf->getNome())))
                ->getBody()
                ->getContents();

            print "Carregando [".$uf->getNome()."]\n";

            preg_match_all('/
                <tr.*?>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                    <td.*?><font.*?>(.*?)<\/td>
                <\/tr>/ix', $html, $matches);

            for ($i=0; $i<count($matches[0]); $i++) {

                $nome = iconv('ISO-8859-1', 'UTF-8', $matches[2][$i]);
                $categoria = $this->getCategoria($manager, $nome);

                if (!$categoria) {
                    print "\nCategoria não encontrada: $nome \n";

                    die ;

                }
                else {
//                    print "\nCategoria $nome ok \n";
                }

                $produto = $manager->getRepository('AppBundle:Produto')->findOneBy(['sku' => $matches[1][$i]]);
                if (!$produto) {
                    $produto = (new Produto())
                        ->setSku($matches[1][$i])
                        ->setNome($nome)
                        ->setDescricao('')
                        ->setVisivel('t')
                        ->setCategoria($categoria);
                    $manager->persist($produto);
                }

                $itemTabelaPrecos = (new ItemTabelaPrecos())
                    ->setUf($uf)
                    ->setPontoValor(str_replace(',', '.', $matches[3][$i]))
                    ->setVenda(str_replace(',', '.', $matches[4][$i]))
                    ->setCusto25(str_replace(',', '.', $matches[5][$i]))
                    ->setCusto35(str_replace(',', '.', $matches[6][$i]))
                    ->setCusto42(str_replace(',', '.', $matches[7][$i]))
                    ->setCusto50(str_replace(',', '.', $matches[8][$i]))
                    ->setTabelaPrecos($tabelaPrecos)
                    ->setProduto($produto);
                $manager->persist($itemTabelaPrecos);
            }
            $manager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @param ObjectManager $manager
     * @param string        $nomeProduto
     *
     * @return Categoria
     */
    private function getCategoria(ObjectManager $manager, $nomeProduto)
    {

//        print "\nMapeando $nomeProduto\n";

        foreach ($this->mappings as $nomeCategoria => $palavras) {
            foreach ($palavras as $palavra) {
                if (false !== stripos($nomeProduto, $palavra)) {
//                    print "\nPalavra localizada na matriz : $nomeProduto <- -> $palavra | nomeCategoria = $nomeCategoria\n";

                    $ret = $manager->getRepository('AppBundle:Categoria')->findOneBy([ 'nome' => $nomeCategoria ]);

                    if (!$ret) {
                       print "\n### Falhou para busca [produto = $nomeProduto] [categoria = $nomeCategoria] \n";
                        die;
                        $manager->persist((new Categoria())->setNome($nomeCategoria));

                    }

                    return $ret ;
                }
                else {
//                    print "\nNope comparando : $nomeProduto <- -> $palavra\n";
                }
            }
        }

        return $manager->getRepository('AppBundle:Categoria')->findOneBy([ 'nome' => 'Sem categoria' ]);
    }

    /**
     * @return Client
     */
    private function getHttpClient()
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * @param string $nomeProduto
     *
     * @return array
     */
    private function generateTags($nomeProduto)
    {
        $nomeProduto = mb_strtolower($nomeProduto);
        $nomeProduto = preg_replace('/[®&().]/', '', $nomeProduto);
        $nomeProduto = preg_replace('/[\s+-]/', ' ', $nomeProduto);
        $nomeProduto = trim($nomeProduto);

        return array_unique(array_filter(array_map(function($part) {
            return trim($part);
        }, mb_split('\b(\s+|a|as|com|da|de|dos|e|em|o|os|para)\b', $nomeProduto))));
    }
}