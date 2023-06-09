<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Message\CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Twig\Environment;
use App\Notification\CommentApprovedNotification;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {
    }

    #[Route('/comment/review/{id}', name: 'review_comment')]
    public function reviewComment(
        Request $request,
        Comment $comment,
        NotifierInterface $notifier,
        WorkflowInterface $commentStateMachine ): Response
        {

            $accepted = !$request->query->get('reject');

            if ($accepted) {
                $comment->setState('published');
            } else {
                $comment->setState('rejected');
            }

            if ($comment->getState() == 'published') {
                $recipient = new Recipient($comment->getEmail());
                $notifier->send(
                    new CommentApprovedNotification($comment), ... [$recipient]
                );
            }
            
            return new Response($this->twig->render('admin/review.html.twig', [
                'choice' => $accepted ? 'allow' : 'disallow',
                'comment' => $comment,
            ]));
    }

    
    #[Route('/http-cache/{uri<.*>}', methods: ['PURGE'])]
    public function purgeHttpCache(
        KernelInterface $kernel,
        Request $request,
        string $uri,
        StoreInterface $store): Response
    {
        if ('prod' === $kernel->getEnvironment()) {
            return new Response('KO', 400);
        }
        $store->purge($request->getSchemeAndHttpHost().'/'.$uri);
        return new Response('Done');
    }
}