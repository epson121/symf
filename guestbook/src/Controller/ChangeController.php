<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ChangeController extends AbstractController {

    public function __construct(
        private $customArgument,
        private ContainerBagInterface $params
    ) {}

    #[Route('/change', name: 'change')]
    #[Template('change.html.twig')]
    public function change() {
        return [];
    }

    #[Route('/test', name: 'test')]
    #[Template('test.html.twig')]
    public function test(
        \App\Service\TestServiceInterface $testService,
        MessageBusInterface $bus
    ) {
        $bus->dispatch(new \App\Message\TestMessage('This is a new message'));
        $data = $testService->execute();
        $env = $this->params->get('kernel.environment');
        return ['data' => $data, 'custom_argument' => $this->customArgument, 'env' => $env];
    }

}