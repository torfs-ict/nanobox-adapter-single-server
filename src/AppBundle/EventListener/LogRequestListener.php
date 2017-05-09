<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

class LogRequestListener
{
    private $kernel;
    private $loggingEnabled;

    public function __construct(KernelInterface $kernel, bool $loggingEnabled)
    {
        $this->kernel = $kernel;
        $this->loggingEnabled = $loggingEnabled;
    }

    public function onKernelTerminate(PostResponseEvent $event) {
        if (!$this->loggingEnabled) {
            return;
        }
        $request = $event->getRequest();
        $log = [];
        /** @var RouterInterface $route */
        $route = $request->attributes->get('_route');
        $log['route'] = $route;
        $log['headers'] = $request->headers->all();
        if ($request->getContentType() == 'json') {
            $log['content'] = json_decode($request->getContent());
        } else {
            $log['content'] = $request->getContent();
        }
        $log = json_encode($log, JSON_PRETTY_PRINT);
        $filename = sprintf('%s/request.%s.%d.log.json', $this->kernel->getLogDir(), $route, time());
        file_put_contents($filename, $log);
    }
}