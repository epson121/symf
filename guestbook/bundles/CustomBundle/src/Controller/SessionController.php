<?php

namespace CustomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController {


    public function __construct(private RequestStack $requestStack)
    {
        
    }

    #[Route('/session', name: 'app_session_idx')]
    public function show()
    {
        $session = $this->requestStack->getSession();
        $data = $session->get('data', 'no-session-data');

        return $this->render('session/index.html.twig', [
            'data' => $data
        ]);

    }


    #[Route('/session/{data}', name: 'app_session_index')]
    public function index(string $data)
    {
        $session = $this->requestStack->getSession();

        $session->set('data', $data);

        $session->save();

        $this->addFlash(
            'notice',
            'Some data is saved to session'
        );

        return $this->redirectToRoute('app_session_idx');
    }

}