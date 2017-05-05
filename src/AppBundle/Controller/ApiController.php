<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class ApiController extends Controller
{
    protected function getJson($basename) {
        $path = $this->container->get('kernel')->locateResource('@AppBundle') . '/Json/' . $basename;
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('The json file could not be located.');
        }
        $json = json_decode(file_get_contents($path));
        if (is_null($json)) {
            throw new \InvalidArgumentException('The json file is invalid.');
        }
        return $json;
    }

    protected function throwError($message, $status = 400) {
        return $this->json(['errors' => [$message]], $status);
    }

    protected function verifyAccessToken() {
        $wanted = $this->container->getParameter('nanobox.access_token');
        $given = $this->container->get('request_stack')->getCurrentRequest()->headers->get('Auth-Access-Token');
        return $given == $wanted;
    }
}
