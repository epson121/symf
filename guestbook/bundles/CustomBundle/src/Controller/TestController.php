<?php

namespace CustomBundle\Controller;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController {


    public function __construct(private \Psr\Cache\CacheItemPoolInterface $customRedisPool)
    {
        
    }

    #[Route('/tester', name: 'app_test_idx')]
    #[Cache(public: true, maxage: 20)]
    public function show(Request $request)
    {

        // $lastModified = new DateTime('2020-01-01');
        // // create a Response with an ETag and/or a Last-Modified header
        // $response = new Response();
        // $response->setEtag('custom-etag');
        // $response->setLastModified($lastModified);

        // // Set response as public. Otherwise it will be private by default.
        // $response->setPublic();

        // // Check that the Response is not modified for the given Request
        // if ($response->isNotModified($request)) {
        //     // return the 304 Response immediately
        //     return $response;
        // }

        // $data = ['text' => 'some text'];

        // $response = new Response();
        // $response->setLastModified($lastModified);

        $date = new DateTime();

        $response = $this->render('test/index.html.twig', [
            'date' => $date->format('Y-m-d H:i:s')
        ]);

        return $response;
    }

    #[Route('/test_esi', name: 'app_test_esi')]
    #[Cache(public: true, maxage: 60)]
    public function esi(Request $request)
    {

        $date = new DateTime();

        $response = $this->render('test/esi.html.twig', [
            'date' => $date->format('Y-m-d H:i:s')
        ]);

        return $response;
    }

}