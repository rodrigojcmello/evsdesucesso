<?php

namespace AppBundle;

use Symfony\Bridge\Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PDO;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PHPExcel;
use PHPExcel_IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Util
{
    private $controller;

    public function __construct($controller = null)
    {
        $this->controller = $controller;
    }

    public static function log($string)
    {
        // create a log channel
        $log = new Logger('cm2pay');

        $log->pushHandler(new StreamHandler('/tmp/evs.log', Logger::INFO));

        $log->addInfo($string);

    }

    public function dataTableSource($request, $params)
    {
        $aWhere = array_key_exists("where", $params) ? $params["where"] : NULL;
        $sGroup = array_key_exists("group", $params) ? $params["group"] : NULL;

        if ($request->get('start')) {
            $iDisplayStart = $request->get('start') - 0;
            $iDisplayLength = $request->get('length') - 0;
        } else {
            $iDisplayStart = $request->get('iDisplayStart') - 0;
            $iDisplayLength = $request->get('iDisplayLength') - 0;
        }

        if ($iDisplayLength == 0) {
            $iDisplayLength = $request->get('length') - 0;
        }

        if (count($aWhere) > 0) {
            $oWhere = " WHERE " . join(" AND ", $aWhere);
        } else {
            $oWhere = "";
        }

        if($sGroup) $sGroup = " GROUP BY " . $sGroup;

        $sLimit = "";
        if ($iDisplayLength != -1) {
            $sLimit = " OFFSET " . ($iDisplayStart) . " LIMIT " . ($iDisplayLength - 0);
        }

        $sOrder = "";
        if (($objOrderBy = $request->get('order')) != null) {
            $sOrder = " ORDER BY " . ($objOrderBy[0]['column'] + 1) . " " . $objOrderBy[0]['dir'];
        }

        $cmd = $params["query"] . " $oWhere $sGroup $sOrder $sLimit";

        $connection = $this->controller;

        $statement = $connection->prepare($cmd);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_NUM);

        $qt = \count($result);

        $fakeTotal = $iDisplayStart + $iDisplayLength;

        //
        // força a impressão de que existe uma nova página
        // se a quantidade de registros exibidos
        // for inferior ao limite por página
        //
        if ($qt == $iDisplayLength)
            $fakeTotal += 10;

        $output = json_encode(array(
            "sEcho"           => intval($request->get('sEcho')),
            "recordsTotal"    => $fakeTotal,
            "recordsFiltered" => $fakeTotal,
            "draw"            => $qt,
            "data"            => $result
        ));

        return ($output);
    }

    public function dataTableSourceUncoupled($result, $request)
    {
        if ($request->get('iDisplayStart') == null || $request->get('iDisplayLength') == null) {
            $iDisplayStart = $request->get('start') - 0;
            $iDisplayLength = $request->get('length') - 0;
        } else {
            $iDisplayStart = $request->get('iDisplayStart') - 0;
            $iDisplayLength = $request->get('iDisplayLength') - 0;
        }

        if ($iDisplayLength == 0) {
            $iDisplayLength = $request->get('length') - 0;
        }

        $qt = \count($result);

        $fakeTotal = $iDisplayStart + $iDisplayLength;

        //
        // força a impressão de que existe uma nova página
        // se a quantidade de registros exibidos
        // for inferior ao limite por página
        //
        if ($qt == $iDisplayLength)
            $fakeTotal += 10;

        $output = json_encode(array(
            "sEcho" => intval($request->get('sEcho')),
            "recordsTotal" => $fakeTotal,
            "recordsFiltered" => $fakeTotal,
            "draw" => $qt,
            "data" => $result
        ));

        return ($output);
    }

    public function queryToExcel( $queryResult, $title, $author = "unknow" ) {
        $columns = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'BB', 'CC', 'DD', 'EE', 'FF', 'GG', 'HH', 'II', 'JJ', 'KK', 'LL', 'MM', 'NN', 'OO', 'PP', 'QQ', 'RR', 'SS', 'TT', 'UU', 'VV', 'WW', 'XX', 'YY', 'ZZ' );

        $phpExcelObject = new \PHPExcel();
        $phpExcelObject->getProperties()->setCreator( $author )
                                        ->setLastModifiedBy( $author )
                                        ->setTitle( $title );

        /*
         * setActiveSheetIndex(0) = 0 pois é para inserir na primeira planilha
         * A1 para inserir na primeira célula
         * $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A1', 'Hello');
         */

        $row = 1;
        $col = 0;
        foreach( $queryResult[0] as $key => $value ) {
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue($columns[$col++] . $row, $key);
        }

        $itemsLength = count($queryResult);
        for( $i = 0; $i < $itemsLength; $i++ ) {
            $col = 0;
            $row++;

            foreach( $queryResult[$i] as $key => $value ) {
                $phpExcelObject->setActiveSheetIndex(0)->setCellValue($columns[$col++] . $row, $value);
            }
        }

        // Define o indice da planilha que vai aparecer ao abrir o documento
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    public function responseExcelDownload( $phpExcelObject, $name ) {
        $writer = PHPExcel_IOFactory::createWriter( $phpExcelObject, 'Excel5' );
        $response = new StreamedResponse(function() use( $writer ) {
            $writer->save('php://output');
        });

        $dispositionHeader = $response->headers->makeDisposition( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name . '.xls' );
        $response->headers->set( 'Content-Type', 'text/vnd.ms-excel; charset=utf-8' );
        $response->headers->set( 'Pragma', 'public' );
        $response->headers->set( 'Cache-Control', 'maxage=1' );
        $response->headers->set( 'Content-Disposition', $dispositionHeader );

        return $response;
    }
}