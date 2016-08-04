<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Categoria;
use AppBundle\Entity\ItemTabelaPrecos;
use AppBundle\Entity\Produto;
use AppBundle\Entity\Tag;
use AppBundle\Entity\TagProduto;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util;
use PHPExcel;
use PHPExcel_IOFactory;


class DadosCargaController extends FOSRestController
{

    /**
     * @Sensio\Route("/admin/carga")
     * @Template()
     */
    public function carga(Request $request)
    {

        return $this->render('AdminBundle:Carga:carga.html.twig');
    }

    /**
     * @Sensio\Route("/admin/carga/confirma", name="carga_confirma")
     */
    public function cargaConfirmar(Request $request)
    {

        $obj = unserialize( $request->get("itens") );

        /*
         *         $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from grade_consumo where id_grade_consumo = $id_grade_consumo";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $result_id_produtos = $statement->fetch();

        try {
            $em = $this->getDoctrine ()->getManager ();
            $removerGradeConsumo = $em->getRepository('AppBundle:GradeConsumo')->findByProduto($result_id_produtos['id_produto']);

            foreach ($removerGradeConsumo as $remover_grades){
                $em->remove($remover_grades);
                $em->flush();
            }
        } catch(\Exception $e){
            Util::log($e->getMessage());
            return new Response($e->getMessage());
        }

         *
         */

        $em = $this->getDoctrine()->getManager();

        foreach ($obj as $item) {


            // identifica categoria
            //

            $cat_nome = trim($item->cat1) ;

            $categoria = $em->getRepository('AppBundle:Categoria')->findOneByNome($cat_nome);

            if (!$categoria) {
                $categoria = new Categoria();
                $categoria->setNome($cat_nome);
            }

            $em->persist( $categoria );

            // verifica existência das tags

            $tags_produto = new ArrayCollection();

            for ( $i = 1 ; $i <= 2 ; $i ++ ) {
                if ($i == 1)
                    $tag_nome = trim($item->cat1) ;
                else
                    $tag_nome = trim($item->cat2) ;


                if ($tag_nome != '') {
                    $tag = $em->getRepository('AppBundle:Tag')->findOneByNome($tag_nome);
                    if (!$tag) {
                        $tag = new Tag();
                        $tag->setNome($tag_nome);
                        $tag->setVisivel(true);
                        $tag->setExibirAutoProdutos(false);
                        $tag->setExibirCategoria(false);
                        $em->persist($tag);
                    }
                    $tags_produto[] = $tag;
                }

            }

            $em->flush();

            // identifica produto pelo sku herbalife (caso exista)

            if ($item->sku_hl !== '')
                $produto = $em->getRepository('AppBundle:Produto')->findOneBySku($item->sku_hl);
            else
                $produto = $em->getRepository('AppBundle:Produto')->findOneBySkuint($item->sku);

            if (!$produto) {
                $produto = new Produto();
            }

            $produto->setSkuint($item->sku);
            $produto->setSku($item->sku_hl);
            $produto->setNome($item->descricao);
            $produto->setDescricao($item->descricao);
            $produto->setApelido($item->apelido);
            $produto->setCategoria($categoria);
            $produto->setVisivel(true);

            $produto->resetTagsProduto();

            foreach ($tags_produto as $tag) {

                $tp = $em->getRepository('AppBundle:TagProduto')->findOneBy( [ 'produto' => $produto , 'tag' => $tag ]);

                if (!$tp) {
                    $tp = new TagProduto();
                    $tp->setProduto($produto);
                    $tp->setTag($tag);
                    $em->persist($tp);
                }

            }

            $produto->resetItensTabelaPreco();

            $tabela_precos_all = $em->getRepository('AppBundle:TabelaPrecos')->findAll();
            $tabela_precos = $tabela_precos_all[0];

            $id_produto = $produto->getId() - 0 ;
            $sql = "DELETE FROM item_Tabela_Precos WHERE id_produto = " . $id_produto ;

            $em->getConnection()->exec( $sql );

            foreach ($item->tabela as $uf => $itemTabela) {
                $uf = $em->getRepository('AppBundle:Uf')->findOneByNome( $uf );
                $itemPreco = new ItemTabelaPrecos();
                $itemPreco->setProduto($produto);
                $itemPreco->setTabelaPrecos($tabela_precos);
                $itemPreco->setUf($uf);
                $itemPreco->setPontoValor(ceil($item->pv));
                $itemPreco->setPrecoAberto(false);
                $itemPreco->setVenda(ceil($item->preco));
                $itemPreco->setCusto25($itemTabela['custo25']);
                $itemPreco->setCusto35($itemTabela['custo35']);
                $itemPreco->setCusto42($itemTabela['custo42']);
                $itemPreco->setCusto50($itemTabela['custo50']);
                $em->persist($itemPreco);
            }

            $em->persist( $produto );


            $em->flush() ;


        }

        return $this->render('AdminBundle:Carga:carga.html.twig', [ "comprovante" => 'Processo executado com sucesso' ]);
    }

    /**
     * @Sensio\Route("/admin/carga/enviar", name="carga_envia")
     */
    public function cargaEnviar(Request $request)
    {

        function valor( $str ) {
            return str_replace( "R$ ", "" , $str);
        }

        $arquivo = ($request->files->get("arquivo_planilha"));

        $doc = PHPExcel_IOFactory::load($arquivo);

        $sheet = $doc->getSheet(0);

        $linhas = $sheet->getHighestRow();

        $items = [];

        for ($nLinha = 3; $nLinha <= $linhas; $nLinha++) {
            $linha = $sheet->rangeToArray("A$nLinha:G$nLinha");
            $area = $linha[0];

            if (count($area) < 5)
                continue;

            if ($area[0] == "")
                continue;

            $item = new \stdClass();
            $item->sku = $area[0];

            $item->sku_hl = $area[1];
            $item->descricao = $area[2];
            $item->apelido = $area[3];
            $item->pv = $area[4];
            $item->cat1 = $area[5];
            $item->cat2 = $area[6];
            $item->tabela = [] ;
            $item->preco = 0 ;

            $items[] = $item;
        }

        $sheet = $doc->getSheet(1);
        $linhas = $sheet->getHighestRow();

        $maxCol = $sheet->getHighestColumn(3);

        $header = $sheet->rangeToArray("A1:$maxCol" . "1");

        for ($nLinha = 3; $nLinha <= $linhas; $nLinha++) {
            $linha = $sheet->rangeToArray("A$nLinha:$maxCol$nLinha");
            $area = $linha[0];

            $sku = $area[0];

            if ($sku == "") {
                continue ;
            }

            $item = null ;
            foreach ($items as $busca) {
                if ($busca->sku == $sku) {
                    $item = $busca;
                    break ;
                }
            }

            if (!$item) {
                // erro
                continue ;
            }

            $item->preco = valor( $area[1] );

            $n = 0;

            $done = false ;

            $col = 2 ;

            $tabela = [];

            while (true) {

                $titulo = $area[ $col ];
                if ( ($titulo = str_replace( ['(R$)','(',')','Preço'] , '', $header[0][ $col ] ) ) == "")
                    break ;


                if ( $area[ $col ] == "")
                    break ;

                $uf = substr( $titulo , -2 );

                $tabela[ $uf ] = [
                                    "custo25" => valor( $area[ $col ] ),
                                    "custo35" => valor( $area[ $col + 1 ] ),
                                    "custo42" => valor( $area[ $col + 2 ] ),
                                    "custo50" => valor( $area[ $col + 3 ] ),
                                 ];

                $col += 4 ;

            }

            $item->tabela = $tabela ;

        }

        $valido = true ;
        $erros = [];

        foreach ($items as $item) {
            if ($item->preco == 0) {
                $valido = false ;
                $erros[] = $item->sku ;

            }
        }

        $objeto = serialize($items);

        $dados = ""; //  print_r($sheet, false);

        return $this->render('AdminBundle:Carga:carga.html.twig', [ "parametro" => 1 , "erros" => $erros, "items" => $objeto, "valido" => $valido,  "itens" => $items, "linhas" => $linhas , "maiorc" => $maxCol ]);
    }


}