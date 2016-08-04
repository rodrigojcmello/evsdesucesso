<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:dump-database')
            ->setDescription('Cria o dump do banco de dados para o front-end.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dump =
            'CREATE TABLE IF NOT EXISTS controle' . PHP_EOL .
            '(' . PHP_EOL .
            '    tabela TEXT NOT NULL PRIMARY KEY,' . PHP_EOL .
            '    dta    TEXT NOT NULL' . PHP_EOL .
            ')' . PHP_EOL .
            ';' . PHP_EOL;

        $tables = $this->getTables();
        foreach ($tables as $table) {

            $dump .= sprintf('CREATE TABLE IF NOT EXISTS %s', $table['table_name']) . PHP_EOL;
            $dump .= '(' . PHP_EOL;
            $isFirstColumn = true;

            $columns = $this->getColumns($table['table_name']);

            $longestColumnNameLength = 0;
            foreach ($columns as $column) {
                if (strlen($column['column_name']) > $longestColumnNameLength) {
                    $longestColumnNameLength = strlen($column['column_name']);
                }
            }

            foreach ($columns as $column) {
                $dump .= '    ' . str_pad($column['column_name'], $longestColumnNameLength, ' ', STR_PAD_RIGHT) . ' ' . ($column['data_type'] == 'integer' ? 'integer' : ($column['data_type'] == 'double precision' ? 'float  ' : 'text   ')) . ' ' . ($column['is_nullable'] === 'YES' ? '' : 'NOT ') . 'NULL';
                if ($isFirstColumn) {
                    $dump .= ' PRIMARY KEY';
                    $isFirstColumn = false;
                }
                if ($column !== $columns[count($columns)-1]) {
                    $dump .= ',';
                }
                $dump .= PHP_EOL;
            }
            $dump .= ')' . PHP_EOL;

            if ($table !== $tables[count($tables)-1]) {
                $dump .= ';' . PHP_EOL;
            }
        }

        $output->write($dump);
    }

    private function getColumns($table)
    {
        $statement = $this->getContainer()
            ->get('doctrine')
            ->getConnection()
            ->executeQuery('
                SELECT column_name, is_nullable, data_type
                FROM information_schema.columns
                WHERE table_schema = \'public\' AND table_name = ?
                ORDER BY ordinal_position;', [ $table ]);

        return $statement->fetchAll();
    }

    private function getTables()
    {
        $statement = $this->getContainer()
            ->get('doctrine')
            ->getConnection()
            ->executeQuery('
                SELECT table_name
                FROM information_schema.columns
                WHERE table_schema = \'public\' AND table_name <> \'data_log\'
                GROUP BY table_name;');

        return $statement->fetchAll();

    }
}