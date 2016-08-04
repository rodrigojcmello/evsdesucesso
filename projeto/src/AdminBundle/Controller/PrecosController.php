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

class PrecosController extends FOSRestController
{
	/**
	 * @Sensio\Route("/admin/precos", name="admin_precos")
	 * @Template()
	 */
	
	public function precosAction(Request $request)
	{
	
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$cmd = "select * from classe_ponto_de_venda";
		$statement = $connection->prepare($cmd);
		$statement->execute();
		$d['classes'] = $statement->fetchAll();
	
		return $d;
	}
	
	/**
	 * @Route("/admin/buscar-tabela-precos", name="buscar_tabela_precos")
	 * @Method("POST")
	 */
	public function getPrecosAction(Request $request)
	{
	
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
	
		$wlist = array();
	
		if (($nome_classe = $request->get('nome_classe')) != '') {
			$wlist[] = " tp.id_classe_ponto_de_venda = $nome_classe";
		}
			
		$cmd = "select
				cpdv.descricao as descricao,
				tp.data_inicio as inicio,
				tp.data_fim as fim,
				tp.id_tabela_precos as id
				from tabela_precos tp join classe_ponto_de_venda cpdv on cpdv.id_classe_ponto_de_venda = tp.id_classe_ponto_de_venda ";
	
		$params = Array( "query" => $cmd , "where" => $wlist);
	
		$util = new Util( $connection ) ;
		$output = $util->dataTableSource( $request, $params );
	
		return new Response( $output );
	}
	
	
	/**
	 * @Route("/admin/gravar-tabela-preco", name="admin_gravar_tabela_preco")
	 * @Method("POST")
	 */
	public function gravarTabelaPrecoAction(Request $request) {
	
		parse_str ( $request->get ( 'dados' ), $searcharray );
		
		$convertDataInicio = \date_create_from_format( 'Y-m-d', $searcharray ['editar_inicio_tabela_preco'] );
			
		$convertDataFim = \date_create_from_format( 'Y-m-d', $searcharray ['editar_fim_tabela_preco'] );

		try {
			$em = $this->getDoctrine ()->getManager ();
			$alterarTabelaPrecos = $em->getRepository ( 'AppBundle:TabelaPrecos' )->find ( $searcharray ['id_tabela_precos'] );
			$alterarTabelaPrecos->setClassePontoDeVenda ( $em->getRepository ( 'AppBundle:ClassePontoDeVenda' )->find ( $searcharray ['editar_id_classe_ponto_de_venda'] ) );
			$alterarTabelaPrecos->setDataInicio ( $convertDataInicio );
			$alterarTabelaPrecos->setDataFim ( $convertDataFim );
			$em->flush ();
	
		} catch ( \Exception $e ) {
			Util::log ( $e->getMessage () );
			return new Response ( $e->getMessage () );
		}
	
		return new Response ();
	}
	
	/**
	 * @Route("/admin/editar-tabela_preco", name="admin_editar_tabela_preco")
	 * @Method("POST")
	 */
	public function editarTabelaPrecoAction(Request $request)
	{
		$id = $request->get ( 'id' );
			
		$em         = $this->getDoctrine()->getManager();
		$connection = $em->getConnection();
		
		$cmd = "select * from classe_ponto_de_venda";
			
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result_all = $statement->fetchAll ();
		
		$cmd = "select
				cpdv.descricao as descricao,
				tp.data_inicio as inicio,
				tp.data_fim as fim,
				tp.id_tabela_precos as id,
				tp.id_classe_ponto_de_venda as id_classe_ponto_de_venda
				from tabela_precos tp join classe_ponto_de_venda cpdv on cpdv.id_classe_ponto_de_venda = tp.id_classe_ponto_de_venda
				where tp.id_tabela_precos = $id";
			
		$statement = $connection->prepare ( $cmd );
		$statement->execute ();
		$result = $statement->fetch ();
			
		$html = "<input type='hidden' name='id_tabela_precos' value='" . $result ['id'] . "'>
    			<div class = 'montar_form_tabela_preco'>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Classe</label>
    					</div>
    					<div class='col-md-6'>
    						<select name='editar_id_classe_ponto_de_venda' class='form-control'>
                                    <option value='".$result['id_classe_ponto_de_venda']."'>".$result['descricao']."</option>";
foreach($result_all as $classes){
	if($classes['id_classe_ponto_de_venda'] != $result['id_classe_ponto_de_venda']){
		$html .= "<option value='" . $classes['id_classe_ponto_de_venda'] . "'>" . $classes['descricao'] . "</option>";	
	}
}
                                $html .= "<option value=''></option>
                                		</select>
    					</div>
       				</div>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Início</label>
    					</div>
    					<div class='col-md-6'>
    						<input id='datas1' class='form-control' type='date' name='editar_inicio_tabela_preco' value='" . $result['inicio'] . "'></input>
    					</div>
       				</div>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Fim</label>
    					</div>
    					<div class='col-md-6'>
    						<input id='datas2' class='form-control' type='date' name='editar_fim_tabela_preco' value='" . $result['fim'] . "'></input>
    					</div>
       				</div>
    			</div>";
	
	
		return new Response( $html );
	}
}
