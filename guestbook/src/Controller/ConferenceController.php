<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use App\Entity\Comment;
use App\Form\CommentFormType;

class ConferenceController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        #[Autowire('%admin_email%')] private string $adminEmail
    ) {
    }

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
    
     #[Route('/conference/{slug}', name: 'conference')]
     public function show(
        Request $request,
        \App\Entity\Conference $conference,
        \App\Repository\CommentRepository $commentRepository,
        #[Autowire('%photo_dir%')] string $photoDir
        ): Response {

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);

            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $comment->setPhotoFilename($filename);
            }

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            // test send email
            $this->mailer->send((new NotificationEmail())
                ->subject('New comment posted')
                ->htmlTemplate('emails/comment_notification.html.twig')
                ->from($this->adminEmail)
                ->to($this->adminEmail)
                ->context(['comment' => $comment]));

            return $this->redirectToRoute('conference', ['slug' => $conference->getSlug()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return $this->render('conference/show.html.twig',
            [
                'conference' => $conference,
                'comments' => $paginator,
                'previous' => $offset - \App\Repository\CommentRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset +
                \App\Repository\CommentRepository::PAGINATOR_PER_PAGE),
                'comment_form' => $form
            ]
        );
     }

    #[Route('/conference_header', name: 'conference_header')]
    public function conferenceHeader(
        ConferenceRepository
        $conferenceRepository): Response
    {
        return $this->render('conference/header.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ])->setSharedMaxAge(3600);
    }
}