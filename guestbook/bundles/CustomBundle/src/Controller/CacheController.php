<?php

namespace CustomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController {


    public function __construct(private \Psr\Cache\CacheItemPoolInterface $customRedisPool)
    {
        
    }

    #[Route('/cache', name: 'app_cache_idx')]
    public function show()
    {
        $data = $this->customRedisPool->getItem('test_');
        
        if (!$data->isHit()) {
            $data->set('my-custom-cache');
            $this->customRedisPool->save($data);
        }

        return $this->render('cache/index.html.twig', [
            'data' => $data->get()
        ]);
    }

}