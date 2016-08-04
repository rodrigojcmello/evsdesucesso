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
use AppBundle\Entity\Tag;
use AppBundle\Entity\TagProduto;

class TagsController extends FOSRestController
{
	/**
	 * @Sensio\Route("/admin/tags", name="admin_tags")
	 * @Template()
	 */
	
	public function tagsAction(Request $request)
	{
	
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$cmd = "select * from tag order by 1";
		$statement = $connection->prepare($cmd);
		$statement->execute();
		$d['tags'] = $statement->fetchAll();
	
		return $d;
	}
	
	/**
	 * @Route("/admin/buscar-tags", name="buscar_tags")
	 * @Method("POST")
	 */
	public function getTagsAction(Request $request)
	{
	
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$wlist = array();
	
		if (($nome_tag = $request->get('nome_tag')) != '') {
			$wlist[] = " t2.nome like '%$nome_tag%'";
		}
			
		$cmd = "select 
				t2.nome as nome,
				t.nome as nome_tag_pai,
				t2.visivel as visivel,
				t2.exibir_auto_produtos as exibir_auto_produtos,
				t2.exibir_categoria as exibir_categoria,
				t2.id_tag as id_tag
				from tag t right join tag t2 on t2.id_parent = t.id_tag";
	
		$params = Array( "query" => $cmd , "where" => $wlist);
	
		$util = new Util( $connection ) ;
		$output = $util->dataTableSource( $request, $params );
	
		return new Response( $output );
	}
	
	/**
	 * @Route("/admin/remover-tag", name="admin_remover_tag")
	 * @Method("POST")
	 */
	public function removerTagAction(Request $request) {
	
		$id_tag = $request->get ( 'id' );
	
		try {
			$em = $this->getDoctrine ()->getManager ();
			$removerTag = $em->getRepository ( 'AppBundle:Tag' )->find ( $id_tag );
			$em->remove($removerTag);
			$em->flush();
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/gravar-tag", name="admin_gravar_tag")
	 * @Method("POST")
	 */
	public function gravarTagAction(Request $request) {
	
		parse_str ( $request->get ( 'dados' ), $searcharray );
		
		if($searcharray ['editar_exibir_auto_produtos_tag'] == '')
			$searcharray ['editar_exibir_auto_produtos_tag'] = false;
		
			if($searcharray ['editar_visivel_tag'] == '')
				$searcharray ['editar_visivel_tag'] = false;
			
				if($searcharray ['editar_exibir_categoria_tag'] == '')
					$searcharray ['editar_exibir_categoria_tag'] = false;
	
		try {
			$em = $this->getDoctrine ()->getManager ();
			$alterarTag = $em->getRepository ( 'AppBundle:Tag' )->find ( $searcharray ['id_tag'] );
			$alterarTag->setNome ( $searcharray ['editar_nome_tag'] );
			
			if($searcharray['editar_id_parent_tag'] != '')
				$alterarTag->setTagProduto($em->getRepository('AppBundle:Tag')->find($searcharray['editar_id_parent_tag']));
			else
				$alterarTag->setTagProduto(null);
			
			$alterarTag->setVisivel ( $searcharray ['editar_visivel_tag'] );
			$alterarTag->setExibirAutoProdutos ( $searcharray ['editar_exibir_auto_produtos_tag'] );
			$alterarTag->setExibirCategoria ( $searcharray ['editar_exibir_categoria_tag'] );
			$em->flush ();
	
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
		
		try {
			$em = $this->getDoctrine ()->getManager ();
			$removerTagProduto = $em->getRepository ( 'AppBundle:TagProduto' )->findByTag ( $searcharray ['id_tag'] );
			
			foreach ($removerTagProduto as $tags_produto){
				$em->remove($tags_produto);
				$em->flush();
			}
				
			foreach ($searcharray['editar_id_tag_produto'] as $id_produto){
				$inserirTagProduto = new TagProduto();
				$inserirTagProduto->setProduto($em->getRepository('AppBundle:Produto')->find($id_produto));
				$inserirTagProduto->setTag($em->getRepository('AppBundle:Tag')->find($searcharray ['id_tag']));
				$em->persist($inserirTagProduto);
				$em->flush();
			}
			
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/inserir-tag", name="admin_inserir_nova_tag")
	 * @Method("POST")
	 */
	public function inserirTagAction(Request $request) {
	
		parse_str ( $request->get ( 'dados' ), $searcharray );
	
		try {
	
			$em = $this->getDoctrine ()->getManager ();
			$inserirTag = new Tag();
			$inserirTag->setNome($searcharray['novo_nome_tag']) ;
			
			if($searcharray['novo_id_parent_tag'] != 'todos')
				$inserirTag->setTagProduto($em->getRepository('AppBundle:Tag')->find($searcharray['novo_id_parent_tag']));
			else
				$inserirTag->setTagProduto(null);
			
			$inserirTag->setVisivel($searcharray['novo_visivel_tag']) ;
			$inserirTag->setExibirAutoProdutos($searcharray['novo_exibir_auto_produtos_tag']) ;
			$inserirTag->setExibirCategoria($searcharray['novo_exibir_categoria_tag']) ;
			$em->persist($inserirTag);
			$em->flush();
	
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/editar-tag", name="admin_editar_tag")
	 * @Method("POST")
	 */
	public function editarTagAction(Request $request)
	{
		$id = $request->get ( 'id' );
		$id_produto = array();
			
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
		
		$cmd = "select produto.*,categoria.nome nome_categoria from produto join categoria on categoria.id_categoria = produto.id_categoria order by categoria.nome ,produto.nome";
		
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result_produto = $statement->fetchAll ();
		
		$cmd = "select tp.id_produto from tag_produto tp where tp.id_tag = $id";
		
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result_tag_produto = $statement->fetchAll ();
		
		foreach ($result_tag_produto as $tag_produto){
			$id_produto[] = $tag_produto['id_produto'];
		}
	
		$cmd = "select 
				t2.nome as nome,
				t.nome as nome_tag_pai,
				t2.visivel as visivel,
				t2.exibir_auto_produtos as exibir_auto_produtos,
				t2.exibir_categoria as exibir_categoria,
				t2.id_tag as id_tag,
				t2.id_parent as id_parent
				from tag t right join tag t2 on t2.id_parent = t.id_tag
				WHERE t2.id_tag = $id";
			
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result = $statement->fetch ();
		
		$visivel = $result['visivel'];
		
		if($visivel == 1)
			$visivel = 'Sim';
		else $visivel = 'Não';
		
		$exibir_auto_produtos = $result['exibir_auto_produtos'];
		
		if($exibir_auto_produtos == 1)
			$exibir_auto_produtos = 'Sim';
		else $exibir_auto_produtos = 'Não';
			
		$exibir_categoria = $result['exibir_categoria'];
			
		if($exibir_categoria == 1)
			$exibir_categoria = 'Sim';
		else $exibir_categoria = 'Não';
		
		$cmd = "select * from tag order by nome";
			
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result_all = $statement->fetchAll ();
		
		$html = "<input type='hidden' name='id_tag' value='" . $result ['id_tag'] . "'>
    			<div class = 'montar_form_tag'>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Nome</label>
    					</div>
    					<div class='col-md-6'>
    						<input class='form-control' type='text' name='editar_nome_tag' value='" . $result ['nome'] . "'></input>
    					</div>
       				</div>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Nome Tag Pai</label>
    					</div>
    					<div class='col-md-6'>
    						<select name='editar_id_parent_tag' class='form-control'>
                                    <option value='".$result['id_parent']."'>".$result['nome_tag_pai']."</option>";
		foreach($result_all as $tags){
			if($tags['id_tag'] != $result['id_parent']){
				$html .= "<option value='" . $tags['id_tag'] . "'>" . $tags['nome'] . "</option>";
			}
		}
		$html .= "<option value=''></option>
                                		</select>
    					</div>
       				</div>";
		
		$html .= "<div class='row'>
						<div class='col-md-3'>
							<label>Visível</label>
    					</div>
				<div class='col-md-6'>
    						<select name='editar_visivel_tag' class='form-control'>
                                    <option value='".$result['visivel']."'>".$visivel."</option>";
				if($result['visivel'] == true){
					$html .= "<option value='false'>Não</option>";
				}else
					$html .= "<option value='true'>Sim</option>";
				
                            $html .= "</select>
                </div>
                </div>";
		
		$html .= "<div class='row'>
						<div class='col-md-3'>
							<label>Exibir auto. produtos</label>
    					</div>
				<div class='col-md-6'>
    						<select name='editar_exibir_auto_produtos_tag' class='form-control'>
                                    <option value='".$result['exibir_auto_produtos']."'>".$exibir_auto_produtos."</option>";
		if($result['exibir_auto_produtos'] == true){
			$html .= "<option value='false'>Não</option>";
		}else
			$html .= "<option value='true'>Sim</option>";
		
                            $html .= "</select>
                </div>
                </div>";
		
		$html .= "<div class='row'>
						<div class='col-md-3'>
							<label>Exibir categoria</label>
    					</div>
				<div class='col-md-6'>
    						<select name='editar_exibir_categoria_tag' class='form-control'>
                                    <option value='".$result['exibir_categoria']."'>".$exibir_categoria."</option>";
                if($result['exibir_categoria'] == true){
					$html .= "<option value='false'>Não</option>";
				}else
					$html .= "<option value='true'>Sim</option>";
				
                            $html .= "</select>
                </div>
                </div>";
                            
		$html .= "<div class='row'>
						<div class='col-md-3'>
							<label>Adicionar produtos: </label>
    					</div>
				  </div>";
				  
                $cat = "..." ;
				foreach($result_produto as $produtos){
				        if ( $cat !== $produtos['nome_categoria'] ) {

                				$html .= "<div class='row'><b style='font-size:8pt;border-bottom:solid 1px #333'>".$produtos['nome_categoria']."</b></div>" ;
						$cat = $produtos['nome_categoria'];
						
                                        }
 
					if(in_array($produtos['id_produto'], $id_produto)){
						$checked = 'checked';
					}else $checked = '';
					$html .= "<div class='row'><input type='checkbox' name='editar_id_tag_produto[]' $checked value='" . $produtos ['id_produto'] . "'>&nbsp;" . $produtos ['nome'] . "</input></div>";
				}
		            
    	$html .= "</div>";
	
	
		return new Response( $html );
	}
}
