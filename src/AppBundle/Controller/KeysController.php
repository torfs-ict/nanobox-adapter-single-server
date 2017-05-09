<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KeysController extends ApiController
{
    protected function getAuthorizedKeysFilename() {
        $user = posix_getpwuid(posix_getuid());
        $filename = sprintf('%s/.ssh/authorized_keys', $user['dir']);
        if (!file_exists($filename)) {
            @mkdir(dirname($filename), 0700, true);
            touch($filename);
        }
        return $filename;
    }

    /**
     * @Route("/keys", name="keys", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        $name = $request->get('name');
        $key = $request->get('key');

        $this->get('logger')->debug($request->getContent());
        $append = sprintf("\n# Nanobox key %s\n%s", $name, $key);
        $filename = $this->getAuthorizedKeysFilename();
        file_put_contents($filename, $append, FILE_APPEND);

        return $this->json(['id' => $name], 201);
    }

    /**
     * @Route("/keys/{id}", methods={"DELETE"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function deleteAction(string $id) {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        $keys = file_get_contents($this->getAuthorizedKeysFilename());
        $ret = trim(preg_replace_callback('/^# Nanobox key (.*)\n(.*)$/m', function($matches) use ($id) {
            $keyId = $matches[1];
            if ($keyId == $id) {
                return '';
            } else {
                return $matches[0];
            }
        }, $keys));
        file_put_contents($this->getAuthorizedKeysFilename(), $ret);
        return new Response();
    }

    /**
     * @Route("/keys/{id}", name="keys-query", methods={"GET"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function queryAction(string $id) {
        if (!$this->verifyAccessToken()) {
            return $this->throwError('Access was denied');
        }
        $keys = file_get_contents($this->getAuthorizedKeysFilename());
        $key = '';
        $ret = preg_match_all('/^# Nanobox key (.*)\n(.*)$/m', $keys, $matches, PREG_SET_ORDER);
        if ($ret > 0) {
            foreach($matches as $match) {
                if ($match[1] == $id) $key = $match[2];
            }
        }
        if (empty($key)) {
            return $this->throwError('Unable to find the requested key.', 404);
        } else {
            return $this->json(['id' => $id, 'name' => $id, 'key' => $key], 201);
        }
    }
}
