<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ServersController extends ApiController
{
    /**
     * @Route("/servers", name="servers", methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction()
    {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        return $this->json($this->getJson('servers.json'), 201);
    }

    /**
     * @Route("/servers/{id}", name="query")
     */
    public function queryAction() {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        $json = $this->getJson('query.json');
        $json->external_ip = $this->container->getParameter('nanobox.external_ip');
        $json->internal_ip = $this->container->getParameter('nanobox.internal_ip');
        return $this->json($json, 201);
    }

    /**
     * @Route("/servers/{id}", name="delete", methods={"DELETE"})
     */
    public function cancelAction() {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        return new Response();
    }

    /**
     * @Route("/servers/{id}/reboot", name="reboot", methods={"PATCH"})
     */
    public function rebootAction() {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        return new Response();
    }
}
