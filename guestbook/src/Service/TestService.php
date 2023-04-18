<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'dev')]
class TestService implements TestServiceInterface {

    public function execute()
    {
        return "Test Service Data";
    }

}
