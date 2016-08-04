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
use AppBundle\Entity\Categoria;
use AppBundle\Entity\GradeConsumo;

class GradeConsumoController extends FOSRestController {
    /**
     * @Sensio\Route("/admin/grade-consumo", name="admin_grade_consumo")
     * @Template()
     */

    public function gradeConsumoAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from categoria order by nome";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['categorias'] = $statement->fetchAll();

        return $d;
    }
	
    /**
     * @Sensio\Route("/admin/carrega-categorias", name="admin_carrega_categorias")
     * @Template()
     */

    public function carregaCategoriasAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from categoria order by nome";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $categorias = $statement->fetchAll();

        return new Response(json_encode($categorias));
    }

    /**
     * @Sensio\Route("/admin/carrega-produtos", name="admin_carrega_produtos")
     * @Template()
     */
    public function carregaProdutosAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from produto order by nome";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $produtos = $statement->fetchAll();

        return new Response(json_encode($produtos));
    }

    /**
     * @Route("/admin/buscar-grade-consumo", name="buscar_grade_consumo")
     * @Method("POST")
     */
    public function getGradeConsumoAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "SELECT 
                    c.nome AS nome_categoria,
                    p.nome AS nome_produto,
                    MAX(gc.id_grade_consumo) AS id_grade_consumo
                FROM 
                    grade_consumo gc, categoria c, produto p";

        $wlist = array();
        $wlist[] = "gc.id_produto = p.id_produto";
        $wlist[] = "p.id_categoria = c.id_categoria";
        $wlist[] = "gc.id_categoria IS NOT NULL";

        if(($nome_produto = $request->get('nome_produto')) != '') $wlist[] = "p.nome like '%$nome_produto%'";

        $groupBy = "p.id_produto, c.id_categoria";

        $params = array("query" => $cmd , "where" => $wlist, "group" => $groupBy);

        $util = new Util($connection);
        $output = $util->dataTableSource($request, $params);

        return new Response($output);
    }

    /**
     * @Route("/admin/remover-grade-consumo", name="admin_remover_grade_consumo")
     * @Method("POST")
     */
    public function removerGradeConsumoAction(Request $request) {
        $id_grade_consumo = $request->get('id');

        $em         = $this->getDoctrine()->getManager();
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

        return new Response ();
    }

    /**
     * @Route("/admin/gravar-grade-consumo", name="admin_gravar_grade_consumo")
     * @Method("POST")
     */
    public function gravarGradeConsumoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection ();

        parse_str($request->get('dados'), $searcharray);

        $id_produto = $searcharray['id_produto'];
        
        $arrItens = array();
        foreach($searcharray['grade_consumo']['id_grade_consumo'] as $item) if($item) $arrItens[] = $item;
        if(count($arrItens)){
            $cmd = "delete from grade_consumo where id_produto = " . $id_produto . " and id_grade_consumo NOT IN (" . implode(',', $arrItens) . ")";
            $connection->prepare($cmd)->execute();
        }

        for($i=0;$i<=count($searcharray['grade_consumo']['categoria']);$i++){
            if(isset($searcharray['grade_consumo']['categoria'][$i])){
                try {
                    if($searcharray['grade_consumo']['id_grade_consumo'][$i]){
                        $item = $em->getRepository('AppBundle:GradeConsumo')->findOneById($searcharray['grade_consumo']['id_grade_consumo'][$i]);
                    } else {
                        $item = new GradeConsumo();
                    }

                    if($searcharray['grade_consumo']['categoria'][$i] != ''){
                        $item->setCategoria($em->getRepository('AppBundle:Categoria')->findOneById($searcharray['grade_consumo']['categoria'][$i]));
                    }

                    $item->setProduto($em->getRepository('AppBundle:Produto')->findOneById($searcharray['id_produto']));

                    if($searcharray['grade_consumo']['produto_especifico'][$i] != ''){
                        $item->setIdProdutoEspecifico($em->getRepository('AppBundle:Produto')->findOneById($searcharray['grade_consumo']['produto_especifico'][$i]));
                    }

                    $item->setQuantidade($searcharray['grade_consumo']['quantidade'][$i]);
                    $item->setDescricao($searcharray['grade_consumo']['descricao'][$i]);
                    $item->setVisivel($searcharray['grade_consumo']['visivel'][$i]);

                    $em->persist($item);
                    $em->flush();
                } catch(\Exception $e){
                    Util::log($e->getMessage());
                    return new Response($e->getMessage());
                }
            }
        }

        return new Response(1);
    }

    /**
     * @Route("/admin/editar-grade-consumo", name="admin_editar_grade_consumo")
     * @Method("POST")
     */
    public function editarGradeConsumoAction(Request $request){
        $id = $request->get ( 'id' );

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $id_produto = '';

        if($id != ''){
            $cmd = "select id_produto from grade_consumo where id_grade_consumo = $id";

            $statement = $connection->prepare($cmd);
            $statement->execute();
            $result_produto_chave = $statement->fetch();

            $id_produto = $result_produto_chave['id_produto'];
        }

        $cmd = "select * from categoria order by nome";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $categorias = $statement->fetchAll();

        $cmd = "select * from produto order by nome";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $produtos = $statement->fetchAll();

        $result = array();

        if($id_produto != ''){
            $cmd = "SELECT 
                        c.id_categoria AS id_categoria,
                        c.nome AS nome_categoria,
                        p.nome AS nome_produto,
                        p.id_produto AS id_produto,
                        gc.quantidade AS quantidade,
                        gc.visivel AS visivel,
                        gc.descricao AS descricao,
                        gc.id_produto_especifico AS id_produto_especifico,
                        gc.id_grade_consumo,
                        pesp.nome AS nome_produto_especifico
                    FROM grade_consumo AS gc 
                    LEFT JOIN categoria AS c ON gc.id_categoria = c.id_categoria
                    JOIN produto AS p ON gc.id_produto = p.id_produto
                    LEFT JOIN produto AS pesp ON pesp.id_produto = gc.id_produto_especifico
                    WHERE gc.id_produto = " . $id_produto . "";

            $statement = $connection->prepare($cmd);
            $statement->execute ();
            $result = $statement->fetchAll ();
        }

        $html = '';
        $html .= '<table id="table_grade_consumo" class="table montar_form_grade_consumo">
                    <thead>
                        <tr>
                            <th colspan="6">Produto</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td colspan="7">
                                <select id="id_produto" name="id_produto" class="form-control">
                                    <option value=""></option>';
        
        foreach($produtos as $produto){
            $html .= '              <option value="' . $produto['id_produto'] . '" ' .  ($id_produto == $produto['id_produto'] ? 'selected' : '') . '>' . $produto['nome'] . '</option>';
        }

        $html .= '              </select>                            
                            </td>
                        </tr>
                    </tbody>
                    
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Produto Específico</th>
                            <th>Qtd.</th>
                            <th>Visível</th>
                            <th>Descricao</th>
                            <th colspan="2">&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody id="new_line">
                    ';

        if(empty($result)){
            $key = 0;

            $html .= '<tr class="grade_consumo">
                        <td>
                            <input type="hidden" id="grade_consumo_id_grade_consumo_'.$key.'" name="grade_consumo[id_grade_consumo]['.$key.']" value="">
                                
                            <select  class="combo_categoria combo" id="grade_consumo_categoria_'.$key.'" name="grade_consumo[categoria]['.$key.']" class="form-control">
                                <option value=""></option>';

            foreach($categorias as $categoria){
                $html .= '      <option value="'.$categoria['id_categoria'].'">'.$categoria['nome'].'</option>';
            }

            $html .= '      </select>
                        </td>
                        <td>
                            <select  class="combo_produto combo"  id="grade_consumo_produto_especifico_'.$key.'" name="grade_consumo[produto_especifico]['.$key.']" class="form-control">
                                <option value=""></option>';

            foreach($produtos as $produto){
                $html .= '      <option value="'.$produto['id_produto'].'">'.$produto['nome'].'</option>';
            }

            $html .= '      </select>
                        </td>';

            $html .= '  <td><input style="width: 50px" type="text" id="grade_consumo_quantidade'.$key.'" name="grade_consumo[quantidade]['.$key.']" class="grade_quant form-control" value=""></input></td>
                        <td>
                            <select id="grade_consumo_visivel'.$key.'" name="grade_consumo[visivel]['.$key.']" class="form-control">
                                <option value="true" ' . ($produto['visivel'] ? "selected" : "") . '>Sim</option>
                                <option value="false" ' . (!$produto['visivel'] ? "selected" : "") . '>Não</option>
                            </select>
                        </td>
                        <td><input type="text" id="grade_consumo_descricao'.$key.'" name="grade_consumo[descricao]['.$key.']" class="grade_descricao form-control" value=""></input></td>';

            $html .= '  <td><button class="btn btn-info" onclick="AddTableRowGradeConsumo('.$key.')" type="button">+</button></td>
                        <td><button class="btn btn-danger" onclick="RemoveTableRow(this)" type="button">-</button></td>
                    </tr>';

        } else {
            foreach($result as $key => $dados){
                $html .= '
                    <tr class="grade_consumo">
                        <td>
                            <input type="hidden" id="grade_consumo_id_grade_consumo_'.$key.'" name="grade_consumo[id_grade_consumo]['.$key.']" value="'.$dados['id_grade_consumo'].'">
                                
                            <select  class="combo_categoria combo"  id="grade_consumo_categoria_'.$key.'" name="grade_consumo[categoria]['.$key.']" class="form-control">
                                <option value="'.$dados['id_categoria'].'">' . $dados['nome_categoria'] . '</option>';
                foreach($categorias as $categoria){
                    if($dados['id_categoria'] != $categoria['id_categoria']){
                        $html .= '<option value="'.$categoria['id_categoria'].'">'.$categoria['nome'].'</option>';
                    }
                }

                $html .= '  </select>
                        </td>
                        <td>
                            <select class="combo_produto combo" id="grade_consumo_produto_especifico_'.$key.'" name="grade_consumo[produto_especifico]['.$key.']" class="form-control">
                                <option value="'.$dados['id_produto_especifico'].'">' . $dados['nome_produto_especifico'] . '</option>';

                foreach($produtos as $produto){
                    if($dados['id_produto_especifico'] != $produto['id_produto']){
                        $html .= '<option value="'.$produto['id_produto'].'">'.$produto['nome'].'</option>';
                    }
                }

                $html .= '</select>
                        </td>';

                $html .= '
                        <td><input style="width: 50px" type="text" id="grade_consumo_quantidade'.$key.'" name="grade_consumo[quantidade]['.$key.']" class="form-control grade_quant " value="'.$dados['quantidade'].'"></input></td>
                        <td>
                            <select id="grade_consumo_visivel'.$key.'" name="grade_consumo[visivel]['.$key.']" class="form-control">
                                <option value="true" ' . ($dados['visivel'] ? "selected" : "") . '>Sim</option>
                                <option value="false" ' . (!$dados['visivel'] ? "selected" : "") . '>Não</option>
                            </select>
                        </td>
                        <td><input type="text" id="grade_consumo_descricao'.$key.'" name="grade_consumo[descricao]['.$key.']" class="form-control grade_descricao " value="'.$dados['descricao'].'"></input></td>';
                $html .= '
                        <td><button class="btn btn-info" onclick="AddTableRowGradeConsumo('.$key.')" type="button">+</button></td>
                        <td><button class="btn btn-danger" onclick="RemoveTableRow(this)" type="button">-</button></td>
                    </tr>';
            }
        }

        $html .= '
                </tbody>
                <tr><td><button type="submit" class="btn btn-primary">Salvar</button></td></tr>
            </table>';

        $html .= '<script>$(".combo").chosen();</script>';

        return new Response($html);
    }
}
