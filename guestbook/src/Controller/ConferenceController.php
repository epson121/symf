<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {

        return $this->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]);
    }

    // #[Route('/hello/{name}', name: 'hellopage')]
    // public function test(string $name, Request $request): Response {
    //     $g = $request->query->get('hello');
    //     return new Response("Hello, $name. Parameter has $g");
    // }
    
     #[Route('/conference/{id}', name: 'conference')]
     public function show(
        Request $request,
        \App\Entity\Conference $conference,
        \App\Repository\CommentRepository $commentRepository): Response
     {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return $this->render('conference/show.html.twig',
            [
                'conference' => $conference,
                'comments' => $paginator,
                'previous' => $offset - \App\Repository\CommentRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset +
                \App\Repository\CommentRepository::PAGINATOR_PER_PAGE)
            ]
        );
     }
}