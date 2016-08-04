<?php

namespace AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util;
use AppBundle\Entity\PontoDeVenda;
use OpenBoleto\Banco\Itau;
use OpenBoleto\Agente;

class PontoDeVendaController extends FOSRestController
{
    /**
     * @Sensio\Route("/admin/pdv", name="admin_pdv")
     * @Template()
     */

    public function pdvAction(Request $request)
    {

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from classe_ponto_de_venda order by 1";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['classes'] = $statement->fetchAll();

        $cmd = "select * from uf order by nome";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['uf'] = $statement->fetchAll();

        $cmd = "select * from anfitriao order by username";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['anfitriao'] = $statement->fetchAll();

        return $d;
    }

    /**
     * @Route("/admin/buscar-pdvs", name="buscar_pdvs")
     * @Method("POST")
     */
    public function getPdvsAction(Request $request)
    {

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $wlist = array();

        if (($nome_pdv = $request->get('nome_pdv')) != '') {
            $wlist[] = " LOWER(pdv.nome) like '%$nome_pdv%'";
        }

        $cmd = "select 
				cpdv.descricao as descricao,
				u.nome as uf,
				pdv.nome as nome,
				pdv.endereco as endereco,
				pdv.telefone as telefone,
				pdv.site as site,
				a.username as username,
				pdv.data_expiracao,
				a_master.username as username_master,
				pdv.id_ponto_de_venda as id
				from 
				classe_ponto_de_venda cpdv
				join ponto_de_venda pdv on pdv.id_classe_ponto_de_venda = cpdv.id_classe_ponto_de_venda
				join uf u on u.id_uf = pdv.id_uf
				join anfitriao a on a.id_anfitriao = pdv.id_anfitriao
				left join anfitriao a_master on a_master.id_anfitriao = pdv.id_anfitriao_master";

        $params = Array( "query" => $cmd , "where" => $wlist);

        $util = new Util( $connection ) ;
        $output = $util->dataTableSource( $request, $params );

        return new Response( $output );
    }

    /**
     * @Route("/admin/gravar-pdv", name="admin_gravar_pdv")
     * @Method("POST")
     */
    public function gravarPdvAction(Request $request) {
        parse_str($request->get('dados'), $searcharray);

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $alterarPdv = $em->getRepository('AppBundle:PontoDeVenda')->find($searcharray['id_ponto_de_venda']);

        if($searcharray['editar_id_anfitriao_master_ponto_de_venda'] != ''){
            $newMaster = $em->getRepository('AppBundle:Anfitriao')->find($searcharray['editar_id_anfitriao_master_ponto_de_venda']);
            $newMaster->addRole('ROLE_MASTER');

            if($newMaster->getId() != $alterarPdv->getAnfitriaoMaster()->getId()){
                $oldMaster = $alterarPdv->getAnfitriaoMaster();
                $oldMaster->removeRole('ROLE_MASTER');
                $em->persist($oldMaster);
                $em->flush();
            }
        }

        if($searcharray['editar_id_anfitriao_ponto_de_venda'] != ''){
            $newOwner = $em->getRepository('AppBundle:Anfitriao')->find($searcharray['editar_id_anfitriao_ponto_de_venda']);
            $newOwner->addRole('ROLE_OWNER');

            if($newOwner->getId() != $alterarPdv->getAnfitriao()->getId()){
                $oldOwner = $alterarPdv->getAnfitriao();
                $oldOwner->removeRole('ROLE_OWNER');
                $em->persist($oldOwner);
                $em->flush();
            }
        }

        if( $user->hasRole( 'ROLE_SUPER' ) ) {
            $convertDataExpiracao = \date_create_from_format('Y-m-d', $searcharray ['editar_data_expiracao_ponto_de_venda']);
        }

        try {
            $alterarPdv->setClassePontoDeVenda($em->getRepository('AppBundle:ClassePontoDeVenda')->find($searcharray['editar_id_classe_ponto_de_venda']));
            $alterarPdv->setEndereco($searcharray['editar_endereco_ponto_de_venda']);
            $alterarPdv->setNome($searcharray['editar_nome_ponto_de_venda']);
            $alterarPdv->setSite($searcharray['editar_site_ponto_de_venda']);
            $alterarPdv->setTelefone($searcharray['editar_telefone_ponto_de_venda']);
            $alterarPdv->setUf($em->getRepository('AppBundle:Uf')->find($searcharray['editar_id_uf_ponto_de_venda']));
            $alterarPdv->setAnfitriao($newOwner);
            $alterarPdv->setAnfitriaoMaster($newMaster);

            if( $user->hasRole( 'ROLE_SUPER' ) ) {
                $alterarPdv->setDataExpiracao($convertDataExpiracao);
            }

            $em->persist($alterarPdv);
            $em->flush ();
        } catch ( \Exception $e ) {
            Util::log ( $e->getMessage () );
            return new Response ( $e->getMessage () );
        }

        return new Response ();
    }

    /**
     * @Route("/admin/inserir-pdv", name="admin_inserir_nova_pdv")
     * @Method("POST")
     */
    public function inserirPdvAction(Request $request) {
        parse_str($request->get('dados'), $searcharray);

        $em = $this->getDoctrine()->getManager();

        $convertDataExpiracao = \date_create_from_format( 'Y-m-d', $searcharray ['novo_data_expiracao_ponto_de_venda'] );

        $newOwner  = $em->getRepository('AppBundle:Anfitriao')->find($searcharray['novo_id_anfitriao_ponto_de_venda']);
        $newOwner->addRole("ROLE_OWNER");
        $em->persist($newOwner);
        $em->flush();

        $newMaster = $em->getRepository('AppBundle:Anfitriao')->find($searcharray['novo_id_anfitriao_master_ponto_de_venda']);
        $newOwner->addRole("ROLE_MASTER");
        $em->persist($newMaster);
        $em->flush();

        try {
            $inserirPdv = new PontoDeVenda();
            $inserirPdv->setClassePontoDeVenda($em->getRepository('AppBundle:ClassePontoDeVenda')->find($searcharray['novo_id_classe_ponto_de_venda']));
            $inserirPdv->setDataExpiracao($convertDataExpiracao);
            $inserirPdv->setEndereco($searcharray['novo_endereco_ponto_de_venda']);
            $inserirPdv->setNome($searcharray['novo_nome_ponto_de_venda']);
            $inserirPdv->setSite($searcharray['novo_site_ponto_de_venda']);
            $inserirPdv->setTelefone($searcharray['novo_telefone_ponto_de_venda']);
            $inserirPdv->setUf($em->getRepository('AppBundle:Uf')->find($searcharray['novo_id_uf_ponto_de_venda']));
            $inserirPdv->setAnfitriao($newOwner);
            $inserirPdv->setAnfitriaoMaster($newMaster);
            $em->persist($inserirPdv);
            $em->flush();
        } catch(\Exception $e){
            Util::log($e->getMessage());
            return new Response($e->getMessage());
        }

        return new Response ();
    }

    /**
     * @Route("/admin/editar-pdv", name="admin_editar_pdv")
     * @Method("POST")
     */
    public function editarPdvAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $id = $request->get ( 'id' );

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select 
				cpdv.descricao as descricao,
				u.nome as uf,
				pdv.nome as nome,
				pdv.endereco as endereco,
				pdv.telefone as telefone,
				pdv.site as site,
				a.username as username,
				pdv.data_expiracao as data_expiracao,
				pdv.id_ponto_de_venda as id_ponto_de_venda,
				pdv.id_uf as id_uf,
				pdv.id_anfitriao as id_anfitriao,
				pdv.id_anfitriao_master as id_anfitriao_master,
				pdv.id_classe_ponto_de_venda,
				a_master.username as username_master
				from 
				classe_ponto_de_venda cpdv
				join ponto_de_venda pdv on pdv.id_classe_ponto_de_venda = cpdv.id_classe_ponto_de_venda
				join uf u on u.id_uf = pdv.id_uf
				join anfitriao a on a.id_anfitriao = pdv.id_anfitriao
				left join anfitriao a_master on a_master.id_anfitriao = pdv.id_anfitriao_master
				where pdv.id_ponto_de_venda = $id";

        $statement = $connection->prepare ( $cmd );
        $statement->execute ();
        $result = $statement->fetch ();

        $cmd = "select * from classe_ponto_de_venda order by id_classe_ponto_de_venda";

        $statement = $connection->prepare ( $cmd );
        $statement->execute ();
        $result_all_classes = $statement->fetchAll ();

        $cmd = "select * from anfitriao order by username";

        $statement = $connection->prepare ( $cmd );
        $statement->execute ();
        $result_all_anfitriao = $statement->fetchAll ();

        $cmd = "select * from uf order by nome";

        $statement = $connection->prepare ( $cmd );
        $statement->execute ();
        $result_all_uf = $statement->fetchAll ();

        $html = "<input type='hidden' name='id_ponto_de_venda' value='" . $result ['id_ponto_de_venda'] . "'>
    			<div class = 'montar_form_pdv'>
    				<div class='row'>
						<div class='col-md-3'>
							<label>Classe</label>
    					</div>
    					<div class='col-md-9'>
    						<select name='editar_id_classe_ponto_de_venda' class='form-control'>
                                    <option value='".$result['id_classe_ponto_de_venda']."'>".$result['descricao']."</option>";
        foreach($result_all_classes as $classes){
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
							<label>UF</label>
    					</div>
    					<div class='col-md-9'>
    						<select name='editar_id_uf_ponto_de_venda' class='form-control'>
                                    <option value='".$result['id_uf']."'>".$result['uf']."</option>";
        foreach($result_all_uf as $ufs){
            if($ufs['id_uf'] != $result['id_uf']){
                $html .= "<option value='" . $ufs['id_uf'] . "'>" . $ufs['nome'] . "</option>";
            }
        }
        $html .= "<option value=''></option>
                                		</select>
    					</div>
       				</div>
                    <div class='row'>
						<div class='col-md-3'>
							<label>Nome</label>
    					</div>
    					<div class='col-md-9'>
    						<input class='form-control' type='text' name='editar_nome_ponto_de_venda' value='" . $result ['nome'] . "'></input>
    					</div>
       				</div>
					<div class='row'>
						<div class='col-md-3'>
							<label>Endereço</label>
    					</div>
    					<div class='col-md-9'>
    						<input class='form-control' type='text' name='editar_endereco_ponto_de_venda' value='" . $result ['endereco'] . "'></input>
    					</div>
       				</div>
					<div class='row'>
						<div class='col-md-3'>
							<label>Telefone</label>
    					</div>
    					<div class='col-md-9'>
    						<input class='form-control' type='text' name='editar_telefone_ponto_de_venda' value='" . $result ['telefone'] . "'></input>
    					</div>
       				</div>
					<div class='row'>
						<div class='col-md-3'>
							<label>Site</label>
    					</div>
    					<div class='col-md-9'>
    						<input class='form-control' type='text' name='editar_site_ponto_de_venda' value='" . $result ['site'] . "'></input>
    					</div>
       				</div>
					<div class='row'>
						<div class='col-md-3'>
							<label>Dono</label>
    					</div>
    					<div class='col-md-9'>
    						<select name='editar_id_anfitriao_ponto_de_venda' class='form-control'>
                                    <option value='".$result['id_anfitriao']."'>".$result['username']."</option>";
        foreach($result_all_anfitriao as $anfitrioes){
            if($anfitrioes['id_anfitriao'] != $result['id_anfitriao']){
                $html .= "<option value='" . $anfitrioes['id_anfitriao'] . "'>" . $anfitrioes['username'] . "</option>";
            }
        }
        $html .= "<option value=''></option>
                                		</select>
    					</div>
       				</div>
					<div class='row'>
						<div class='col-md-3'>
							<label>Master</label>
    					</div>
    					<div class='col-md-9'>
    						<select name='editar_id_anfitriao_master_ponto_de_venda' class='form-control'>
                                    <option value='".$result['id_anfitriao_master']."'>".$result['username_master']."</option>";
        foreach($result_all_anfitriao as $anfitrioes){
            if($anfitrioes['id_anfitriao'] != $result['id_anfitriao_master']){
                $html .= "<option value='" . $anfitrioes['id_anfitriao'] . "'>" . $anfitrioes['username'] . "</option>";
            }
        }
        $html .= "<option value=''></option>
                                		</select>
    					</div>
       				</div>";

        if( $user->hasRole('ROLE_SUPER') ) {
            $html .= "<div class='row'>
						<div class='col-md-3'>
							<label>Data Expiração</label>
    					</div>
    					<div class='col-md-9'>
    						<input class='form-control' type='text' name='editar_data_expiracao_ponto_de_venda' value='" . $result ['data_expiracao'] . "' placeholder='Formato = 0000-00-00 (Ano-mês-dia)'></input>
    					</div>
       				</div>";
        }

        $html .= "</div>";

        return new Response( $html );
    }

    /**
     * @Route("/admin/boleto-pdv", name="admin_boleto_pdv")
     */
    public function printBoletoAction( Request $request ) {
        $sacado = new Agente('Fernando Maia', '023.434.234-34', 'ABC 302 Bloco N', '72000-000', 'Brasília', 'DF');
        $cedente = new Agente('Empresa de cosméticos LTDA', '02.123.123/0001-11', 'CLS 403 Lj 23', '71000-000', 'Brasília', 'DF');
        $boleto = new Itau(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new \DateTime('2013-01-24'),
            'valor' => 23.00,
            'sequencial' => 12345678, // 8 dígitos
            'sacado' => $sacado,
            'cedente' => $cedente,
            'agencia' => 1724, // 4 dígitos
            'carteira' => 112, // 3 dígitos
            'conta' => 12345, // 5 dígitos

            // Parâmetro obrigatório somente se a carteira for
            // 107, 122, 142, 143, 196 ou 198
            'codigoCliente' => 12345, // 5 dígitos
            'numeroDocumento' => 1234567, // 7 dígitos
            // Parâmetros recomendáveis
            //'logoPath' => 'http://empresa.com.br/logo.jpg', // Logo da sua empresa
            'contaDv' => 2,
            'agenciaDv' => 1,
            'descricaoDemonstrativo' => array( // Até 5
                'Compra de materiais cosméticos',
                'Compra de alicate',
            ),
            'instrucoes' => array( // Até 8
                'Após o dia 30/11 cobrar 2% de mora e 1% de juros ao dia.',
                'Não receber após o vencimento.',
            ),
        ));

        return new Response( $boleto->getOutput() );
    }

    /**
     * @Route("/admin/pagseguro-pdv", name="admin_pagseguro_pdv")
     */
    public function pagseguroAction( Request $request ) {
        $url = 'https://ws.pagseguro.uol.com.br/v2/checkout';
        $fields = array(
            'email' => 'erkjpdesign@hotmail.com',
            'token' => '355C59095AAA4C8386EAE3F14608C6FE',
            'currency' => 'BRL',
            'itemId1' => '0001',
            'itemDescription1' => 'EVS de Sucesso - Plano Mensal',
            'itemAmount1' => '19.90',
            'itemQuantity1' => '1',
            'senderName' => 'Nome de quem compra',
            'senderEmail' => 'ericktatsui@gmail.com',
            'redirectURL' => 'localhost:8000/admin/pdv'
            //'notificationURL' => 'http://'
        );

        $params = "";
        foreach( $fields as $key => $value ) {
            $params .= $key .'='. $value .'&';
        }

        $params = rtrim( $params, '&' );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, count( $fields ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'application/x-www-form-urlencoded; charset=ISO-8859-1' ) );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        $postResult = simplexml_load_string( curl_exec( $ch ) );
        curl_close( $ch );

        return new RedirectResponse( 'https://pagseguro.uol.com.br/v2/checkout/payment.html?code=' . strip_tags( $postResult->code ) );
    }
}
