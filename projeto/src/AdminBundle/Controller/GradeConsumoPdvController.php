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
use AppBundle\Util;
use AppBundle\Entity\PontoDeVenda;
use AppBundle\Entity\GradeConsumo;
use AppBundle\Entity\GradeConsumoPdv;

class GradeConsumoPdvController extends FOSRestController {
    /**
     * @Sensio\Route("/admin/grade-consumo-pdv", name="admin_grade_consumo_pdv")
     * @Template()
     */
    public function gradeConsumoPdvAction(Request $request){
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $pontoDeVenda["id_ponto_de_venda"] = $usr->getPontoDeVenda();

        return $pontoDeVenda;
    }
           
    
    
    /**
     * @Route("/admin/buscar-grade-consumo-pdv", name="buscar_grade_consumo_pdv")
     * @Method("POST")
     */
    public function getGradeConsumoPdvAction(Request $request){
        $pontoDeVenda = $this->get('security.token_storage')->getToken()->getUser()->getPontoDeVenda()->getId();

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "SELECT 
                    c.nome AS nome_categoria,
                    p.nome AS nome_produto,
                    COUNT(gcp.id_grade_consumo) AS tem_itens,
                    MAX(gc.id_grade_consumo) AS id_grade_consumo
                FROM 
                    categoria c, produto p, grade_consumo gc 
                LEFT JOIN grade_consumo_pdv AS gcp ON gcp.id_grade_consumo = gc.id_grade_consumo AND 
                                                      gcp.ativo = 'true' AND 
                                                      gcp.id_ponto_de_venda = " . $pontoDeVenda;

        $wlist = array();
        $wlist[] = "gc.id_produto = p.id_produto";
        $wlist[] = "p.id_categoria = c.id_categoria";
        $wlist[] = "gc.id_categoria IS NOT NULL";

        if(($nome_produto = $request->get('nome_produto')) != '') $wlist[] = "p.nome like '%$nome_produto%'";

        $groupBy = "p.id_produto, c.id_categoria";

        $params = Array( "query" => $cmd , "where" => $wlist, "group" => $groupBy);

        $util = new Util( $connection ) ;
        $output = $util->dataTableSource( $request, $params );

        return new Response( $output );
    }

    /**
     * @Route("/admin/remover-grade-consumo-pdv", name="admin_remover_grade_consumo_pdv")
     * @Method("POST")
     */
    public function removerGradeConsumoPdvAction(Request $request) {
        $id_ponto_de_venda = $this->get('security.token_storage')->getToken()->getUser()->getPontoDeVenda()->getId();
        $id_grade_consumo  = $request->get('id');
        $id_produto        = $this->getProdutoByGrade($id_grade_consumo)->getId();
        
        $saida = $this->limpaGradePdv($id_ponto_de_venda, $id_produto);

        return new Response($saida);
    }
    
    /* Helper para pegar o id_produto por uma grade */
    public function getProdutoByGrade($id_grade_consumo){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "SELECT id_produto 
                FROM grade_consumo 
                WHERE id_grade_consumo = $id_grade_consumo";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $res = $statement->fetch();
        
        return $em->getRepository('AppBundle:Produto')->find($res["id_produto"]);
    }
    
    /* Helper para limpar a grade personalizada */
    public function limpaGradePdv($id_ponto_de_venda, $id_produto){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "UPDATE item_pdv_tabela_precos 
                SET ativo = 'false' 
                WHERE id_produto = $id_produto AND 
                      id_ponto_de_venda = $id_ponto_de_venda";
        $statement = $connection->prepare($cmd);
        $statement->execute();

        
        $cmd = "SELECT id_grade_consumo_pdv 
                FROM grade_consumo_pdv 
                WHERE id_ponto_de_venda = $id_ponto_de_venda AND 
                      ativo = 'true' AND 
                      id_grade_consumo IN (SELECT id_grade_consumo 
                                           FROM grade_consumo 
                                           WHERE id_produto = $id_produto)";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $res_linhas = $statement->fetchAll();

        foreach($res_linhas as $linha){
            try {
                $delLinha = $em->getRepository('AppBundle:GradeConsumoPdv')->find($linha['id_grade_consumo_pdv']);
                $delLinha->setAtivo(false);
                $em->persist($delLinha);
                $em->flush();
            } catch (\Exception $e){
                Util::log($e->getMessage());
                return $e->getMessage();
            }
        }

        return NULL;
    }

    /**
     * @Route("/admin/gravar-grade-consumo-pdv", name="admin_gravar_grade_consumo_pdv")
     * @Method("POST")
     */
    public function gravarGradeConsumoAction(Request $request) {
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        parse_str($request->get('dados'), $searcharray);

        $pontoDeVenda = $this->get('security.token_storage')->getToken()->getUser()->getPontoDeVenda();
        $produto      = $this->getProdutoByGrade($searcharray['grade_consumo']['id_grade_consumo'][0]);

        $this->limpaGradePdv($pontoDeVenda->getId(), $produto->getId());
        
        $preco = str_replace('.', '', $searcharray['grade_valor_venda']);
        $preco = str_replace(',', '.', $preco);
        $preco = floatval($preco);
        
        $addPrecoPdv = new \AppBundle\Entity\ItemPdvTabelaPrecos;
        $addPrecoPdv->setProduto($produto);
        $addPrecoPdv->setPontoDeVenda($pontoDeVenda);
        $addPrecoPdv->setPreco($preco);
        $addPrecoPdv->setAtivo(true);
        $em->persist($addPrecoPdv);
        $em->flush();
                
        foreach($searcharray['grade_consumo']['id_grade_consumo'] as $idx => $idGradeConsumo){
            try {
                $itemPadrao = $em->getRepository('AppBundle:GradeConsumo')->find($idGradeConsumo);

                $newQTD = $searcharray['grade_consumo']['quantidade'][$idx] === "" ? $itemPadrao->getQuantidade() : $searcharray['grade_consumo']['quantidade'][$idx];
                $newQTD = str_replace(',', '.', $newQTD);
                $newQTD = floatval($newQTD);
                
                $addGradeConsumoPdv = new GradeConsumoPdv();
                $addGradeConsumoPdv->setIdGradeConsumo($itemPadrao);
                $addGradeConsumoPdv->setIdPontoDeVenda($pontoDeVenda);
                $addGradeConsumoPdv->setQuantidade($newQTD);
                $addGradeConsumoPdv->setAtivo(true);
                $em->persist($addGradeConsumoPdv);
                $em->flush();
            } catch(\Exception $e){
                Util::log($e->getMessage());
                return new Response($e->getMessage());
            }
        }
        
        return new Response(1);
    }

    /**
     * @Route("/admin/editar-grade-consumo-pdv", name="admin_editar_grade_consumo_pdv")
     * @Method("POST")
     */
    public function editarGradeConsumoAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $idPontoDeVenda   = $user->getPontoDeVenda()->getId();
        $id_grade_consumo = $request->get('id');
        $id_produto       = $this->getProdutoByGrade($id_grade_consumo)->getId();

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        
        
        // pega o valor padrao
        $id_uf = $user->getPontoDeVenda()->getUf()->getId();
        
        $cmd = "SELECT  venda AS preco 
                FROM    item_tabela_precos AS itp 
                JOIN    tabela_precos AS tp ON tp.id_tabela_precos = itp.id_tabela_precos 
                JOIN    ponto_de_venda AS pdv ON pdv.id_classe_ponto_de_venda = tp.id_classe_ponto_de_venda 
                WHERE   pdv.id_ponto_de_venda = $idPontoDeVenda
                    AND itp.id_uf = $id_uf
                    AND itp.id_produto = $id_produto";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $res = $statement->fetch();
        $preco_padrao = (isset($res["preco"]) && $res["preco"]) ? number_format($res["preco"], 2, ',', '.') : NULL;


        // pega o valor personalizado
        $cmd = "SELECT preco 
                FROM item_pdv_tabela_precos 
                WHERE id_produto = $id_produto AND 
                      id_ponto_de_venda = $idPontoDeVenda AND 
                      ativo = 'true'";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $res = $statement->fetch();
        $preco_personalizado = (isset($res["preco"]) && $res["preco"]) ? number_format($res["preco"], 2, ',', '.') : NULL;

        
        $html = '';
        $html.= '
                <div style="margin: 0px -7px 0">
                    <div class="form-group col-md-6 col-lg-6">
                        <label for="grade_valor_venda">Valor de Venda PADRÃO</label>
                        <p>R$ ' . $preco_padrao . '</p>
                    </div>
                    <div class="form-group col-md-6 col-lg-6">
                        <label for="grade_valor_venda">Valor de Venda PERSONALIZADO</label>
                        <div class="input-group">
                            <span class="input-group-addon">R$</span>
                            <input type="text" class="form-control money" id="grade_valor_venda" name="grade_valor_venda" aria-describedby="grade_valor_venda" value="' . $preco_personalizado . '" />
                        </div>
                    </div>
                </div>
                
                <table id="table_grade_consumo" class="table montar_form_grade_consumo">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Produto Específico</th>
                            <th>Descrição</th>
                            <th>Qtd. Padrão</th>
                            <th>Qtd. Personalizada</th>
                        </tr>
                    </thead>
                    <tbody id="new_line">
        ';

        $result = array();

        $cmd = "SELECT 
                    gc.id_grade_consumo,
                    c.nome AS nome_categoria,
                    p.nome AS nome_produto,
                    pesp.nome AS nome_produto_especifico, 
                    gc.descricao AS descricao, 
                    gc.quantidade AS qtd_padrao,
                    gcp.quantidade AS qtd_personalizada
                FROM grade_consumo AS gc 
                LEFT JOIN grade_consumo_pdv AS gcp ON gcp.id_grade_consumo = gc.id_grade_consumo AND 
                                                      gcp.ativo = 'true' AND  
                                                      gcp.id_ponto_de_venda = $idPontoDeVenda
                LEFT JOIN categoria AS c ON gc.id_categoria = c.id_categoria
                LEFT JOIN produto AS p ON gc.id_produto = p.id_produto
                LEFT JOIN produto AS pesp ON pesp.id_produto = gc.id_produto_especifico
                WHERE gc.id_produto = " . $id_produto . " 
                ORDER BY gc.id_grade_consumo ASC";

        $statement = $connection->prepare ($cmd);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach($result as $key => $linha){
            $html.= '<tr class="grade_consumo">
                         <td>' . $linha["nome_categoria"] . '</td>
                         <td>' . $linha["nome_produto_especifico"] . '</td>
                         <td>' . $linha["descricao"] . '</td>
                         <td>' . $linha["qtd_padrao"] . '</td>
                         <td>
                             <input type="hidden" id="grade_consumo_id_grade_consumo' . $key . '" name="grade_consumo[id_grade_consumo][' . $key . ']" value="' . $linha["id_grade_consumo"] . '" />
                             <input type="text" id="grade_consumo_quantidade' . $key . '" name="grade_consumo[quantidade][' . $key . ']" class="form-control numerico" value="' . $linha["qtd_personalizada"] . '" />
                         </td>
                     </tr>';
        }
        
        $html.= '   </tbody>
                    <tr><td><button type="submit" class="btn btn-primary">Salvar</button></td></tr>
                 </table>';
        
        return new Response($html);
    }
}
