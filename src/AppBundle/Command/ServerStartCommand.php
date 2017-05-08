<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ServerStartCommand as BaseServerStartCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerStartCommand extends BaseServerStartCommand
{
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input->setOption('port', $this->getContainer()->getParameter('endpoint.port'));
        $input->setArgument('address', $this->getContainer()->getParameter('nanobox.external_ip'));
        return parent::execute($input, $output);
    }
}
