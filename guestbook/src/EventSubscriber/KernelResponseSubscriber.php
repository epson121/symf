<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KernelResponseSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            \Symfony\Component\HttpKernel\KernelEvents::RESPONSE => [
                'handleResponse'
            ]
        ];
    }

    public function handleResponse(\Symfony\Component\HttpKernel\Event\ResponseEvent $event)
    {
        /** Symfony\Component\HttpFoundation\Response */
        $response = $event->getResponse();
        $response->headers->set('custom_header', 222);
        return;
    }

}