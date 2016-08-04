<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;

trait Evs
{
    /** @var array */
    private $tables = [
        'anfitriao', 'cartela_digital', 'categoria', 'classe_ponto_de_venda', 'cliente', 'cliente_bioimpedancia', 'cliente_foto', 'cliente_medidas', 
    	'estrelas', 'forma_pagamento', 'grade_consumo', 'item_pdv_tabela_precos', 'item_tabela_precos', 'origem_estrela', 'ponto_de_venda', 'produto', 'produto_imagem', 'tabela_precos', 
    	'tag', 'tag_produto', 'tipo_indicacao', 'uf', 'venda', 'venda_produto', 'historico_padrao_custos', 'custos_mensais', 'grade_consumo_pdv'
    ];

    /**
     * @param Connection $connection
     *
     * @return void
     */
    protected function createDataLogTable(Connection $connection)
    {
        $statement = $connection->executeQuery('
            SELECT *
            FROM pg_catalog.pg_tables
            WHERE tablename  = \'data_log\'');
        $results = $statement->fetchAll();

        // só cria a tabela data_log se ela não existe.
        if (!$results) {
            $connection->executeQuery('
                CREATE TABLE data_log
                (
                    id             bigserial NOT NULL,
                    tabela         character varying(100),
                    id_tabela      character varying,
                    dta            timestamp without time zone,
                    op             character varying,
                    usuario_sessao character varying,
                    ip             character varying,
                    dados_old      json,
                    dados_new      json,
                    CONSTRAINT data_log_pkey PRIMARY KEY (id)
                )
                WITH (
                    OIDS=FALSE
                );');
        }
    }

    /**
     * @param Connection $connection
     *
     * @return void
     */
    protected function createTriggersAndFunctions(Connection $connection)
    {
        foreach ($this->tables as $table) {

            $connection->executeQuery(sprintf('
                DROP TRIGGER IF EXISTS update_%s_dta ON %s;', $table, $table));

            $connection->executeQuery(sprintf('
                DROP FUNCTION IF EXISTS dta_%s_update();', $table));
            
            if($table == 'cartela_digital' || $table == 'cliente_bioimpedancia' || $table == 'cliente_foto' || $table == 'cliente_medidas' || $table == 'estrelas' 
            		|| $table == 'forma_pagamento' || $table == 'item_pdv_tabela_precos' || $table == 'origem_estrela' || $table == 'produto_imagem' || $table == 'tipo_indicacao' 
            		|| $table == 'historico_padrao_custos' || $table == 'custos_mensais'){
            	$new_id = 'NEW.id';
            	$old_id = 'OLD.id';
            	
            }else{
            	$new_id = 'NEW.id_%s';
            	$old_id = 'OLD.id_%s';
            }

            $connection->executeQuery(sprintf('
                CREATE OR REPLACE FUNCTION dta_%s_update() RETURNS trigger AS
                $BODY$
                DECLARE old_h hstore;
                        new_h hstore;
                        diff  hstore;
                        _id   varchar;
                        _op   varchar;
                        ret   record;
                BEGIN
                    _op := SUBSTRING(TG_OP, 1, 1);
                    IF (_op = \'U\') THEN
                        NEW.dta := now();
                        ret     := NEW;
                        _id     := '.$new_id.';
                        new_h   := hstore(NEW.*);
                        old_h   := hstore(OLD.*);
                        diff     = new_h - old_h;
                        old_h    = old_h - new_h;
                    ELSIF (_op = \'D\') THEN
                        _id  := '.$old_id.';
                        diff := hstore (OLD.*);
                        ret  := OLD;
                    ELSE
                        _id     := '.$new_id.';
                        NEW.dta := now();
                        ret     := NEW;
                        diff     = hstore (NEW.*);
                    END IF;
                    IF NOT diff = hstore(\'\') THEN
                       INSERT INTO data_log(tabela, id_tabela, dta, op, usuario_sessao, ip, dados_old, dados_new)
                       VALUES (TG_TABLE_NAME, _id, now(), _op, session_user::text, inet_client_addr(), old_h::json, diff::json);
                    END IF;
                    RETURN RET;
                END;
                $BODY$
                LANGUAGE plpgsql VOLATILE
                COST 100;', $table, $table, $table, $table));

            $connection->executeQuery(sprintf('
                CREATE TRIGGER update_%s_dta
                BEFORE INSERT OR UPDATE OR DELETE
                ON %s
                FOR EACH ROW
                EXECUTE PROCEDURE dta_%s_update();', $table, $table, $table));
        }
    }
}