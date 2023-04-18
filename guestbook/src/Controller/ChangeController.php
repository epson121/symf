<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;

class ChangeController extends AbstractController {

    #[Route('/change', name: 'change')]
    #[Template('change.html.twig')]
    public function change() {
        return [];
    }

    #[Route('/test', name: 'test')]
    #[Template('test.html.twig')]
    public function test(\App\Service\TestServiceInterface $testService) {
        $data = $testService->execute();
        return ['data' => $data];
    }

}