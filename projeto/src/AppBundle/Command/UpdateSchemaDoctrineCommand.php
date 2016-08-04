<?php

namespace AppBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand as BaseUpdateSchemaDoctrineCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdateSchemaDoctrineCommand extends BaseUpdateSchemaDoctrineCommand implements ContainerAwareInterface
{
    use Evs;

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

        if ($input->getOption('force')) {
            $this->createDataLogTable($this->container->get('doctrine')->getConnection());
            $this->createTriggersAndFunctions($this->container->get('doctrine')->getConnection());
        }
    }
}