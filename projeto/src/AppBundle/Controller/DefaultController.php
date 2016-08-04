<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Anfitriao;
use AppBundle\Entity\ClassePontoDeVenda;
use AppBundle\Entity\PontoDeVenda;
use FOS\RestBundle\Controller\FOSRestController;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util;
use Symfony\Component\Config\Definition\Exception;
use PDO;

/**
 * @Sensio\Route("/")
 */
class DefaultController extends FOSRestController
{
    /**
     * @Sensio\Route("/sync")
     */
    public function syncAction(Request $request)
    {
        $this->writeIntoTables($request);
        $response = $this->readFromTables($request);

        if ($request->query->has('callback')) {
            $response->setCallback($request->query->get('callback'));
        }

        return $response;
    }

    /**
     * @Sensio\Route("/sync/autentica")
     */
    public function autentica(Request $request)
    {
        $anfitriao = $this->getUser();

        $dt_now = date("YmdHis");
        $dt_pdv = $anfitriao->getPontoDeVenda()->getDataExpiracao()->format("YmdHis");
        $expire = $anfitriao->isExpired() || $anfitriao->isLocked();

        if($expire || $dt_now > $dt_pdv){
            return new Response(null, Response::HTTP_UNAUTHORIZED);
        } else {
            return new Response( json_encode( [ "id_anfitriao" => $anfitriao->getId() , "id_ponto_de_venda" => $anfitriao->pontoDeVenda->getId() ]) );
        }
    }

    /**
     * @Sensio\Route("/sync/updateReceive")
     */
    public function updateReceive(Request $request){
        $import = array();
        $export = array();

        // recebe as atualizacoes do pdv e atualiza a base
        $controle = $request->request->get('controle');
        $tabelas  = $request->request->get('tables');

        if(!$controle || !is_array($controle)) $controle = array();
        if(!$tabelas || !is_array($tabelas))   $tabelas  = array();

        // importa os dados enviados do tablet
        $import = $this->processImport($tabelas);

        // exporta os dados conforme a data da tabela controle
        $export = $this->processControle($controle);

        $saida = array("import" => $import, "export" => $export);

        return new JsonResponse(json_encode($saida));
    }

    /**
     * @Sensio\Route("/admin")
     */
    public function adminAction(Request $request){
        return $this->render('::admin.html.twig');
    }

    /**
     * @Sensio\Route("/anfitrioes-evs")
     * @Template()
     */

    public function anfitrioesEvsAction(Request $request)
    {

        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from anfitriao order by 1";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['anfitrioes'] = $statement->fetchAll();

        return $d;
    }

    /**
     * @Sensio\Route("/admin/relatorio-detalhado-vendas-diario", name="admin_relatorio_detalhado_vendas_diario")
     * @Template()
     */
    public function relatorioDetalhadoVendasDiarioAction(Request $request)
    {
        return $this->render( "AppBundle:Default:relatorioDetalhadoVendasDiario.html.twig" );
    }

    /**
     * @Sensio\Route("/exportar-relatorio/{type}/{numberResults}", defaults={"numberResults" = null}, name="admin_exportar_relatorio")
     */
    public function exportRelatorio( Request $request, $type, $numberResults = null )
    {
        if( $type == 'detalhadoVendasDiario' ) {
            $result = $this->getVendaDiario( $request, [], [], $numberResults, false );
        } else if( $type == 'detalhadoAtividadesDiario' ) {
            $result = $this->getAtividadeDiario( $request, [], [], $numberResults, false );
        }  else if( $type == 'mensalGanho' ) {
            $result = $this->getMensalGanho( $request, [], [], $numberResults, false );
        }else{
            throw $this->createNotFoundException( "O parametro passado em 'type' é inválido." );
        }

        $util = new Util();
        $phpExcelObject = $util->queryToExcel( $result, "EVS de Sucesso", "Evs de Sucesso" );

        $response = $util->responseExcelDownload( $phpExcelObject, $type );

        return $response;
    }

    /**
     * @Route("/buscar-venda-diario", name="buscar_venda_diario")
     */
    public function getVendaDiarioAction(Request $request)
    {
        $wlist = array();
        $wlist[] = " v.data_fim::text not like '1970%'";

        if (($data_venda = $request->get('data_venda')) != '') {
            $wlist[] = " v.data_fim::text like '$data_venda%'";
        }

        $relatorios = $this->getVendaDiario( $request, $wlist, [] );

        $util = new Util();
        $output = $util->dataTableSourceUncoupled( $relatorios, $request );

        return new Response( $output );
    }

    public function getVendaDiario( $request, $whereList, $groupList, $limit = null, $fetchByNum = true ) {
        $cmd = "select v.data_fim as data_fim, 
				c.nome as nome_cliente, 
				cat.nome as nome_categoria, 
				p.nome as nome_produto, 
				vp.quantidade, 
				vp.valor_venda, 
				vp.valor_venda * vp.quantidade as total 
					from venda v
					join venda_produto vp on v.id_venda = vp.id_venda
					join cliente c on c.id_cliente = v.id_cliente
					join produto p on p.id_produto = vp.id_produto
					join categoria cat on cat.id_categoria = p.id_categoria
					join anfitriao a on a.id_anfitriao = c.id_anfitriao
					join ponto_de_venda pdv on pdv.id_ponto_de_venda = a.id_ponto_de_venda";

        if( $limit == null ){
            $limit = $request->get('length');
        }

        $params = array(
            'sqlCmd' => $cmd,
            'start' => $request->get('start'),
            'length' => $limit,
            'order' => $request->get('order'),
            'iDisplayStart' => $request->get('iDisplayStart'),
            'iDisplayLength' => $request->get('iDisplayLength'),
            'aWhere' => $whereList,
            'sGroup' => $groupList,
            'fetchByNum' => $fetchByNum
        );

        $relatorios = $this->getRelatorios( $params );

        return $relatorios;
    }

    public function getRelatorios( $params )
    {
        if ($params['iDisplayStart'] == null || $params['iDisplayLength'] == null) {
            $iDisplayStart = $params['start'] - 0;
            $iDisplayLength = $params['length'] - 0;
        } else {
            $iDisplayStart = $params['iDisplayStart'] - 0;
            $iDisplayLength = $params['iDisplayLength'] - 0;
        }

        if ($iDisplayLength == 0) {
            $iDisplayLength = $params['length'] - 0;
        }

        if (count($params['aWhere']) > 0) {
            $oWhere = " WHERE " . join(" AND ", $params['aWhere']);
        } else {
            $oWhere = "";
        }

        $sGroup = "";
        if($params['sGroup'] && count($params['sGroup']) > 0){
            $sGroup = " GROUP BY " . $params['sGroup'];
        }

        $sLimit = "";
        if ($iDisplayLength > 0) {
            $sLimit = " OFFSET " . ($iDisplayStart) . " LIMIT " . ($iDisplayLength - 0);
        }

        $sOrder = "";
        if (($objOrderBy = $params['order']) != null) {
            $sOrder = " ORDER BY " . ($objOrderBy[0]['column'] + 1) . " " . $objOrderBy[0]['dir'];
        }

        $cmd = $params['sqlCmd'];
        $cmd = $cmd . " " . $oWhere . " " . $sGroup . " " . $sOrder . " " . $sLimit;

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare( $cmd );
        $statement->execute();

        if( $params['fetchByNum'] ) {
            $result = $statement->fetchAll(PDO::FETCH_NUM);
        }else{
            $result = $statement->fetchAll();
        }

        return $result;
    }

    /**
     * @Sensio\Route("/admin/relatorio-detalhado-atividades-diario", name="admin_relatorio_detalhado_atividades_diario")
     * @Template()
     */
    public function relatorioDetalhadoAtividadesDiarioAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from venda_produto order by 1";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['itens'] = $statement->fetchAll();

        return $d;
    }

    /**
     * @Route("/buscar-atividade-diario", name="buscar_atividade_diario")
     * @Method("POST")
     */
    public function getAtividadeDiarioAction(Request $request)
    {
        $wlist = array();
        $wlist[] = " v.data_fim::text not like '1970%'";

        if (($data_venda = $request->get('data_venda')) != '') {
            $wlist[] = " v.data_fim::text like '$data_venda%'";
        }

        $relatorio = $this->getAtividadeDiario( $request, $wlist, [] );

        $util = new Util() ;
        $output = $util->dataTableSourceUncoupled( $relatorio, $request );

        return new Response( $output );
    }

    public function getAtividadeDiario( $request, $whereList, $groupList, $limit = null, $fetchByNum = true ) {
        $cmd = "select 
				cli.nome as nome_cliente, 
				ti.descricao as descricao_tipo_cliente, 
				p.apelido as acesso, 
				case when id_parent is null then vp.valor_venda * vp.quantidade end as extras, 
				vp.valor_venda * vp.quantidade as total, 
				vp.pontos_de_volume as pontos_de_volume, 
				fp.descricao as pagamento, 
				vp.valor_venda * vp.quantidade - vp.valor_custo * vp.quantidade as ganhos 
				from venda v
				join venda_produto vp on vp.id_venda = v.id_venda 
				join produto p on vp.id_produto = p.id_produto
				join item_tabela_precos itp on itp.id_produto = p.id_produto
				join categoria c on p.id_categoria = c.id_categoria
				join cliente cli on cli.id_cliente = v.id_cliente
				join tipo_indicacao ti on ti.id = cli.id_tipo_indicacao
				join forma_pagamento fp on fp.id = vp.id_forma_pagamento
				join anfitriao a on a.id_anfitriao = v.id_anfitriao
				join ponto_de_venda pdv on pdv.id_ponto_de_venda = a.id_ponto_de_venda and pdv.id_uf = itp.id_uf";

        if( $limit == null ){
            $limit = $request->get('length');
        }

        $params = array(
            'sqlCmd' => $cmd,
            'start' => $request->get('start'),
            'length' => $limit,
            'order' => $request->get('order'),
            'iDisplayStart' => $request->get('iDisplayStart'),
            'iDisplayLength' => $request->get('iDisplayLength'),
            'aWhere' => $whereList,
            'sGroup' => $groupList,
            'fetchByNum' => $fetchByNum
        );

        $relatorio = $this->getRelatorios( $params );

        return $relatorio;
    }

    /**
     * @Sensio\Route("/admin/relatorio-mensal-ganho", name="admin_relatorio_mensal_ganho")
     * @Template()
     */
    public function relatorioMensalGanhoAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $cmd = "select * from venda_produto order by 1";
        $statement = $connection->prepare($cmd);
        $statement->execute();
        $d['itens'] = $statement->fetchAll();

        return $d;
    }

    /**
     * @Route("/buscar-mensal-ganho", name="buscar_mensal_ganho")
     * @Method("POST")
     */
    public function getMensalGanhoAction(Request $request)
    {
        $relatorios = $this->getMensalGanho( $request, [], [] );

        $util = new Util();
        $output = $util->dataTableSourceUncoupled( $relatorios, $request );

        return new Response( $output );
    }

    public function getMensalGanho( $request, $whereList, $groupList, $limit = null, $fetchByNum = true ) {
        $concat_sql = "";

        if (($data_venda = $request->get('data_venda')) != '') {
            $data_venda = substr($data_venda, 0, 4);
            $concat_sql = " and v.data_fim::text like '$data_venda%'";
        }

        $cmd = "select date_part('year',v.data_fim) as ano , 
				date_part('month'::text,v.data_fim) as mes, 
    			sum(vp.quantidade) as acessos, 
				count(distinct(date_part('day'::text,v.data_fim))) as dias_de_venda, 
				sum(vp.quantidade) / count(distinct(date_part('day'::text,v.data_fim))) as media_acesso,
				sum(vp.pontos_de_volume) as pontos_de_volume,
				sum(vp.quantidade * vp.valor_venda) - sum(vp.quantidade * vp.valor_custo) as lucro
				from venda v
				join venda_produto vp on vp.id_venda = v.id_venda
				join produto p on p.id_produto = vp.id_produto
				join categoria c on p.id_categoria = c.id_categoria
    			where v.data_fim::text not like '1970%' and c.nome = 'Acesso' $concat_sql 
    			group by date_part('year',v.data_fim), date_part('month'::text, v.data_fim)";

        if( $limit == null ){
            $limit = $request->get('length');
        }

        $params = array(
            'sqlCmd' => $cmd,
            'start' => $request->get('start'),
            'length' => $limit,
            'order' => $request->get('order'),
            'iDisplayStart' => $request->get('iDisplayStart'),
            'iDisplayLength' => $request->get('iDisplayLength'),
            'aWhere' => $whereList,
            'sGroup' => $groupList,
            'fetchByNum' => $fetchByNum
        );

        $relatorio = $this->getRelatorios( $params );

        return $relatorio;
    }

    /**
     * @param Request $request
     */
    private function writeIntoTables(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = json_decode($request->getContent(), true);

            if (isset($data['cliente'])) {
                $this->saveClientes($data['cliente']);
            }

            if (isset($data['venda'])) {
                $this->saveVendas($data['venda']);
            }

            if (isset($data['venda_produto'])) {
                $this->saveVendaProdutos($data['venda_produto']);
            }

            if (isset($data['cartela_digital'])) {
                $this->saveCartelaDigital($data['cartela_digital']);
            }

            if (isset($data['estrelas'])) {
                $this->saveEstrelas($data['estrelas']);
            }

            if (isset($data['item_pdv_tabela_precos'])) {
                $this->saveItemPdvTabelaPrecos($data['item_pdv_tabela_precos']);
            }

            if (isset($data['cliente_bioimpedancia'])) {
                $this->saveClienteBioimpedancia($data['cliente_bioimpedancia']);
            }

            if (isset($data['cliente_medidas'])) {
                $this->saveClienteMedidas($data['cliente_medidas']);
            }

            if (isset($data['cliente_foto'])) {
                $this->saveClienteFoto($data['cliente_foto']);
            }

            if (isset($data['custos_mensais'])) {
                $this->saveCustosMensais($data['custos_mensais']);
            }
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    private function readFromTables(Request $request)
    {
        /** @var Anfitriao $anfitriao */
        $anfitriao = $this->getUser();

        /** @var PontoDeVenda $pontoDeVenda */
        $pontoDeVenda = $anfitriao->getPontoDeVenda();

        /** @var ClassePontoDeVenda $classePontoDeVenda */
        $classePontoDeVenda = $anfitriao->getPontoDeVenda()->getClassePontoDeVenda();

        return new JsonResponse([
            'anfitriao'             	=> $this->findAnfitrioesByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('anfitriao') ? : '1970-01-01 00:00:00')),
            'categoria'             	=> $this->findCategoriasAfterDta(new \DateTime($request->query->get('categoria') ? : '1970-01-01 00:00:00')),
            'classe_ponto_de_venda' 	=> $this->findClassesPontoDeVendaByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('classe_ponto_de_venda') ? : '1970-01-01 00:00:00')),
            'cliente'               	=> $this->findClientesByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('cliente') ? : '1970-01-01 00:00:00')),
            'item_tabela_precos'    	=> $this->findItensTabelaPrecosByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('item_tabela_precos') ? : '1970-01-01 00:00:00')),
            'ponto_de_venda'        	=> $this->findPontosDeVendaByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('ponto_de_venda') ? : '1970-01-01 00:00:00')),
            'produto'               	=> $this->findProdutosByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('produto') ? : '1970-01-01 00:00:00')),
            'tabela_precos'         	=> $this->findTabelasPrecosByClassePontoDeVendaAfterDta($classePontoDeVenda, new \DateTime($request->query->get('tabela_precos') ? : '1970-01-01 00:00:00')),
            'uf'                        => $this->findUf(new \DateTime($request->query->get('uf') ? : '1970-01-01 00:00:00')),
            'tag'                   	=> $this->findTagsAfterDta(new \DateTime($request->query->get('tag') ? : '1970-01-01 00:00:00')),
            'tag_produto'           	=> $this->findTagProdutosByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('tag_produto') ? : '1970-01-01 00:00:00')),
            'venda'                 	=> $this->findVendasByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('venda') ? : '1970-01-01 00:00:00')),
            'venda_produto'         	=> $this->findVendaProdutosByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('venda_produto') ? : '1970-01-01 00:00:00')),
            'grade_consumo'         	=> $this->findGradeConsumo(new \DateTime($request->query->get('grade_consumo') ? : '1970-01-01 00:00:00')),
            'grade_consumo_pdv'        	=> $this->findGradeConsumoPdv($pontoDeVenda, new \DateTime($request->query->get('grade_consumo_pdv') ? : '1970-01-01 00:00:00')),
            'produto'         	        => $this->findProduto(new \DateTime($request->query->get('produto') ? : '1970-01-01 00:00:00')),
            'produto_imagem'        	=> $this->findProdutoImagem(new \DateTime($request->query->get('produto_imagem') ? : '1970-01-01 00:00:00')),
            'cartela_digital'       	=> $this->findCartelaDigitalByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('cartela_digital') ? : '1970-01-01 00:00:00')),
            'forma_pagamento'       	=> $this->findFormaPagamentoAfterDta(new \DateTime($request->query->get('forma_pagamento') ? : '1970-01-01 00:00:00')),
            'tipo_indicacao'        	=> $this->findTipoIndicacaoAfterDta(new \DateTime($request->query->get('tipo_indicacao') ? : '1970-01-01 00:00:00')),
            'origem_estrela'        	=> $this->findOrigemEstrelaAfterDta(new \DateTime($request->query->get('origem_estrela') ? : '1970-01-01 00:00:00')),
            'estrelas'       	    	=> $this->findEstrelasByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('estrelas') ? : '1970-01-01 00:00:00')),
            'cliente_bioimpedancia' 	=> $this->findClienteBioimpedanciaAfterDta(new \DateTime($request->query->get('cliente_bioimpedancia') ? : '1970-01-01 00:00:00')),
            'cliente_medidas'       	=> $this->findClienteMedidasAfterDta(new \DateTime($request->query->get('cliente_medidas') ? : '1970-01-01 00:00:00')),
            'cliente_foto'          	=> $this->findClienteFotoAfterDta(new \DateTime($request->query->get('cliente_foto') ? : '1970-01-01 00:00:00')),
            'item_pdv_tabela_precos'	=> $this->findItemPdvTabelaPrecosByPontoDeVendaAfterDta($pontoDeVenda, new \DateTime($request->query->get('item_pdv_tabela_precos') ? : '1970-01-01 00:00:00')),
            'historico_padrao_custos'	=> $this->findHistoricoPadraoCustosAfterDta(new \DateTime($request->query->get('historico_padrao_custos') ? : '1970-01-01 00:00:00')),
            'custos_mensais'		=> $this->findCustosMensaisAfterDta(new \DateTime($request->query->get('custos_mensais') ? : '1970-01-01 00:00:00'))
        ]);
    }

    /**
     * @param array  $tabelas
     * @param string $lastSync
     *
     * @return array
     */
    private function processControle(array $controle = []){
        $saida = array();
        $cfg   = array();

        /** @var Anfitriao $anfitriao */
        $anfitriao = $this->getUser();

        /** @var PontoDeVenda $pontoDeVenda */
        $cfg['pontoDeVenda'] = $anfitriao->getPontoDeVenda();

        /** @var ClassePontoDeVenda $classePontoDeVenda */
        $cfg['classePontoDeVenda'] = $anfitriao->getPontoDeVenda()->getClassePontoDeVenda();

        foreach($controle as $tab){
            $itens = $this->getInfoTable($tab['tabela'], $tab['dta'], $cfg);
            if(count($itens)) $saida[$tab['tabela']] = $itens;
        }

        return $saida;
    }


    /**
     * @param array  $tabelas
     * @param string $lastSync
     *
     * @return array
     */
    private function processImport(array $tabelas = []){
        $saida = array();

        foreach($tabelas as $tabela => $linhas){
            foreach($linhas as $idx => $val) $linhas[$idx]["dta"] = date("Y-m-d H:i:s");

            switch($tabela){
                case 'cliente':                 $this->saveClientes($linhas);               break;
                case 'venda':                   $this->saveVendas($linhas);                 break;
                case 'venda_produto':           $this->saveVendaProdutos($linhas);          break;
                case 'cartela_digital':         $this->saveCartelaDigital($linhas);         break;
                case 'estrelas':                $this->saveEstrelas($linhas);               break;
                case 'item_pdv_tabela_precos':  $this->saveItemPdvTabelaPrecos($linhas);    break;
                case 'cliente_bioimpedancia':   $this->saveClienteBioimpedancia($linhas);   break;
                case 'cliente_bioimpedancia':   $this->saveClienteMedidas($linhas);         break;
                case 'cliente_medidas':         $this->saveClienteMedidas($linhas);         break;
                case 'cliente_foto':            $this->saveClienteFoto($linhas);            break;
                case 'custos_mensais':          $this->saveCustosMensais($linhas);          break;

            }

            $saida[] = array("tabela" => $tabela, "registros" => count($linhas));
        }

        return $saida;
    }


    /**
     * @param string $tabela
     * @param string $lastSync
     * @param array  $cfg
     *
     * @return array
     */
    private function getInfoTable($tabela = false, $lastSync = false, array $cfg = [])
    {
        $saida = array();

        if(!$lastSync) $lastSync = '2016-01-01 00:00:00';

        switch($tabela){
            case 'anfitriao':               $saida = $this->findAnfitrioesByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                   break;
            case 'categoria':               $saida = $this->findCategoriasAfterDta(new \DateTime($lastSync));                                                       break;
            case 'classe_ponto_de_venda':   $saida = $this->findClassesPontoDeVendaByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));          break;
            case 'cliente':                 $saida = $this->findClientesByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                     break;
            case 'item_tabela_precos':      $saida = $this->findItensTabelaPrecosByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));            break;
            case 'ponto_de_venda':          $saida = $this->findPontosDeVendaByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                break;
            case 'produto':                 $saida = $this->findProdutosByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                     break;
            case 'tabela_precos':           $saida = $this->findTabelasPrecosByClassePontoDeVendaAfterDta($cfg['classePontoDeVenda'], new \DateTime($lastSync));    break;
            case 'uf':                      $saida = $this->findUf(new \DateTime($lastSync));                                                                       break;
            case 'tag':                     $saida = $this->findTagsAfterDta(new \DateTime($lastSync));                                                             break;
            case 'tag_produto':             $saida = $this->findTagProdutosByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                  break;
            case 'venda':                   $saida = $this->findVendasByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                       break;
            case 'venda_produto':           $saida = $this->findVendaProdutosByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                break;
            case 'grade_consumo':           $saida = $this->findGradeConsumo(new \DateTime($lastSync));                                                             break;
            case 'grade_consumo_pdv':       $saida = $this->findGradeConsumoPdv($cfg['pontoDeVenda'], new \DateTime($lastSync));                                                             break;
            case 'produto':                 $saida = $this->findProduto(new \DateTime($lastSync));                                                                  break;
            case 'produto_imagem':          $saida = $this->findProdutoImagem(new \DateTime($lastSync));                                                            break;
            case 'cartela_digital':         $saida = $this->findCartelaDigitalByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));               break;
            case 'forma_pagamento':         $saida = $this->findFormaPagamentoAfterDta(new \DateTime($lastSync));                                                   break;
            case 'tipo_indicacao':          $saida = $this->findTipoIndicacaoAfterDta(new \DateTime($lastSync));                                                    break;
            case 'origem_estrela':          $saida = $this->findOrigemEstrelaAfterDta(new \DateTime($lastSync));                                                    break;
            case 'estrelas':                $saida = $this->findEstrelasByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));                     break;
            case 'cliente_bioimpedancia':   $saida = $this->findClienteBioimpedanciaAfterDta(new \DateTime($lastSync));                                             break;
            case 'cliente_medidas':         $saida = $this->findClienteMedidasAfterDta(new \DateTime($lastSync));                                                   break;
            case 'cliente_foto':            $saida = $this->findClienteFotoAfterDta(new \DateTime($lastSync));                                                      break;
            case 'item_pdv_tabela_precos':  $saida = $this->findItemPdvTabelaPrecosByPontoDeVendaAfterDta($cfg['pontoDeVenda'], new \DateTime($lastSync));          break;
            case 'historico_padrao_custos': $saida = $this->findHistoricoPadraoCustosAfterDta(new \DateTime($lastSync));                                            break;
            case 'custos_mensais':          $saida = $this->findCustosMensaisAfterDta(new \DateTime($lastSync));                                                    break;
        }

        return $saida;
    }

    /**
     * @param string $query
     * @param array  $parameters
     *
     * @return array
     */
    private function fetchAll($query, array $parameters = [])
    {
        return $this
            ->getDoctrine()
            ->getConnection()
            ->fetchAll(trim(preg_replace('/\s*' . PHP_EOL . '\s*/', ' ', $query)), $parameters);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findAnfitrioesByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT a.*
            FROM anfitriao a
            WHERE a.id_ponto_de_venda = ?
            AND a.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findCategoriasAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT c.*
            FROM categoria c
            WHERE c.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findClassesPontoDeVendaByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT cpdv.*
            FROM classe_ponto_de_venda cpdv
            INNER JOIN ponto_de_venda pdv ON pdv.id_classe_ponto_de_venda = cpdv.id_classe_ponto_de_venda
            WHERE pdv.id_ponto_de_venda = ?
            AND cpdv.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findClientesByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT c.*
            FROM cliente c
            INNER JOIN anfitriao a ON a.id_anfitriao = c.id_anfitriao
            WHERE a.id_ponto_de_venda = ?
            AND c.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findItensTabelaPrecosByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT itb.*
            FROM item_tabela_precos itb
            INNER JOIN tabela_precos tp ON tp.id_tabela_precos = itb.id_tabela_precos
            WHERE tp.id_classe_ponto_de_venda = ?
            AND itb.id_uf = ?
            AND itb.dta > ?', [ $pontoDeVenda->getClassePontoDeVenda()->getId(), $pontoDeVenda->getUf()->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findPontosDeVendaByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT pdv.*
            FROM ponto_de_venda pdv
            WHERE pdv.id_ponto_de_venda = ?
            AND pdv.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findProdutosByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT p.*
            FROM produto p
            INNER JOIN item_tabela_precos itb ON itb.id_produto = p.id_produto
            INNER JOIN tabela_precos tp ON tp.id_tabela_precos = itb.id_tabela_precos
            WHERE tp.id_classe_ponto_de_venda = ?
            AND itb.id_uf = ?
            AND p.dta > ?', [ $pontoDeVenda->getClassePontoDeVenda()->getId(), $pontoDeVenda->getUf()->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findCartelaDigitalByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT cd.*
            FROM cartela_digital cd 
    		INNER JOIN ponto_de_venda pdv ON cd.id_ponto_de_venda = pdv.id_ponto_de_venda
    	    WHERE cd.id_ponto_de_venda = ? AND cd.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findEstrelasByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT e.*
            FROM estrelas e
    		INNER JOIN ponto_de_venda pdv ON e.id_ponto_de_venda = pdv.id_ponto_de_venda
    	    WHERE e.id_ponto_de_venda = ? AND e.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findItemPdvTabelaPrecosByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT i.*
            FROM item_pdv_tabela_precos i
    		INNER JOIN ponto_de_venda pdv ON i.id_ponto_de_venda = pdv.id_ponto_de_venda
    	    WHERE i.id_ponto_de_venda = ? AND i.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param ClassePontoDeVenda $classePontoDeVenda
     * @param \DateTime          $dta
     *
     * @return array
     */
    private function findTabelasPrecosByClassePontoDeVendaAfterDta(ClassePontoDeVenda $classePontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT tp.*
            FROM tabela_precos tp
            WHERE tp.id_classe_ponto_de_venda = ?
            AND tp.dta > ?', [ $classePontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findTagsAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT t.*
            FROM tag t
            WHERE t.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findGradeConsumo(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT gc.*
            FROM grade_consumo gc WHERE gc.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findGradeConsumoPdv(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT  gcp.*
            FROM    grade_consumo_pdv gcp 
            WHERE   gcp.id_ponto_de_venda = ? 
                AND gcp.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findClienteBioimpedanciaAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT cbi.*
            FROM cliente_bioimpedancia cbi WHERE cbi.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findClienteMedidasAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT cm.*
            FROM cliente_medidas cm WHERE cm.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findClienteFotoAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT cf.*
            FROM cliente_foto cf WHERE cf.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findHistoricoPadraoCustosAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT hpc.*
            FROM historico_padrao_custos hpc WHERE hpc.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findCustosMensaisAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT cm.*
            FROM custos_mensais cm WHERE cm.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findProdutoImagem(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT pi.*
            FROM produto_imagem pi WHERE pi.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findProduto(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT p.*
            FROM produto p WHERE p.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findUf(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT u.*
            FROM uf u WHERE u.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findFormaPagamentoAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT fp.*
            FROM forma_pagamento fp WHERE fp.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findOrigemEstrelaAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT oe.*
            FROM origem_estrela oe WHERE oe.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param \DateTime $dta
     *
     * @return array
     */
    private function findTipoIndicacaoAfterDta(\DateTime $dta)
    {
        return $this->fetchAll('
            SELECT ti.*
            FROM tipo_indicacao ti WHERE ti.dta > ?', [ $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findTagProdutosByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT tp1.*
            FROM tag_produto tp1
            INNER JOIN produto p ON p.id_produto = tp1.id_produto
            INNER JOIN item_tabela_precos itb ON itb.id_produto = p.id_produto
            INNER JOIN tabela_precos tp2 ON tp2.id_tabela_precos = itb.id_tabela_precos
            WHERE tp2.id_classe_ponto_de_venda = ?
            AND itb.id_uf = ?
            AND tp1.dta > ?', [ $pontoDeVenda->getClassePontoDeVenda()->getId(), $pontoDeVenda->getUf()->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findVendasByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT v.*
            FROM venda v
            INNER JOIN cliente c ON c.id_cliente = v.id_cliente
            INNER JOIN anfitriao a ON a.id_anfitriao = c.id_anfitriao
            WHERE a.id_ponto_de_venda = ?
            AND v.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param PontoDeVenda $pontoDeVenda
     * @param \DateTime    $dta
     *
     * @return array
     */
    private function findVendaProdutosByPontoDeVendaAfterDta(PontoDeVenda $pontoDeVenda, \DateTime $dta)
    {
        return $this->fetchAll('
            SELECT vp.*
            FROM venda_produto vp
            INNER JOIN venda v on v.id_venda = vp.id_venda
            INNER JOIN cliente c ON c.id_cliente = v.id_cliente
            INNER JOIN anfitriao a ON a.id_anfitriao = c.id_anfitriao
            WHERE a.id_ponto_de_venda = ?
            AND vp.dta > ?', [ $pontoDeVenda->getId(), $dta->format('Y-m-d H:i:s') ]);
    }

    /**
     * @param array $clientes
     */
    private function saveClientes(array $clientes)
    {

        foreach ($clientes as $cliente) {
            if(!$cliente["id_anfitriao"])       $cliente["id_anfitriao"]       = 0;
            if(!$cliente["nome"])               $cliente["nome"]               = "";
            if(!$cliente["email"])              $cliente["email"]              = "";
            if(!$cliente["foto"])               $cliente["foto"]               = "";
            if(!$cliente["id_tipo_indicacao"])  $cliente["id_tipo_indicacao"]  = NULL;
            if(!$cliente["id_cliente_indicou"]) $cliente["id_cliente_indicou"] = NULL;
            if(!$cliente["telefone_celular"])   $cliente["telefone_celular"]   = NULL;
            if(!$cliente["data_nascimento"])    $cliente["data_nascimento"]    = NULL;
            if(!$cliente["senha"])              $cliente["senha"]              = NULL;
            if(!$cliente["sexo"])               $cliente["sexo"]               = NULL;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM cliente WHERE id_cliente = ?', [ $cliente['id_cliente'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('cliente', $cliente, [ 'id_cliente' => $cliente['id_cliente']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('cliente', $cliente);
            }
        }
    }

    /**
     * @param array $vendas
     */
    private function saveVendas(array $vendas)
    {
        foreach ($vendas as $venda) {
            if(!$venda["id_venda"])     $venda["id_venda"]     = "";
            if(!$venda["id_cliente"])   $venda["id_cliente"]   = "";
            if(!$venda["status"])       $venda["status"]       = 0;
            if(!$venda["id_anfitriao"]) $venda["id_anfitriao"] = NULL;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM venda WHERE id_venda = ?', [ $venda['id_venda'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('venda', $venda, [ 'id_venda' => $venda['id_venda']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('venda', $venda);
            }
        }
    }

    /**
     * @param array $cartelaDigital
     */
    private function saveCartelaDigital(array $cartelaDigital)
    {
        foreach ($cartelaDigital as $cartela) {
            if(!$cartela["id_cliente"])           $cartela["id_cliente"]           = "";
            if(!$cartela["id_ponto_de_venda"])    $cartela["id_ponto_de_venda"]    = 0;
            if(!$cartela["data_hora_aquisicao"])  $cartela["data_hora_aquisicao"]  = NULL;
            if(!$cartela["data_hora_utilizacao"]) $cartela["data_hora_utilizacao"] = NULL;
            if(!$cartela["tipo"])                 $cartela["tipo"]                 = 0;
            if(!$cartela["ativo"])                $cartela["ativo"]                = 0;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM cartela_digital WHERE id = ?', [ $cartela['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('cartela_digital', $cartela, [ 'id' => $cartela['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('cartela_digital', $cartela);
            }
        }
    }

    /**
     * @param array $estrelas
     */
    private function saveEstrelas(array $estrelas)
    {
        foreach ($estrelas as $estrela) {
            if(!$estrela["id_cliente"])        $estrela["id_cliente"]        = "";
            if(!$estrela["id_ponto_de_venda"]) $estrela["id_ponto_de_venda"] = 0;
            if(!$estrela["id_venda"])          $estrela["id_venda"]          = NULL;
            if(!$estrela["id_origem_estrela"]) $estrela["id_origem_estrela"] = 0;
            if(!$estrela["quantidade"])        $estrela["quantidade"]        = "";

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM estrelas WHERE id = ?', [ $estrela['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('estrelas', $estrela, [ 'id' => $estrela['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('estrelas', $estrela);
            }
        }
    }

    /**
     * @param array $cliente_bioimpedancia
     */
    private function saveClienteBioimpedancia(array $cliente_bioimpedancia)
    {
        foreach ($cliente_bioimpedancia as $bioimpedancia) {
            if(!$bioimpedancia["id_cliente"])       $bioimpedancia["id_cliente"]       = "";
            if(!$bioimpedancia["imc"])              $bioimpedancia["imc"]              = NULL;
            if(!$bioimpedancia["perc_gordura"])     $bioimpedancia["perc_gordura"]     = NULL;
            if(!$bioimpedancia["perc_musculo"])     $bioimpedancia["perc_musculo"]     = NULL;
            if(!$bioimpedancia["metabolismo"])      $bioimpedancia["metabolismo"]      = NULL;
            if(!$bioimpedancia["gordura_visceral"]) $bioimpedancia["gordura_visceral"] = NULL;
            if(!$bioimpedancia["idade_corporal"])   $bioimpedancia["idade_corporal"]   = NULL;
            if(!$bioimpedancia["token"])            $bioimpedancia["token"]            = NULL;
            if(!$bioimpedancia["ativo"])            $bioimpedancia["ativo"]            = false;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM cliente_bioimpedancia WHERE id = ?', [ $bioimpedancia['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('cliente_bioimpedancia', $bioimpedancia, [ 'id' => $bioimpedancia['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('cliente_bioimpedancia', $bioimpedancia);
            }
        }
    }

    /**
     * @param array $cliente_medidas
     */
    private function saveClienteMedidas(array $cliente_medidas)
    {
        foreach ($cliente_medidas as $medidas) {
            if(!$medidas["id_cliente"]) $medidas["id_cliente"] = "";
            if(!$medidas["peso"])       $medidas["peso"]       = NULL;
            if(!$medidas["altura"])     $medidas["altura"]     = NULL;
            if(!$medidas["torax"])      $medidas["torax"]      = NULL;
            if(!$medidas["cintura"])    $medidas["cintura"]    = NULL;
            if(!$medidas["barriga"])    $medidas["barriga"]    = NULL;
            if(!$medidas["quadril"])    $medidas["quadril"]    = NULL;
            if(!$medidas["token"])      $medidas["token"]      = NULL;
            if(!$medidas["ativo"])      $medidas["ativo"]      = false;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM cliente_medidas WHERE id = ?', [ $medidas['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('cliente_medidas', $medidas, [ 'id' => $medidas['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('cliente_medidas', $medidas);
            }
        }
    }

    /**
     * @param array $cliente_foto
     */
    private function saveClienteFoto(array $cliente_foto)
    {
        foreach ($cliente_foto as $foto) {
            if(!$foto["id_cliente"]) $foto["id_cliente"] = "";
            if(!$foto["imagem"])     $foto["imagem"]     = "";
            if(!$foto["token"])      $foto["token"]      = NULL;
            if(!$foto["ativo"])      $foto["ativo"]      = false;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM cliente_foto WHERE id = ?', [ $foto['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('cliente_foto', $foto, [ 'id' => $foto['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('cliente_foto', $foto);
            }
        }
    }

    /**
     * @param array $custos_mensais
     */
    private function saveCustosMensais(array $custos_mensais)
    {
        foreach ($custos_mensais as $custo) {
            if(!$custo["id_historico"]) $custo["id_historico"] = 0;
            if(!$custo["valor"])        $custo["valor"]        = 0;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM custos_mensais WHERE id = ?', [ $custo['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('custos_mensais', $custo, [ 'id' => $custo['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('custos_mensais', $custo);
            }
        }
    }

    /**
     * @param array $itemPdvTabelaPrecos
     */
    private function saveItemPdvTabelaPrecos(array $itemPdvTabelaPrecos)
    {
        foreach ($itemPdvTabelaPrecos as $itemPdv) {
            if(!$itemPdv["id_produto"])        $itemPdv["id_produto"]        = 0;
            if(!$itemPdv["id_ponto_de_venda"]) $itemPdv["id_ponto_de_venda"] = 0;
            if(!$itemPdv["preco"])             $itemPdv["preco"]             = 0;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM item_pdv_tabela_precos WHERE id = ?', [ $itemPdv['id'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('item_pdv_tabela_precos', $itemPdv, [ 'id' => $itemPdv['id']]);
            } else {
                $this->getDoctrine()->getConnection()->insert('item_pdv_tabela_precos', $itemPdv);
            }
        }
    }

    /**
     * @param array $vendaProdutos
     */
    private function saveVendaProdutos(array $vendaProdutos)
    {
        foreach ($vendaProdutos as $vendaProduto) {
            if(!$vendaProduto["id_venda_produto"])   $vendaProduto["id_venda_produto"]   = "";
            if(!$vendaProduto["id_produto"])         $vendaProduto["id_produto"]         = 0;
            if(!$vendaProduto["id_venda"])           $vendaProduto["id_venda"]           = "";
            if(!$vendaProduto["quantidade"])         $vendaProduto["quantidade"]         = 0;
            if(!$vendaProduto["valor_venda"])        $vendaProduto["valor_venda"]        = 0;
            if(!$vendaProduto["valor_custo"])        $vendaProduto["valor_custo"]        = 0;
            if(!$vendaProduto["id_forma_pagamento"]) $vendaProduto["id_forma_pagamento"] = NULL;
            if(!$vendaProduto["id_parent"])          $vendaProduto["id_parent"]          = NULL;
            if(!$vendaProduto["id_grade_consumo"])   $vendaProduto["id_grade_consumo"]   = NULL;
            if(!$vendaProduto["quantidade1"])        $vendaProduto["quantidade1"]        = NULL;
            if(!$vendaProduto["valor_custo1"])       $vendaProduto["valor_custo1"]       = NULL;
            if(!$vendaProduto["pontos_de_volume"])   $vendaProduto["pontos_de_volume"]   = NULL;

            $result = $this->getDoctrine()->getConnection()->fetchAll('SELECT * FROM venda_produto WHERE id_venda_produto = ? AND id_produto = ?', [ $vendaProduto['id_venda_produto'], $vendaProduto['id_produto'] ]);
            if (count($result)) {
                $this->getDoctrine()->getConnection()->update('venda_produto', $vendaProduto, [ 'id_venda_produto' => $vendaProduto['id_venda_produto'], 'id_produto' => $vendaProduto['id_produto'] ]);
            } else {
                $this->getDoctrine()->getConnection()->insert('venda_produto', $vendaProduto);
            }
        }
    }
}