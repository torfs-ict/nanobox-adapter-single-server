<?php

namespace AppBundle\Controller;

use AppBundle\ServerInfo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->json($this->getJson('meta.json'));
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
}
