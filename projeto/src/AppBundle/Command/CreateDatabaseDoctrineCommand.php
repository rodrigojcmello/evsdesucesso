<?php

namespace AppBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand as BaseCreateDatabaseDoctrineCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateDatabaseDoctrineCommand extends BaseCreateDatabaseDoctrineCommand implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->installExtensions();
    }

    /**
     * @return void
     */
    private function installExtensions()
    {
        $this->container
            ->get('doctrine')
            ->getConnection()
            ->executeQuery('CREATE EXTENSION IF NOT EXISTS hstore;');
    }
}