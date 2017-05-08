<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ServerRunCommand as BaseServerRunCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerRunCommand extends BaseServerRunCommand
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
