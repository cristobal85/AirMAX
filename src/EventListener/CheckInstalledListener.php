<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\UnicodeString;

class CheckInstalledListener {

    /**
     * @var LoogerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event) {
        $route = new UnicodeString((string) $event->getRequest()->get('_route'));
        $routeStr = (string)$route;
        if ( !isset($_ENV['APP_INSTALLED']) ) {
            if (!empty($routeStr) && 
                    !$route->startsWith('install') &&
                    !$route->startsWith('_wdt') &&
                    !$route->startsWith('_profiler') ) {
                $this->logger->info($_ENV['APP_NAME'] . ": Not installed");
                $event->setResponse(new RedirectResponse('/install'));
            }
        }
//        $this->logger->info($routeStr);
        if ( isset($_ENV['APP_INSTALLED']) && $route->startsWith('install')) {
            $event->setResponse(new RedirectResponse('/'));
        }
    }

}
