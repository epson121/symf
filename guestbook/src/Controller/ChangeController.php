<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ChangeController extends AbstractController {

    public function __construct(public \Twig\Environment $twig) {

    }

    #[Route('/change', name: 'change')]
    public function change() {
        $response = $this->twig->render('change.html.twig');
        return new Response($response);
    }

}