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

class CategoriasController extends FOSRestController
{
	/**
	 * @Sensio\Route("/admin/categorias", name="admin_categorias")
	 * @Template()
	 */
	
	public function categoriasAction(Request $request)
	{
	
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$cmd = "select * from categoria order by 1";
		$statement = $connection->prepare($cmd);
		$statement->execute();
		$d['categorias'] = $statement->fetchAll();
	
		return $d;
	}
	
	/**
	 * @Route("/admin/buscar-categorias", name="buscar_categorias")
	 * @Method("POST")
	 */
	public function getCategoriasAction(Request $request)
	{
	
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$wlist = array();
	
		if (($nome_categoria = $request->get('nome_categoria')) != '') {
			$wlist[] = " c2.nome like '%$nome_categoria%'";
		}
			
		$cmd = "select c2.nome, c.nome as nome_pai, c2.descricao, c2.id_categoria
				from categoria c 
				right join categoria c2 on c2.id_categoria_pai = c.id_categoria	";
	
		$params = Array( "query" => $cmd , "where" => $wlist);
	
		$util = new Util( $connection ) ;
		$output = $util->dataTableSource( $request, $params );
	
		return new Response( $output );
	}
	
	/**
	 * @Route("/admin/remover-categoria", name="admin_remover_categoria")
	 * @Method("POST")
	 */
	public function removercategoriaAction(Request $request) {
	
		$id_categoria = $request->get ( 'id' );
	
		try {
			$em = $this->getDoctrine ()->getManager ();
			$removerCategoria = $em->getRepository ( 'AppBundle:Categoria' )->find ( $id_categoria );
			$em->remove($removerCategoria);
			$em->flush();
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/gravar-categoria", name="admin_gravar_categoria")
	 * @Method("POST")
	 */
	public function gravarCategoriaAction(Request $request) {
	
		parse_str ( $request->get ( 'dados' ), $searcharray );
		
		//Util::log($searcharray ['editar_id_categoria_pai']);

		try {
			$em = $this->getDoctrine ()->getManager ();
			$alterarCategoria = $em->getRepository ( 'AppBundle:Categoria' )->find ( $searcharray ['id_categoria'] );
			$alterarCategoria->setNome ( $searcharray ['editar_nome_categoria'] );
			if($searcharray ['editar_id_categoria_pai'] != '')
				$alterarCategoria->setPai ( $em->getRepository('AppBundle:Categoria')->find($searcharray ['editar_id_categoria_pai']));
			else{
				$alterarCategoria->setPai (null);
			}
			
			$alterarCategoria->setDescricao ( $searcharray ['editar_descricao_categoria'] );
			$em->flush ();
	
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/inserir-categoria", name="admin_inserir_nova_categoria")
	 * @Method("POST")
	 */
	public function inserirCategoriaAction(Request $request) {
	
		parse_str ( $request->get ( 'dados' ), $searcharray );
	
		try {
				
			$em = $this->getDoctrine ()->getManager ();
			$inserirCategoria = new Categoria();
			$inserirCategoria->setNome($searcharray['novo_nome_categoria']) ;
			$inserirCategoria->setPai($em->getRepository('AppBundle:Categoria')->find($searcharray['novo_id_categoria_pai']));
			$inserirCategoria->setDescricao($searcharray['nova_descricao_categoria']) ;
			$em->persist($inserirCategoria);
			$em->flush();
	
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/editar-categoria", name="admin_editar_categoria")
	 * @Method("POST")
	 */
	public function editarCategoriaAction(Request $request)
	{
		$id = $request->get ( 'id' );
			
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$cmd = "select c2.nome, c.nome as nome_pai, c2.descricao, c2.id_categoria 
				from categoria c 
				right join categoria c2 on c2.id_categoria_pai = c.id_categoria WHERE c2.id_categoria = $id";
			
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result = $statement->fetch ();
		
		$cmd = "select * from categoria order by nome";
			
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result_all = $statement->fetchAll ();
			
		$html = "<input type='hidden' name='id_categoria' value='" . $result ['id_categoria'] . "'>
    			<div class = 'montar_form_categoria'>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Nome</label>
    					</div>
    					<div class='col-md-6'>
    						<input class='form-control' type='text' name='editar_nome_categoria' value='" . $result ['nome'] . "'></input>
    					</div>
       				</div>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Nome Categoria Pai</label>
    					</div>
    					<div class='col-md-6'>
    						<select name='editar_id_categoria_pai' class='form-control'>
                                    <option value='".$result['id_categoria']."'>".$result['nome_pai']."</option>";
foreach($result_all as $categorias){
	if($categorias['id_categoria'] != $result['id_categoria']){
		$html .= "<option value='" . $categorias['id_categoria'] . "'>" . $categorias['nome'] . "</option>";	
	}
}
                                $html .= "<option value=''></option>
                                		</select>
    					</div>
       				</div>
                    <div class='row'>
						<div class='col-md-3'>
							<label>Descrição</label>
    					</div>
    					<div class='col-md-6'>
    						<input class='form-control' type='text' name='editar_descricao_categoria' value='" . $result ['descricao'] . "'></input>
    					</div>
       				</div>
    			</div>";
	
	
		return new Response( $html );
	}
}
