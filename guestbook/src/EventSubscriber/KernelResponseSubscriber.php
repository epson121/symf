<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class KernelResponseSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            \Symfony\Component\HttpKernel\KernelEvents::RESPONSE => [
                'handleResponse'
            ],
            \Symfony\Component\HttpKernel\KernelEvents::REQUEST => [
                'handleRequest'
            ],
            \Symfony\Component\HttpKernel\KernelEvents::CONTROLLER => [
                'handleController'
            ]
        ];
    }

    /**
     * Add custom header to the response
     */
    public function handleResponse(\Symfony\Component\HttpKernel\Event\ResponseEvent $event)
    {
        /** Symfony\Component\HttpFoundation\Response */
        $response = $event->getResponse();
        $response->headers->set('custom_header', 222);
        return;
    }

    /**
     * Kill the flow if kill=1 is set on query string
     */
    public function handleRequest(\Symfony\Component\HttpKernel\Event\RequestEvent $event)
    {
        $shouldKill = $event->getRequest()->query->get('kill');
        if ($shouldKill) {
            $response = new Response('Do not call me');
            $event->setResponse($response);
        }
        
        return;
    }

    /**
     * Change controller on the fly (needs some twig shenaningans - it was missing when calling render)
     */
    public function handleController(\Symfony\Component\HttpKernel\Event\ControllerEvent $event) {
        $shouldChangeController = $event->getRequest()->query->get('change');
        if ($shouldChangeController) {
            $instance = new \App\Controller\ChangeController();
            $callable = [$instance, 'change'];
            $event->setController($callable);
        }

        return;
    }

}