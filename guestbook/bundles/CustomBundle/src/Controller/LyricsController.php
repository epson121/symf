<?php

namespace CustomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LyricsController extends AbstractController
{
    #[Route('/lyrics', name: 'app_lyrics')]
    public function index(): Response
    {        
        return $this->render('lyrics/index.html.twig', [
            'controller_name' => 'LyricsController',
        ]);
    }
}
