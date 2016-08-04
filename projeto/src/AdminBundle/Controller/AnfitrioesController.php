<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Anfitriao;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util;
use Doctrine\Common\Collections\Criteria;

class AnfitrioesController extends FOSRestController {

    /**
     * @Route("/admin/buscar-anfitrioes", name="buscar_anfitrioes")
     * @Method("POST")
     */
    public function getAnfitrioesAction(Request $request){
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $cmd = "SELECT
                    nome AS nome,
                    email AS email,
                    telefone AS telefone,
                    endereco AS endereco,
                    id_anfitriao AS id_anfitriao
                FROM anfitriao";

        $wlist = array();
        $wlist[] = "enabled = 'true'";
        
        // se nao é o proprio webmaster, nao mostra ele
        if($usr->getUsername() != 'webmaster') $wlist[] = "username <> 'webmaster'";
        
        // se nao for o super, só ve usuarios relacionados ao ponto de venda
        //if(!$usr->hasRole('ROLE_SUPER'))
        
        // define a hierarquia de regras para listar os usuarios
        if(!$usr->hasRole('ROLE_SUPER')){
            $wlist[] = "id_ponto_de_venda = " . $usr->getPontoDeVenda()->getId();
            
            $wlist[] = "roles NOT LIKE '%ROLE_SUPER%'";
         
            if(!$usr->hasRole('ROLE_MASTER')){
                $wlist[] = "roles NOT LIKE '%ROLE_MASTER%'";
                
                if(!$usr->hasRole('ROLE_OWNER')) $wlist[] = "id_anfitriao = " . $usr->getId();
            }
        }
        
        // aplica os filtros
        if(($nome_anfitriao = $request->get('nome_anfitriao')) != '')   $wlist[] = " LOWER(nome) LIKE LOWER('%$nome_anfitriao%')";
        if(($email_anfitriao = $request->get('email_anfitriao')) != '') $wlist[] = " LOWER(email) LIKE LOWER('%$email_anfitriao%')";
        
        $params = Array("query" => $cmd , "where" => $wlist);

        $util = new Util($connection);
        
        $output = $util->dataTableSource($request, $params);

        return new Response($output);
    }

    /**
     * @Route("/admin/remover-anfitriao", name="admin_remover_anfitriao")
     * @Method("POST")
     */
    public function removerAnfitriaoAction(Request $request) {
        $id_anfitriao = $request->get('id');
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();

        try {
            $em = $this->getDoctrine()->getManager();
            $removerAnfitriao = $em->getRepository('AppBundle:Anfitriao')->find($id_anfitriao);
            
            // nao deixa remover ele mesmo
            if($usr->getId() == $id_anfitriao) return new Response("Permissão negada.");
            
            // nao deixa quem nao for admin realizar remocao
            if(!$usr->hasRole('ROLE_SUPER') && !$usr->hasRole('ROLE_MASTER') && !$usr->hasRole('ROLE_OWNER')) return new Response("Permissão negada.");
            
            // se nao for super, nao deixa apagar se nao for do mesmo local
            if(!$usr->hasRole('ROLE_SUPER') && $usr->getPontoDeVenda()->getId() != $removerAnfitriao->getPontoDeVenda()->getId()) return new Response("Permissão negada.");
            
            // define a hierarquia de regras para apagar usuarios
            if($usr->hasRole('ROLE_MASTER') && ($removerAnfitriao->hasRole('ROLE_SUPER') || $removerAnfitriao->hasRole('ROLE_MASTER'))) return new Response("Permissão negada.");
            if($usr->hasRole('ROLE_OWNER') && ($removerAnfitriao->hasRole('ROLE_SUPER') || $removerAnfitriao->hasRole('ROLE_MASTER') || $removerAnfitriao->hasRole('ROLE_OWNER'))) return new Response("Permissão negada.");
            
            $removerAnfitriao->setEnabled(false);
            $em->flush();
        } catch(\Exception $e){
            Util::log($e->getMessage());
            return new Response($e->getMessage());
        }

        return new Response();
    }

    /**
     * @Route("/admin/gravar-anfitriao", name="admin_gravar_anfitriao")
     * @Method("POST")
     */
    public function gravarAnfitriaoAction(Request $request) {
        parse_str($request->get('dados'), $searcharray);

        try {
            $em = $this->getDoctrine()->getManager();
            
            $item = $em->getRepository('AppBundle:Anfitriao')->find($searcharray['id_anfitriao']);
            
            $item->setNome($searcharray['editar_nome_anfitriao']);
            $item->setEmail($searcharray['editar_email_anfitriao']);
            $item->setTelefone($searcharray['editar_telefone_anfitriao']);
            $item->setEndereco($searcharray['editar_endereco_anfitriao']);
            $item->setCpf($searcharray['editar_cpf_anfitriao']);
            $item->setFaixa($searcharray['editar_faixa_anfitriao']);
            
            if(isset($searcharray['editar_id_ponto_de_venda'])){
                $item->setPontoDeVenda($em->getRepository('AppBundle:PontoDeVenda')->find($searcharray['editar_id_ponto_de_venda']));
            }
            
            if(isset($searcharray['editar_username_anfitriao'])){
                $item->setUsername($searcharray['editar_username_anfitriao']);
            }
            
            if(trim($searcharray['editar_password_anfitriao'])){
                $item->setPlainPassword($searcharray['editar_password_anfitriao']);
            }

            if( $searcharray['editar_super_user'] == 0 && $item->hasRole('ROLE_SUPER') ) {
                    $item->removeRole('ROLE_SUPER');
            } else if( $searcharray['editar_super_user'] == 1 && !$item->hasRole('ROLE_SUPER') ) {
                    $item->addRole('ROLE_SUPER');
            }

            /*if(isset($searcharray['editar_super_user']) && $searcharray['editar_super_user']){
                $item->addRole('ROLE_SUPER');
            } elseif(isset($searcharray['novo_super_user'])) {
                $item->removeRole('ROLE_SUPER');
            }*/
            
            $item->addRole('ROLE_SALES');
            
            $em->persist($item);
            $em->flush();
        } catch(\Exception $e){
            Util::log($e->getMessage());
            return new Response($e->getMessage());
        }

        return new Response ();
    }

    /**
     * @Route("/admin/inserir-anfitriao", name="admin_inserir_novo_anfitriao")
     * @Method("POST")
     */
    public function inserirAnfitriaoAction(Request $request) {
        parse_str($request->get('dados'), $searcharray);

        $usr = $this->get('security.token_storage')->getToken()->getUser();

        try {
            $em = $this->getDoctrine()->getManager();
            
            $item = new Anfitriao();
            
            $item->setEmail($searcharray['novo_email_anfitriao']);
            $item->setUsername($searcharray['novo_username_anfitriao']);
            $item->setPlainPassword($searcharray['novo_senha_anfitriao']);
            $item->setNome($searcharray['novo_nome_anfitriao']);
            $item->setEndereco($searcharray['novo_endereco_anfitriao']);
            $item->setCpf($searcharray['novo_cpf_anfitriao']);
            $item->setFaixa($searcharray['novo_faixa_anfitriao']);
            $item->setLocked(false);
            $item->setExpired(false);
            $item->setCredentialsExpired(false);
            $item->setEnabled(true);
            
            if(isset($searcharray['novo_id_ponto_de_venda'])){
                $item->setPontoDeVenda($em->getRepository('AppBundle:PontoDeVenda')->find($searcharray['novo_id_ponto_de_venda']));
            } else {
                $item->setPontoDeVenda($em->getRepository('AppBundle:PontoDeVenda')->find($usr->getPontoDeVenda()->getId()));
            }
            
            if(isset($searcharray['novo_super_user']) && $searcharray['novo_super_user']){
                $item->addRole('ROLE_SUPER');
            }
            
            $item->addRole('ROLE_SALES');
            
            $em->persist($item);
            $em->flush();
        } catch(\Exception $e){
            Util::log($e->getMessage());
            return new Response($e->getMessage());
        }

        return new Response();
    }

    /**
     * @Route("/admin/editar-anfitriao", name="admin_editar_anfitriao")
     * @Method("POST")
     */
    public function editarAnfitriaoAction(Request $request){
        $id = $request->get('id');

        $usr = $this->get('security.token_storage')->getToken()->getUser();
            
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
            
        $item   = $em->getRepository('AppBundle:Anfitriao')->find($id);

        $optPontos = NULL;

        if( $usr->hasRole('ROLE_SUPER') ) {
            $pontos = $em->getRepository('AppBundle:PontoDeVenda')->findAll();

            foreach($pontos as $pto) {
                $optPontos.= "<option value='" . $pto->getId() . "' " . ($item->getPontoDeVenda() && $pto->getId() == $item->getPontoDeVenda()->getId() ? 'selected' : '') . ">" . $pto->getNome() . "</option>";
            }
        } else {
            $cmd  = "SELECT DISTINCT pdv.id_ponto_de_venda, ";
            $cmd .= "                pdv.nome ";
            $cmd .= "FROM anfitriao anf ";
            $cmd .= "INNER JOIN ponto_de_venda pdv ON anf.id_ponto_de_venda = pdv.id_ponto_de_venda ";
            $cmd .= "WHERE anf.id_anfitriao = " . $usr->getId();
            $cmd .= "  OR pdv.id_anfitriao = " . $usr->getId();
            $cmd .= "  OR pdv.id_anfitriao_master = " . $usr->getId();

            $statement = $connection->prepare( $cmd );
            $statement->execute();
            $pontos = $statement->fetchAll();

            foreach( $pontos as $pto ) {
                $optPontos.= "<option value='" . $pto['id_ponto_de_venda'] . "' " . ($item->getPontoDeVenda() && $pto['id_ponto_de_venda'] == $item->getPontoDeVenda()->getId() ? 'selected' : '') . ">" . $pto['nome'] . "</option>";
            }
        }
            
        $html = "<input type='hidden' name='id_anfitriao' value='" . $item->getId() . "'>
                     <div class = 'montar_form_anfitriao'>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Ponto de Venda</label>
                            </div>
                            <div class='col-md-9'>
                                <select name='editar_id_ponto_de_venda' class='form-control' " . (!$usr->hasRole("ROLE_SUPER") || $usr->getUsername() == "webmaster" ? "disabled='disabled'" : '') . ">
                                    <option value=''></option>
                                    " . $optPontos . "
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Nome</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='editar_nome_anfitriao' value='" . $item->getNome() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Email</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='editar_email_anfitriao' value='" . $item->getEmail() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Telefone</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control mask-phone' type='text' name='editar_telefone_anfitriao' value='" . $item->getTelefone() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Endereço</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='editar_endereco_anfitriao' value='" . $item->getEndereco() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>CPF</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control mask-cpf' type='text' name='editar_cpf_anfitriao' value='" . $item->getCpf() . "'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Faixa</label>
                            </div>
                            <div class='col-md-9'>
                                <select name='editar_faixa_anfitriao' class='form-control'>
                                    <option value='25' " . ($item->getFaixa() == 25 ? 'selected' : '') . ">25</option>
                                    <option value='35' " . ($item->getFaixa() == 35 ? 'selected' : '') . ">35</option>
                                    <option value='42' " . ($item->getFaixa() == 42 ? 'selected' : '') . ">42</option>
                                    <option value='50' " . ($item->getFaixa() == 50 ? 'selected' : '') . ">50</option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Usuário</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='editar_username_anfitriao' value='" . $item->getUsername() . "' disabled='disabled'></input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Senha</label>
                            </div>
                            <div class='col-md-9'>
                                <input class='form-control' type='text' name='editar_password_anfitriao' value=''></input>
                            </div>
                        </div>
                        " . ($usr->hasRole("ROLE_SUPER") ? "
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>Super-Usuário</label>
                            </div>
                            <div class='col-md-9'>
                                <input type='radio' name='editar_super_user' value='1' " . ($item->hasRole('ROLE_SUPER') ? 'checked' : '') . " /> Sim &nbsp;&nbsp;&nbsp;
                                <input type='radio' name='editar_super_user' value='0' " . (!$item->hasRole('ROLE_SUPER') ? 'checked' : '') . " /> Não
                            </div>
                        </div>
                        " : "" ) . "
                     </div>";


        return new Response( $html );
    }

    /**
     * @Sensio\Route("/admin/anfitrioes", name="admin_anfitrioes")
     * @Template()
     */

    public function anfitrioesAction(Request $request){
        $usr = $this->get('security.token_storage')->getToken()->getUser();

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        
        $pontos = array();
//        $objPontos = $em->getRepository('AppBundle:PontoDeVenda')->findAll();
//
//        foreach( $objPontos as $pto ) {
//            $pontos[] = array( "id_ponto_de_venda" => $pto->getId(), "nome" => $pto->getNome() );
//        }




        if( $usr->hasRole('ROLE_SUPER') ) {
            $objPontos = $em->getRepository('AppBundle:PontoDeVenda')->findAll();

            foreach($objPontos as $pto) {
                $pontos[] = array( "id_ponto_de_venda" => $pto->getId(), "nome" => $pto->getNome() );
            }
        } else {
            /*
             * Busca todos os pontos de venda em que o anfitrião esteja ou que ele seja dono ou que ele seja master
            */
            $cmd  = "SELECT DISTINCT pdv.id_ponto_de_venda, ";
            $cmd .= "                pdv.nome ";
            $cmd .= "FROM anfitriao anf ";
            $cmd .= "INNER JOIN ponto_de_venda pdv ON anf.id_ponto_de_venda = pdv.id_ponto_de_venda ";
            $cmd .= "WHERE anf.id_anfitriao = " . $usr->getId();
            $cmd .= "  OR pdv.id_anfitriao = " . $usr->getId();
            $cmd .= "  OR pdv.id_anfitriao_master = " . $usr->getId();

            $statement = $connection->prepare( $cmd );
            $statement->execute();
            $objPontos = $statement->fetchAll();

            foreach( $objPontos as $pto ) {
                $pontos[] = array( "id_ponto_de_venda" => $pto['id_ponto_de_venda'], "nome" => $pto['nome'] );
            }
        }

        $saida = array('id_ponto_de_venda' => $usr->getPontoDeVenda() ? $usr->getPontoDeVenda()->getId() : NULL, 
                       'tem_super'         => $usr->hasRole('ROLE_SUPER'), 
                       'pontos'            => $pontos);
        
        return $saida;
    }
}
