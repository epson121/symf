<?php

namespace App\Consumer;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestMessageConsumer {

    public function __invoke(\App\Message\TestMessage $message)
    {
        // $a = 1;
        // die('message received');
    }

}