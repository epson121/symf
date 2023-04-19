<?php

namespace CustomBundle\EventSubscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KernelResponseSubscriber implements EventSubscriberInterface {

    public function __construct(private \Twig\Environment $twigEnv)
    {
    }

    public static function getSubscribedEvents() {
        return [
            \Symfony\Component\HttpKernel\KernelEvents::RESPONSE => [
                'handleResponse'
            ],
            \Symfony\Component\HttpKernel\KernelEvents::REQUEST => [
                'addTwigPath'
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
        $response->headers->set('custom_header', 233322);
        return;
    }

    public function addTwigPath(RequestEvent $event)
    {
        $path = __DIR__ . '/../templates/';

        /** @var Twig\Loader\FilesystemLoader */
        $loader = $this->twigEnv->getLoader();
        $loader->addPath($path, $namespace = '__main__');
    }

}