<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use App\Repository\ConferenceRepository;

class TwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $conferenceRepository;
    
    public function __construct(
        Environment $twig,
        ConferenceRepository $conferenceRepository
    ) {
        $this->twig = $twig;
        $this->conferenceRepository = $conferenceRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        //$this->twig->addGlobal('conferences', $this->conferenceRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
