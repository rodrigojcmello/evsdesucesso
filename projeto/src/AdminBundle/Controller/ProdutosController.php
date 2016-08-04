<?php

namespace AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\FileBag;
use AppBundle\Util;
use AppBundle\Entity\Tag;
use AppBundle\Entity\TagProduto;
use AppBundle\Entity\Produto;
use AppBundle\Entity\ProdutoImagem;
use AppBundle\Entity\ItemTabelaPrecos;

class ProdutosController extends FOSRestController {
    /**
     * @Sensio\Route("/admin/produtos", name="admin_produtos")
     * @Template()
     */

    public function produtosAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from categoria order by nome asc";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['categorias'] = $statement->fetchAll();

        $cmd = "select * from uf order by nome asc";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['uf'] = $statement->fetchAll();

        $cmd = "select * from tag order by nome asc";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['tags'] = $statement->fetchAll();

        return $d;
    }

    /**
     * @Route("/admin/buscar-produtos", name="buscar_produtos")
     * @Method("POST")
     */
    public function getProdutosAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "SELECT
                    pi.imagem AS imagem,
                    c.nome AS nome_categoria,
                    p.nome AS nome,
                    p.apelido AS apelido,
                    p.sku AS sku,
                    p.visivel AS visivel,
                    p.quantidade_estrelas AS quantidade_estrelas,
                    p.id_produto AS id_produto
                FROM
                    produto AS p
                LEFT JOIN produto_imagem AS pi ON pi.id_produto = p.id_produto

                JOIN categoria AS c ON c.id_categoria = p.id_categoria";

        $wlist = array();
        if(($nome_produto = $request->get('nome_produto')) != ''){
            $wlist[] = " LOWER(p.nome) LIKE LOWER('%$nome_produto%')";
        }

        $params = array("query" => $cmd , "where" => $wlist);

        $util = new Util($connection);
        $output = $util->dataTableSource($request, $params);

        return new Response($output);
    }

    /**
     * @Route("/admin/remover-produto", name="admin_remover_produto")
     * @Method("POST")
     */
    public function removerProdutoAction(Request $request){
        $id_produto = $request->get('id');

        $em        = $this->getDoctrine()->getManager();
        $temVendas = $em->getRepository('AppBundle:VendaProduto')->findOneByProduto($id_produto);

        if(empty($temVendas)){
            try {
                $em = $this->getDoctrine()->getManager();
                $removerItemTabelaPrecos = $em->getRepository('AppBundle:ItemTabelaPrecos')->findByProduto($id_produto);
                foreach ($removerItemTabelaPrecos as $remover_itens){
                    $em->remove($remover_itens);
                    $em->flush();
                }
            } catch(\Exception $e){
                Util::log($e->getMessage());
                return new Response($e->getMessage());
            }

            try {
                $em = $this->getDoctrine()->getManager();
                $removerProdutoImagem = $em->getRepository('AppBundle:ProdutoImagem')->findOneByProduto($id_produto);
                if(!empty($removerProdutoImagem)){
                    $em->remove($removerProdutoImagem);
                    $em->flush();
                }
            } catch(\Exception $e){
                Util::log($e->getMessage());
                return new Response($e->getMessage());
            }

            try {
                $em = $this->getDoctrine()->getManager();
                $removerProduto = $em->getRepository('AppBundle:Produto')->find($id_produto);
                if(!empty($removerProduto)){
                    $em->remove($removerProduto);
                    $em->flush();
                }
            } catch(\Exception $e){
                Util::log($e->getMessage());
                return new Response($e->getMessage());
            }

            return new Response();
        } else {
            return new Response(2);
        }
    }

    /**
     * @Route("/admin/gravar-produto", name="admin_gravar_produto")
     * @Method("POST")
     */
    public function gravarProdutoAction(Request $request) {
        parse_str($request->request->get('dados'), $searcharray);

        $em = $this->getDoctrine()->getManager();

        if (isset($searcharray['id_produto'])) {
            $id_produto = $searcharray['id_produto'];
        } else {
            $id_produto = false;
        }

        // // dump($searcharray['ipt_id_categoria_produto']);
        // dump($searcharray);
        // die;

        $nome = isset($searcharray['ipt_nome_produto']) ? $searcharray['ipt_nome_produto'] : false;
        $id_categoria = isset($searcharray['ipt_id_categoria_produto']) ? $searcharray['ipt_id_categoria_produto'] : false;
        $ean = isset($searcharray['ipt_ean_produto']) ? $searcharray['ipt_ean_produto'] : false;
        $sku = isset($searcharray['ipt_sku_produto']) ? $searcharray['ipt_sku_produto'] : false;
        $descricao = isset($searcharray['ipt_descricao_produto']) ? $searcharray['ipt_descricao_produto'] : false;
        $apelido = isset($searcharray['ipt_apelido_produto']) ? $searcharray['ipt_apelido_produto'] : false;
        $visivel = isset($searcharray['ipt_visivel_produto']) ? $searcharray['ipt_visivel_produto'] : false;
        $quantidade_estrelas = isset($searcharray['ipt_quantidade_estrelas_produto']) ? $searcharray['ipt_quantidade_estrelas_produto'] : false;
        $tags_produto = isset($searcharray['ipt_tags_produto']) ? $searcharray['ipt_tags_produto'] : false;

        if($visivel == '')             $visivel             = 'false';
        if($quantidade_estrelas == '') $quantidade_estrelas = null;

        try {
            if($id_produto) {
                $item = $em->getRepository('AppBundle:Produto')->find($id_produto);
            } else {
                $item = new Produto();
            }

            $item->setNome($nome);
            // dump($id_categoria);
            // die;
            $item->setCategoria($em->getRepository('AppBundle:Categoria')->find($id_categoria));
            $item->setEan($ean);
            $item->setSku($sku);
            $item->setDescricao($descricao);
            $item->setVisivel($visivel);
            $item->setApelido($apelido);
            $item->setQuantidadeEstrelas($quantidade_estrelas);

            $em->persist($item);
            $em->flush();

            $id_produto = $item->getId();

            $oldTags = $item->getTagsProduto();
            foreach($oldTags as $tag){
                $em->remove($tag);
                $em->flush();
            }

            if(is_array($tags_produto)){
                foreach($tags_produto as $id_tag){
                    $itemTag = new TagProduto();

                    $itemTag->setProduto($item);
                    $itemTag->setTag($em->getRepository('AppBundle:Tag')->find($id_tag));

                    $em->persist($itemTag);
                    $em->flush();
                }
            }
        } catch(\Exception $e){
            Util::log($e->getMessage());
            return new Response($e->getMessage());
        }

        for($i=1;$i<=27;$i++){
            $ponto_valor = $searcharray['ipt_ponto_valor_' . $i];
            if($ponto_valor == '') $ponto_valor = null;

            $venda = $searcharray['ipt_venda_' . $i];
            if($venda == '') $venda = null;

            $custo25 = $searcharray['ipt_custo25_' . $i];
            if($custo25 == '') $custo25 = null;

            $custo35 = $searcharray['ipt_custo35_' . $i];
            if($custo35 == '') $custo35 = null;

            $custo42 = $searcharray['ipt_custo42_' . $i];
            if($custo42 == '') $custo42 = null;

            $custo50 = $searcharray['ipt_custo50_' . $i];
            if($custo50 == '') $custo50 = null;

            $preco_aberto = $aceita_cartela = $credito_cartela = false;
            if (isset($searcharray['ipt_preco_aberto_' . $i])) {
                $preco_aberto = $searcharray['ipt_preco_aberto_' . $i] !== null ? true : false;
            }
            if (isset($searcharray['ipt_aceita_cartela_' . $i])) {
                $aceita_cartela = $searcharray['ipt_aceita_cartela_' . $i] !== null ? true : false;
            }
            if (isset($searcharray['ipt_cred_cartela_' . $i])) {
                $credito_cartela = $searcharray['ipt_cred_cartela_' . $i] !== null ? true : false;
            }

            try {
                $precos = $em->getRepository('AppBundle:ItemTabelaPrecos')->findOneBy(array('produto' => $id_produto, 'uf' => $i));

                if(!empty($precos)){
                    $precos->setVenda($venda);
                    $precos->setAceitaCartelaDigital($aceita_cartela);
                    $precos->setCreditoCartela($credito_cartela);
                    $precos->setCusto25($custo25);
                    $precos->setCusto35($custo35);
                    $precos->setCusto42($custo42);
                    $precos->setCusto50($custo50);
                    $precos->setPontoValor($ponto_valor);
                    $precos->setPrecoAberto($preco_aberto);

                    $em->persist($precos);
                    $em->flush();
                } else {
                    $precos = new ItemTabelaPrecos();

                    $precos->setUf($em->getRepository('AppBundle:Uf')->find($i));
                    $precos->setTabelaPrecos($em->getRepository('AppBundle:TabelaPrecos')->find(1));
                    $precos->setProduto($em->getRepository('AppBundle:Produto')->find($id_produto));
                    $precos->setVenda($venda);
                    $precos->setAceitaCartelaDigital($aceita_cartela);
                    $precos->setCreditoCartela($credito_cartela);
                    $precos->setCusto25($custo25);
                    $precos->setCusto35($custo35);
                    $precos->setCusto42($custo42);
                    $precos->setCusto50($custo50);
                    $precos->setPontoValor($ponto_valor);
                    $precos->setPrecoAberto($preco_aberto);

                    $em->persist($precos);
                    $em->flush();
                }
            } catch(\Exception $e){
                Util::log($e->getMessage());
                return new Response($e->getMessage());
            }
        }

        $input_image = $request->files->get('fileToUpload');

        if(!empty($input_image)){
            $extensao = $input_image->getClientMimeType();
            if($extensao != 'image/png') return new Response(0);

            $size = getimagesize($input_image);
            $thumb_width = "200";
            $thumb_height = (int) (($thumb_width / $size[0]) * $size[1]);
            $thumbnail = ImageCreateTrueColor( $thumb_width, $thumb_height);
            $background = imagecolorallocate($thumbnail, 0, 0, 0);
            imagecolortransparent($thumbnail, $background);
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $src_img = ImageCreateFromPNG($input_image);
            ImageCopyResampled($thumbnail, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1]);

            ob_start();
            ImagePNG($thumbnail);
            $contents = ob_get_contents();
            ob_end_clean();

            $imagem = "data:image/png;base64," . base64_encode($contents);

            try {
                $pImagem = $em->getRepository('AppBundle:ProdutoImagem')->findOneByProduto($id_produto);

                if(!empty($pImagem)){
                    $pImagem->setImagem($imagem);

                    $em->persist($pImagem);
                    $em->flush();
                } else {
                    $pImagem = new ProdutoImagem();

                    $pImagem->setProduto($em->getRepository('AppBundle:Produto')->find($id_produto));
                    $pImagem->setImagem($imagem);

                    $em->persist($pImagem);
                    $em->flush();
                }
            } catch(\Exception $e){
                Util::log($e->getMessage());
                return new Response($e->getMessage());
            }
        }

        return new Response(1);
    }

    /**
     * @Route("/admin/inserir-produto", name="admin_inserir_novo_produto")
     * @Method("POST")
     */
    public function inserirProdutoAction(Request $request) {
        return $this->gravarProdutoAction($request);
    }

    /**
     * @Route("/admin/editar-produto", name="admin_editar_produto")
     * @Method("POST")
     */
    public function editarProdutoAction(Request $request){
        $id = $request->get('id');

        $em         = $this->getDoctrine()->getManager();

        $ufs        = $em->getRepository('AppBundle:Uf')->findAll();
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        $tags       = $em->getRepository('AppBundle:Tag')->findAll();

        $produto    = $em->getRepository('AppBundle:Produto')->findOneById($id);
        $prod_img   = $em->getRepository('AppBundle:ProdutoImagem')->findOneByProduto($id);
        $prod_tags  = $produto->getTagsProduto();
        $prod_cat   = $produto->getCategoria();
        $precos     = $produto->getItensTabelaPrecos();

        $visivel    = $produto->getVisivel() == 1 ? 'Sim' : 'Não';

        $html = "   <input type='hidden' name='id_produto' value='" . $id . "'>
                    <div class = 'montar_form_produto'>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Nome</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='ipt_nome_produto' value='" . $produto->getNome() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Categoria</label>
                            </div>
                            <div class='col-md-9'>
                                <select name='ipt_id_categoria_produto' class='form-control'>
                                    <option value='" . $prod_cat->getId(). "'>" . $prod_cat->getNome() . "</option>";

        foreach($categorias as $linha){
            if($linha->getId() != $prod_cat->getId()){
                $html .= "<option value='" . $linha->getId() . "'>" . $linha->getNome() . "</option>";
            }
        }

        $html.= "                   <option value=''></option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>EAN</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='ipt_ean_produto' value='" . $produto->getEan() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>SKU</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='ipt_sku_produto' value='" . $produto->getSku() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Descrição</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='ipt_descricao_produto' value='" . $produto->getDescricao() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Visível</label>
                            </div>
                            <div class='col-md-9'>
                                <select name='ipt_visivel_produto' class='form-control'>
                                    <option value='true' " . ($produto->getVisivel() ? "selected" : "") . ">Sim</option>
                                    <option value='false' " . (!$produto->getVisivel() ? "selected" : "") . ">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Apelido</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='ipt_apelido_produto' value='" . $produto->getApelido() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Quantidade Estrelas</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control numerico' type='text' name='ipt_quantidade_estrelas_produto' value='" . $produto->getQuantidadeEstrelas() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Tags Associadas</label>
                            </div>
                            <div class='col-md-9'>
                                <select name='ipt_tags_produto[]' class='form-control custom' multiple='multiple'>";

        $arrTagsSel = array();
        foreach($prod_tags as $tag) $arrTagsSel[] = $tag->getTag()->getId();

        foreach($tags as $tag){
            $html.= "               <option value='" . $tag->getId() . "' " . (in_array($tag->getId(), $arrTagsSel) ? 'selected' : '') . ">" . $tag->getNome() . "</option>";
        }

        $html.= "               </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <div class='item_imagem_carrinho'><div style='width:77px;height:70px;background-size:cover;background-image: url(" . ($prod_img ? $prod_img->getImagem() : "") . ") '></div></div>
                            </div>
                            <div class='col-md-9'>
                                <input type='file' name='fileToUpload' id='fileToUpload'>
                            </div>
                        </div>
                        <br />
                        <table class='table table-striped'>
                            <tbody>
                                <tr style='background-color: lightgray;'>
                                    <td>&nbsp;</td>
                                    <td>P. Valor</td>
                                    <td>Venda</td>
                                    <td>Custo25</td>
                                    <td>Custo35</td>
                                    <td>Custo42</td>
                                    <td>Custo50</td>
                                    <td>Aceita Cartela</td>
                                    <td>Preço Aberto</td>
                                    <td>Créd. Cartela</td>
                                </tr>
                                <tr>
                                    <td style='font-weight: bold;'>PADRÃO</td>
                                    <td><input size=4 type='text' class='padrao numerico' name='ipt_ponto_valor_' value=''></input></td>
                                    <td><input size=4 type='text' class='padrao numerico money' name='ipt_venda_' value=''></input></td>
                                    <td><input size=4 type='text' class='padrao numerico money' name='ipt_custo25_' value=''></input></td>
                                    <td><input size=4 type='text' class='padrao numerico money' name='ipt_custo35_' value=''></input></td>
                                    <td><input size=4 type='text' class='padrao numerico money' name='ipt_custo42_' value=''></input></td>
                                    <td><input size=4 type='text' class='padrao numerico money' name='ipt_custo50_' value=''></input></td>
                                    <td><input size=4 type='checkbox' class='padrao' name='ipt_aceita_cartela_' value='1'></input></td>
                                    <td><input size=4 type='checkbox' class='padrao' name='ipt_preco_aberto_' value='1'></input></td>
                                    <td><input size=4 type='checkbox' class='padrao' name='ipt_cred_cartela_' value='1'></input></td>
                                </tr>";

        if(count($precos)){
            foreach($precos as $preco){
                $aceita_cartela_digital = $preco->getAceitaCartelaDigital() ? 'checked' : '';
                $preco_aberto           = $preco->getPrecoAberto() ? 'checked' : '';
                $credito_cartela        = $preco->getCreditoCartela() ? 'checked' : '';

                $nome_uf = $preco->getUf()->getNome();
                $id_uf   = $preco->getUf()->getId();

                $html.= "       <tr>
                                    <td style='font-weight: bold;'>" . $nome_uf . "</td>
                                    <td><input size=4 type='text' class='numerico' name='ipt_ponto_valor_" . $id_uf . "' value='" . $preco->getPontoValor() . "'></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_venda_" . $id_uf . "' value='" . $preco->getVenda() . "'></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo25_" . $id_uf . "' value='" . $preco->getCusto25() . "'></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo35_" . $id_uf . "' value='" . $preco->getCusto35() . "'></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo42_" . $id_uf . "' value='" . $preco->getCusto42() . "'></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo50_" . $id_uf . "' value='" . $preco->getCusto50() . "'></input></td>
                                    <td><input size=4 type='checkbox' name='ipt_aceita_cartela_" . $id_uf . "' value='1' $aceita_cartela_digital></input></td>
                                    <td><input size=4 type='checkbox' name='ipt_preco_aberto_" . $id_uf . "' value='1' $preco_aberto></input></td>
                                    <td><input size=4 type='checkbox' name='ipt_cred_cartela_" . $id_uf . "' value='1' $credito_cartela></input></td>
                                </tr>";
            }
        } else {
            foreach($ufs as $uf){
                $html.= "       <tr>
                                    <td style='font-weight: bold;'>" . $uf->getNome() . "</td>
                                    <td><input size=4 type='text' class='numerico' name='ipt_ponto_valor_" . $uf->getId() . "' value=''></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_venda_" . $uf->getId() . "' value=''></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo25_" . $uf->getId() . "' value=''></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo35_" . $uf->getId() . "' value=''></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo42_" . $uf->getId() . "' value=''></input></td>
                                    <td><input size=4 type='text' class='numerico money' name='ipt_custo50_" . $uf->getId() . "' value=''></input></td>
                                    <td><input size=4 type='checkbox' name='ipt_aceita_cartela_" . $uf->getId() . "' value='1'></input></td>
                                    <td><input size=4 type='checkbox' name='ipt_preco_aberto_" . $uf->getId() . "' value='1'></input></td>
                                    <td><input size=4 type='checkbox' name='ipt_cred_cartela_" . $uf->getId() . "' value='1'></input></td>
                                </tr>";
                }
        }

        $html.= "           </tbody>
                        </table>
                    </div>";

        return new Response($html);
    }
}
