<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class KeysController extends ApiController
{
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

        $append = sprintf("\n# Nanobox key %s\n%s", $name, $key);
        $user = posix_getpwuid(posix_getuid());
        $filename = sprintf('%s/.ssh/authorized_keys', $user['dir']);
        @mkdir(dirname($filename), 0700, true);
        file_put_contents($filename, $append, FILE_APPEND);

        return $this->json(['id' => $name], 201);
    }
}
