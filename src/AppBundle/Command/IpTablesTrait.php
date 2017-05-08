<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ServerCommand;

trait IpTablesTrait
{
    public function openFirewall() {
        /**
         * @var ServerCommand $this
         */
        $ip = $this->getContainer()->getParameter('nanobox.external_ip');
        $port = $this->getContainer()->getParameter('endpoint.port');
        $cmd = sprintf('iptables -A INPUT -p tcp -d %s --dport %d -j ACCEPT', $ip, $port);
        exec($cmd);
    }
}