<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class ApiController extends Controller
{
    /**
     * Loads a predefined JSON file for a request.
     *
     * @param string $basename The basename of the JSON file e.g. "catalog.json"
     * @return object The parsed JSON object
     */
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

    /**
     * Returns the absolute path to the file which will flag the server as provisioned.
     */
    protected function getProvisionFlagFilename() {
        return realpath(sprintf('%s/../var', $this->get('kernel')->getRootDir())) . DIRECTORY_SEPARATOR . 'provisioned.flag';
    }

    /**
     * Checks if the server has already been provisioned by Nanobox.
     *
     * @return bool
     */
    protected function isProvisioned() {
        $filename = $this->getProvisionFlagFilename();
        clearstatcache(null, $filename);
        return file_exists($filename);
    }

    /**
     * Throws an error as wanted by Nanobox. The controller should return the response
     * this method generates.
     *
     * @param string $message The error message.
     * @param int $status The HTTP status code
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function throwError($message, $status = 400) {
        return $this->json(['errors' => [$message]], $status);
    }

    /**
     * Checks if the authentication token passed through the HTTP header is valid.
     *
     * @return bool
     */
    protected function verifyAccessToken() {
        $wanted = $this->container->getParameter('nanobox.access_token');
        $given = $this->container->get('request_stack')->getCurrentRequest()->headers->get('Auth-Access-Token');
        return $given == $wanted;
    }
}
