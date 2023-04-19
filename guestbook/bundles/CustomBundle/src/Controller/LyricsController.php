<?php

namespace CustomBundle\Controller;

use CustomBundle\Entity\Lyrics;
use Doctrine\ORM\EntityManagerInterface;
use CustomBundle\Repository\LyricsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LyricsController extends AbstractController
{
    #[Route('/lyrics', name: 'app_lyrics')]
    public function index(EntityManagerInterface $entityManager): Response
    {        

        $lyrics = $entityManager->getRepository(Lyrics::class)->findAll();

        return $this->render('lyrics/index.html.twig', [
            'lyrics' => $lyrics
        ]);
    }
}
