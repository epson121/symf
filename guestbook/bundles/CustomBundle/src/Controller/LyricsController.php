<?php

namespace CustomBundle\Controller;

use CustomBundle\Entity\Lyrics;
use CustomBundle\Form\Type\LyricsType;
use Doctrine\ORM\EntityManagerInterface;
use CustomBundle\Repository\LyricsRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LyricsController extends AbstractController
{
    #[Route('/lyrics', name: 'app_lyrics')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {        
        $lyricsEntity = new Lyrics();
        $form = $this->createForm(LyricsType::class, $lyricsEntity);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $lyricsEntity->setContent($data->getContent());
            $lyricsEntity->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($lyricsEntity);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('lyrics/index.html.twig', [
            'lyrics' => $entityManager->getRepository(Lyrics::class)->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/lyrics/new', name: 'app_lyrics_new')]
    public function show_new(EntityManagerInterface $entityManager): Response
    {
        $newLyrics = $entityManager->getRepository(Lyrics::class)->findByDate(new DateTime('-2 hour'));

        return $this->render('lyrics/new.html.twig', [
            'lyrics' => $newLyrics
        ]);
    }

    #[Route('/lyrics/delete/{id}', name: 'app_lyrics_delete')]
    public function delete(EntityManagerInterface $entityManager, Lyrics $lyrics): Response
    {        
        $entityManager->remove($lyrics);
        $entityManager->flush();
        return $this->redirectToRoute('app_lyrics');
    }

    #[Route('/lyrics/{id}', name: 'app_lyrics_show')]
    public function show(Lyrics $lyrics): Response
    {        
        return $this->render('lyrics/show.html.twig', [
            'lyrics' => $lyrics
        ]);
    }
}
