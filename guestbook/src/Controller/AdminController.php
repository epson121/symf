<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Message\CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Twig\Environment;


class AdminController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {
    }

    #[Route('/admin/comment/review/{id}', name: 'review_comment')]
    public function reviewComment(
        Request $request,
        Comment $comment,
        WorkflowInterface $commentStateMachine ): Response
        {

            $accepted = !$request->query->get('reject');

            if ($accepted) {
                $comment->setState('published');
            } else {
                $comment->setState('rejected');
            }

            return new Response($this->twig->render('admin/review.html.twig', [
                'choice' => $accepted ? 'allow' : 'disallow',
                'comment' => $comment,
            ]));
    }
}