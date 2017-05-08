<?php

namespace AppBundle\Controller;

use AppBundle\ServerInfo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends ApiController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/meta", name="meta")
     */
    public function metaAction() {
        $json = $this->getJson('meta.json');
        $json->external_iface = $this->container->getParameter('nanobox.external_iface');
        $json->internal_iface = $this->container->getParameter('nanobox.internal_iface');
        $json->bootstrap_script = $this->generateUrl('bootstrap', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $json->ssh_user = $this->container->getParameter('nanobox.ssh_user');
        return $this->json($json);
    }

    /**
     * @Route("/catalog", name="catalog")
     */
    public function catalogAction() {
        $json = $this->getJson('catalog.json');
        $json[0]->plans[0]->specs[0]->cpu = ServerInfo::getCpuCount();
        $json[0]->plans[0]->specs[0]->ram = ServerInfo::getMemoryAmount();
        $json[0]->plans[0]->specs[0]->disk = ServerInfo::getStorageAmount();
        return $this->json($json);
    }

    /**
     * @Route("/verify", name="verify", methods={"POST"})
     */
    public function verifyAction() {
        if ($this->verifyAccessToken()) {
            return new Response();
        } else {
            return $this->json(['errors' => ['Invalid access token']], 400);
        }
    }

    /**
     * @Route("/bootstrap.sh", name="bootstrap", methods={"GET"})
     */
    public function bootstrapAction() {
        $url = $this->container->getParameter('nanobox.bootstrap_script');
        $content = file_get_contents($url);
        return new Response(
            $content, 200, ['Content-Type' => 'text/plain']
        );
    }
}
